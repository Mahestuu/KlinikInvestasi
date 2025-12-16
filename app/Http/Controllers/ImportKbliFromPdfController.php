<?php

namespace App\Http\Controllers;

use App\Models\Kbli;
use App\Models\Dinas;
use App\Models\KategoriKbli;
use App\Models\PersyaratanPerizinan;
use App\Models\SubPoin;
use App\Models\SubPoinDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;

class ImportKbliFromPdfController extends Controller
{
    public function import(Request $request)
    {
        try {
            // Get file path from request (uploaded file)
            $filePath = $request->input('pdf_file');

            // Fallback: untuk testing dengan file di public (optional)
            if (!$filePath || !file_exists($filePath)) {
                // Coba cari di public jika file_path tidak ada (untuk testing saja)
                $publicFilePath = $request->input('file_path');
                if ($publicFilePath && file_exists(public_path($publicFilePath))) {
                    $filePath = public_path($publicFilePath);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'File PDF tidak ditemukan. Pastikan file sudah diupload dengan benar.'
                    ], 404);
                }
            }

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File PDF tidak dapat diakses: ' . $filePath
                ], 404);
            }

            // Validate file type
            $mimeType = mime_content_type($filePath);
            if ($mimeType !== 'application/pdf' && pathinfo($filePath, PATHINFO_EXTENSION) !== 'pdf') {
                return response()->json([
                    'success' => false,
                    'message' => 'File yang diupload bukan file PDF yang valid.'
                ], 400);
            }

            // Parse PDF - ensure class is loaded (PSR-0 autoloading issue fix)
            if (!class_exists('Smalot\PdfParser\Parser')) {
                // Try to load manually if autoloader didn't catch it
                $parserFile = base_path('vendor/smalot/pdfparser/src/Smalot/PdfParser/Parser.php');
                if (!file_exists($parserFile)) {
                    throw new \Exception('PDF Parser library tidak ditemukan. Silakan jalankan: composer require smalot/pdfparser dan restart web server.');
                }

                // Use spl_autoload_register for PSR-0 compatibility
                spl_autoload_register(function ($class) {
                    if (strpos($class, 'Smalot\\PdfParser\\') === 0) {
                        $classFile = base_path('vendor/smalot/pdfparser/src/' . str_replace('\\', '/', $class) . '.php');
                        if (file_exists($classFile)) {
                            require_once $classFile;
                        }
                    }
                });

                // Try loading again after registering autoloader
                if (!class_exists('Smalot\PdfParser\Parser')) {
                    require_once $parserFile;
                }
            }

            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);

            // Coba ekstrak text dengan informasi font/style jika tersedia
            // Catatan: smalot/pdfparser mungkin tidak selalu memberikan info formatting
            $text = $pdf->getText();

            // Coba ambil informasi font dari pages jika tersedia
            $pages = $pdf->getPages();
            $textWithFontInfo = [];
            foreach ($pages as $page) {
                try {
                    $details = $page->get('Details');
                    // Coba ekstrak dengan detail lebih
                    $textWithFontInfo[] = $page->getText();
                } catch (\Exception $e) {
                    // Fallback ke method biasa
                    $textWithFontInfo[] = $page->getText();
                }
            }
            $text = implode("\n", $textWithFontInfo);

            Log::info('PDF Text Extracted', ['length' => strlen($text)]);

            // Parse data dari text
            $importedData = $this->parsePdfText($text);

            Log::info('Parsed Data', [
                'total_parsed' => count($importedData),
                'sample' => isset($importedData[0]) ? [
                    'kode' => $importedData[0]['kode'] ?? 'N/A',
                    'nama' => substr($importedData[0]['nama'] ?? 'N/A', 0, 50),
                    'persyaratan_count' => count($importedData[0]['persyaratan'] ?? [])
                ] : []
            ]);

            // Import ke database dalam transaction
            DB::beginTransaction();

            $importedCount = 0;
            $errors = [];

            foreach ($importedData as $index => $data) {
                try {
                    Log::info("Importing KBLI", [
                        'index' => $index,
                        'kode' => $data['kode'] ?? 'N/A',
                        'persyaratan_count' => count($data['persyaratan'] ?? [])
                    ]);

                    $kbli = $this->importKbliData($data);

                    if ($kbli && $kbli->kbli_id) {
                        $importedCount++;
                        Log::info("KBLI imported successfully", [
                            'kbli_id' => $kbli->kbli_id,
                            'kode' => $kbli->kode
                        ]);
                    } else {
                        $errors[] = "KBLI {$data['kode']} gagal dibuat (return null)";
                        Log::warning("KBLI import returned null", ['kode' => $data['kode'] ?? 'unknown']);
                    }
                } catch (\Exception $e) {
                    $kode = $data['kode'] ?? 'unknown';
                    $errorMsg = "Error importing KBLI {$kode}: " . $e->getMessage();
                    $errors[] = $errorMsg;
                    Log::error('Import Error', [
                        'kode' => $kode,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimpor {$importedCount} KBLI",
                'imported_count' => $importedCount,
                'total_parsed' => count($importedData),
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import PDF Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse text dari PDF menjadi struktur data dengan fleksibilitas tinggi
     */
    private function parsePdfText(string $text): array
    {
        $results = [];
        $lines = explode("\n", $text);

        $currentKbli = null;
        $currentPersyaratan = null;
        $currentSubPoin = null;
        $detectedDinas = null;

        // State flags untuk tracking
        $state = 'none'; // none, kbli_name, ruang_lingkup, persyaratan_name, subpoin, detail
        $emptyLineCount = 0; // Track baris kosong berturut-turut

        foreach ($lines as $lineIndex => $line) {
            $originalLine = $line;
            $line = trim($line);
            $isEmptyLine = empty($line);

            // Deteksi Dinas
            if (preg_match('/^Dinas\s*[:]?\s*(.+)$/i', $line, $dinasMatches)) {
                $newDinas = trim($dinasMatches[1]);
                $newDinas = rtrim($newDinas, '.,;');
                if (!$detectedDinas || strlen($newDinas) > strlen($detectedDinas)) {
                    $detectedDinas = $newDinas;
                    Log::info('Dinas detected', ['dinas' => $detectedDinas]);
                }
                continue;
            }

            if ($isEmptyLine) {
                $emptyLineCount++;
                // Jika baris kosong saat mengumpulkan nama persyaratan,
                // mungkin ini pemisah, tapi tetap lanjutkan mengumpulkan
                // sampai menemukan indikator jelas (jangan terlalu cepat reset)
                if ($emptyLineCount > 3 && $state === 'persyaratan_name' && $currentPersyaratan) {
                    // Jika terlalu banyak baris kosong, kemungkinan nama sudah selesai
                    $state = 'subpoin';
                }
                continue;
            }

            $emptyLineCount = 0;

            // ========== DETEKSI KBLI ==========
            // Pattern 1: "KBLI: 12345" atau "Kode: 12345"
            if (preg_match('/^(?:KBLI|Kode|KBLI\s+Kode)[\s:]*(\d{4,5}[A-Z]?)[\s\-]*(?:[-–—])?\s*(.+)?$/i', $line, $kbliMatches)) {
                $this->saveCurrentKbli($currentKbli, $results, $currentPersyaratan, $currentSubPoin, $detectedDinas);

                $kbliName = isset($kbliMatches[2]) && !empty(trim($kbliMatches[2])) ? trim($kbliMatches[2]) : '';
                $currentKbli = [
                    'kode' => strtoupper($kbliMatches[1]),
                    'nama' => $kbliName,
                    'ruang_lingkup' => '',
                    'persyaratan' => [],
                    'dinas_name' => $detectedDinas,
                    'kategori_kode' => null,
                    'kategori_nama' => null
                ];
                $currentPersyaratan = null;
                $currentSubPoin = null;
                $state = $kbliName ? 'ruang_lingkup' : 'kbli_name';
                continue;
            }

            // Pattern 2: "12345 Nama" atau "47211A Nama"
            if (preg_match('/^(\d{4,5}[A-Z]?)[\s\-]+(.+)$/ui', $line, $simpleMatches)) {
                if (!$currentKbli || (!empty($currentKbli['nama']) && !empty($currentKbli['persyaratan']))) {
                    $this->saveCurrentKbli($currentKbli, $results, $currentPersyaratan, $currentSubPoin, $detectedDinas);

                    $currentKbli = [
                        'kode' => strtoupper($simpleMatches[1]),
                        'nama' => trim($simpleMatches[2]),
                        'ruang_lingkup' => '',
                        'persyaratan' => [],
                        'dinas_name' => $detectedDinas,
                        'kategori_kode' => null,
                        'kategori_nama' => null
                    ];
                    $currentPersyaratan = null;
                    $currentSubPoin = null;
                    $state = 'ruang_lingkup';
                }
                continue;
            }

            if (!$currentKbli) {
                continue;
            }

            // ========== DETEKSI KATEGORI ==========
            if (preg_match('/^Kategori\s*:\s*([A-Z])\s*-\s*(.+)$/i', $line, $kategoriMatches)) {
                $currentKbli['kategori_kode'] = strtoupper(trim($kategoriMatches[1]));
                $currentKbli['kategori_nama'] = trim($kategoriMatches[2]);
                if ($state === 'kbli_name') {
                    $state = 'ruang_lingkup';
                }
                continue;
            }

            // ========== DETEKSI PERSYARATAN (PRIORITAS TINGGI) ==========
            // Pattern fleksibel: "1. Teks", "1) Teks", "1.Teks", "1)Teks", "1.  Teks" (multiple spaces)
            // Pastikan angka diikuti titik/kurung dan bukan bagian dari angka lain
            if (preg_match('/^(\d+)[\.\)]\s+(.+)$/', $line, $persMatches)) {
                // Pattern dengan spasi setelah titik/kurung
                $persyaratanNama = trim($persMatches[2]);
            } elseif (preg_match('/^(\d+)[\.\)]([^\d\s].+)$/', $line, $persMatches)) {
                // Pattern tanpa spasi setelah titik/kurung (contoh: "1.Teks")
                $persyaratanNama = trim($persMatches[2]);
            } else {
                $persMatches = null;
            }

            if ($persMatches && isset($persMatches[1]) && isset($persMatches[2])) {
                // Pastikan ini bukan bagian dari angka yang lebih besar (contoh: "123.456" bukan persyaratan)
                // Hanya deteksi jika angka diikuti titik/kurung dan kemudian teks (bukan angka lagi)
                $nomor = $persMatches[1];

                // Simpan persyaratan sebelumnya
                $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                $this->saveCurrentPersyaratan($currentPersyaratan, $currentKbli);

                Log::info('Persyaratan detected', [
                    'nomor' => $nomor,
                    'nama' => substr($persyaratanNama, 0, 100),
                    'kbli_kode' => $currentKbli['kode']
                ]);

                $currentPersyaratan = [
                    'nama' => $persyaratanNama,
                    'subpoin' => []
                ];
                $currentSubPoin = null;
                $state = 'persyaratan_name';
                continue;
            }

            // ========== PROSES BERDASARKAN STATE ==========
            if ($state === 'kbli_name') {
                // Mengumpulkan nama KBLI
                if (!preg_match('/^(?:Ruang\s+Lingkup|Persyaratan|Kategori|\d+[\.\)])/i', $line)) {
                    $currentKbli['nama'] .= ($currentKbli['nama'] ? ' ' : '') . $line;

                    // Cek kategori dalam nama
                    if (preg_match('/Kategori\s*:\s*([A-Z])\s*-\s*(.+)$/i', $currentKbli['nama'], $katMatches)) {
                        $currentKbli['kategori_kode'] = strtoupper(trim($katMatches[1]));
                        $currentKbli['kategori_nama'] = trim($katMatches[2]);
                        $currentKbli['nama'] = preg_replace('/\s*Kategori\s*:.*$/i', '', $currentKbli['nama']);
                        $currentKbli['nama'] = trim($currentKbli['nama']);
                        $state = 'ruang_lingkup';
                    }
                } else {
                    $state = 'ruang_lingkup';
                }
                continue;
            }

            if ($state === 'ruang_lingkup') {
                // Deteksi "Ruang Lingkup:"
                if (preg_match('/^Ruang\s+Lingkup[\s:]*[:]?\s*(.+)$/i', $line, $scopeMatches)) {
                    $scopeContent = trim($scopeMatches[1] ?? '');
                    $scopeContent = preg_replace('/\s*Persyaratan.*$/i', '', $scopeContent);
                    $currentKbli['ruang_lingkup'] = trim($scopeContent);
                } elseif (preg_match('/^Persyaratan/i', $line)) {
                    // Header Persyaratan
                    $state = 'subpoin';
                } elseif (!preg_match('/^(?:KBLI|Kode|\d{4,5})/i', $line)) {
                    // Tambahkan ke ruang lingkup
                    if (!empty($line)) {
                        $pos = stripos($line, 'Persyaratan');
                        if ($pos !== false) {
                            $line = trim(substr($line, 0, $pos));
                        }
                        if (!empty($line)) {
                            $currentKbli['ruang_lingkup'] .= ($currentKbli['ruang_lingkup'] ? ' ' : '') . $line;
                        }
                    }
                }
                continue;
            }

            if ($state === 'persyaratan_name' && $currentPersyaratan) {
                // Mengumpulkan nama persyaratan yang panjang (biasanya BOLD di PDF)
                // STRATEGI: Terus kumpulkan sampai menemukan indikator SANGAT JELAS
                // Karena kita tidak bisa deteksi bold secara langsung, gunakan heuristik

                // Prioritas 1: Cek persyaratan baru (angka dengan titik/kurung di awal)
                $persMatchesNew = null;
                if (
                    preg_match('/^(\d+)[\.\)]\s+(.+)$/', $line, $persMatchesNew) ||
                    preg_match('/^(\d+)[\.\)]([^\d\s].+)$/', $line, $persMatchesNew)
                ) {
                    // Ini persyaratan baru - simpan yang lama, lalu proses sebagai persyaratan baru
                    $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                    $this->saveCurrentPersyaratan($currentPersyaratan, $currentKbli);

                    $persyaratanNama = trim($persMatchesNew[2]);
                    Log::info('Persyaratan detected (during name collection)', [
                        'nomor' => $persMatchesNew[1],
                        'nama' => substr($persyaratanNama, 0, 100),
                        'kbli_kode' => $currentKbli['kode']
                    ]);

                    $currentPersyaratan = [
                        'nama' => $persyaratanNama,
                        'subpoin' => []
                    ];
                    $currentSubPoin = null;
                    $state = 'persyaratan_name';
                    continue;
                }

                // Prioritas 2: Cek format sub poin yang SANGAT JELAS (dengan spasi setelah format)
                // Format yang jelas menunjukkan bahwa nama persyaratan sudah selesai
                // PENTING: Jangan terlalu cepat menganggap baris sebagai sub poin
                $isClearSubPoinFormat = preg_match('/^([a-z]|[A-Z])[\.\)]\s+/i', $line) || // a. Teks (spasi setelah titik)
                    preg_match('/^[•\-\*—–]\s+/u', $line) || // bullet/dash dengan spasi
                    preg_match('/^\d+\)\s+/', $line); // 1) Teks (spasi setelah kurung)
                // TIDAK termasuk tanda kurung di awal seperti "(label)" karena bisa jadi bagian nama persyaratan

                // Prioritas 3: Cek header/format khusus
                $isSpecialFormat = preg_match('/^(?:KBLI|Kode|Persyaratan|Ruang\s+Lingkup|\d{4,5})/i', $line);

                // HEURISTIK untuk mendeteksi akhir nama persyaratan (yang biasanya bold):
                // Nama persyaratan bisa mengandung tanda kurung di tengah seperti:
                // "Dokumen kerja sama (treatment dan pembuangan) yang masih berlaku"
                // Jadi kita harus lebih hati-hati dalam mendeteksi akhir nama persyaratan

                $likelyEndOfPersyaratanName = false;
                if (!$isClearSubPoinFormat && !$isSpecialFormat) {
                    $currentName = $currentPersyaratan['nama'];

                    // Cek apakah baris saat ini adalah lanjutan nama persyaratan
                    // Karakteristik lanjutan nama persyaratan:
                    // 1. Dimulai dengan huruf kecil (lanjutan kalimat) - SANGAT UMUM
                    // 2. Dimulai dengan tanda kurung buka (lanjutan dengan keterangan dalam kurung)
                    // 3. Dimulai dengan kata yang melanjutkan kalimat (tidak dimulai dengan format sub poin)
                    $startsWithLowercase = preg_match('/^[a-z]/', $line);
                    $startsWithOpenParen = preg_match('/^\(/', $line);

                    // Hanya anggap sebagai sub poin jika benar-benar jelas format sub poin
                    // Jika baris dimulai dengan huruf kecil atau tanda kurung, 
                    // hampir pasti ini lanjutan nama persyaratan (bukan sub poin baru)
                    if ($startsWithLowercase || $startsWithOpenParen) {
                        // Ini kemungkinan besar lanjutan nama persyaratan
                        $currentPersyaratan['nama'] .= ' ' . $line;
                        Log::debug('Continuing persyaratan name (lowercase/paren start)', [
                            'current_name_length' => strlen($currentPersyaratan['nama']),
                            'added_line' => substr($line, 0, 50),
                            'kbli_kode' => $currentKbli['kode'] ?? 'N/A'
                        ]);
                        continue;
                    }

                    // Cek apakah baris dimulai dengan huruf kapital
                    // Jika iya, bisa jadi lanjutan nama persyaratan atau sub poin baru
                    // Tapi jika sebelumnya nama persyaratan belum diakhiri dengan tanda baca,
                    // kemungkinan besar ini masih lanjutan
                    $startsWithCapital = preg_match('/^[A-Z]/', $line);
                    if ($startsWithCapital) {
                        // Cek apakah nama persyaratan sudah diakhiri dengan tanda baca
                        $endsWithPunctuation = preg_match('/[\.;:]\s*$/', trim($currentName));

                        // Jika nama persyaratan belum diakhiri dengan tanda baca,
                        // kemungkinan besar ini masih lanjutan (bisa jadi awal kalimat baru dalam nama)
                        if (!$endsWithPunctuation) {
                            $currentPersyaratan['nama'] .= ' ' . $line;
                            Log::debug('Continuing persyaratan name (capital, no punctuation end)', [
                                'current_name_length' => strlen($currentPersyaratan['nama']),
                                'added_line' => substr($line, 0, 50)
                            ]);
                            continue;
                        }
                    }

                    // Cek karakteristik baris yang MENUNJUKKAN sub poin (bukan lanjutan)
                    // Hanya jika benar-benar jelas bahwa ini sub poin
                    $endsWithPunctuation = preg_match('/[\.;:]\s*$/', trim($currentName));

                    // Keyword yang jelas menunjukkan sub poin HARUS diikuti dengan spasi
                    // Contoh: "dengan meliputi" (bukan "denganmeliputi")
                    $lineStartsWithSubPoinKeyword = preg_match('/^(dengan|meliputi|berupa|termasuk|yaitu|seperti|antara lain|berikut|adalah)\s+/i', $line);

                    // Format sub poin yang jelas
                    $lineIsShortAndHasFormat = strlen($line) < 40 && (
                        preg_match('/^[a-z]\.\s/i', $line) || // huruf kecil dengan titik dan spasi
                        preg_match('/^[-•\*]\s/', $line) // dash/bullet dengan spasi
                    );

                    // Hanya anggap selesai jika:
                    // 1. Nama persyaratan sudah diakhiri dengan tanda baca, DAN
                    // 2. Baris berikutnya jelas-jelas sub poin (keyword dengan spasi atau format jelas)
                    if ($endsWithPunctuation && ($lineStartsWithSubPoinKeyword || $lineIsShortAndHasFormat)) {
                        $likelyEndOfPersyaratanName = true;
                        Log::info('Likely end of persyaratan name detected', [
                            'current_name' => substr($currentName, -50),
                            'next_line' => substr($line, 0, 50),
                            'kbli_kode' => $currentKbli['kode'] ?? 'N/A'
                        ]);
                    } else {
                        // Jika tidak jelas, lebih baik lanjutkan sebagai nama persyaratan
                        // (lebih baik terlalu banyak mengumpulkan daripada terpotong)
                        $currentPersyaratan['nama'] .= ' ' . $line;
                        Log::debug('Continuing persyaratan name (uncertain, default to continue)', [
                            'current_name_length' => strlen($currentPersyaratan['nama']),
                            'added_line' => substr($line, 0, 50)
                        ]);
                        continue;
                    }
                }

                if ($isClearSubPoinFormat || $isSpecialFormat || $likelyEndOfPersyaratanName) {
                    // Nama persyaratan selesai, lanjut ke sub poin
                    $state = 'subpoin';
                    // Jangan continue, proses baris ini sebagai sub poin di bawah
                } else {
                    // Lanjutan nama persyaratan - tambahkan ke nama
                    // Karena nama persyaratan biasanya bold dan bisa panjang,
                    // kita harus AGRESIF dalam mengumpulkan sampai menemukan indikator yang SANGAT JELAS
                    $currentPersyaratan['nama'] .= ' ' . $line;
                    Log::debug('Continuing persyaratan name collection', [
                        'current_name_length' => strlen($currentPersyaratan['nama']),
                        'added_line' => substr($line, 0, 50)
                    ]);
                    continue;
                }
            }

            // Hanya proses sub poin jika state sudah 'subpoin' (bukan 'persyaratan_name')
            // Ini memastikan nama persyaratan tidak terpotong oleh deteksi sub poin
            if ($state === 'subpoin' && $currentPersyaratan) {
                // Deteksi berbagai format SUB POIN
                // Format 1: Huruf dengan titik/kurung (a., b., A., B., a), b))
                if (preg_match('/^([a-z]|[A-Z])[\.\)]\s+(.+)$/i', $line, $subMatches)) {
                    $firstChar = $subMatches[1];
                    if (ctype_alpha($firstChar)) {
                        $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                        $currentSubPoin = [
                            'item' => trim($subMatches[2]),
                            'details' => [],
                            'is_formatted' => true,
                            'format_letter' => strtoupper($firstChar)
                        ];
                        continue;
                    }
                }

                // Format 2: Bullet atau dash (•, -, *, —)
                if (preg_match('/^([•\-\*—–])\s+(.+)$/u', $line, $bulletMatches)) {
                    $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                    $currentSubPoin = [
                        'item' => trim($bulletMatches[2]),
                        'details' => []
                    ];
                    continue;
                }

                // Format 3: Angka dengan kurung (1), 2), 3))
                if (preg_match('/^(\d+)\)\s+(.+)$/', $line, $subNumberMatches)) {
                    $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                    $currentSubPoin = [
                        'item' => trim($subNumberMatches[2]),
                        'details' => []
                    ];
                    continue;
                }

                // Format 4: Tanda kurung di awal (perpanjangan), (opsional)
                if (preg_match('/^\(([^)]+)\)\s+(.+)$/i', $line, $parenMatches)) {
                    $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                    $fullItem = trim($parenMatches[1]) . ') ' . trim($parenMatches[2]);
                    $currentSubPoin = [
                        'item' => $fullItem,
                        'details' => []
                    ];
                    continue;
                }

                // Jika ada sub poin aktif, tambahkan baris sebagai detail atau lanjutan
                if ($currentSubPoin && $currentPersyaratan) {
                    // Cek apakah ini sub poin baru (format jelas)
                    $isNewSubPoin = preg_match('/^(?:[a-z]|[A-Z])[\.\)]\s+/i', $line) || // a. Teks (dengan spasi)
                        preg_match('/^[•\-\*—–]\s+/u', $line) || // bullet/dash dengan spasi
                        preg_match('/^\d+\)\s+/', $line) || // 1) Teks (dengan spasi)
                        preg_match('/^\d+\.\s+/', $line); // 1. Teks (persyaratan baru)

                    // Cek header/format khusus
                    $isSpecialFormat = preg_match('/^(?:KBLI|Kode|Persyaratan|Ruang\s+Lingkup|\d{4,5})/i', $line);

                    if (!$isNewSubPoin && !$isSpecialFormat) {
                        $currentItem = $currentSubPoin['item'] ?? '';

                        // HEURISTIK: Deteksi apakah baris ini lanjutan item atau detail baru
                        // Karakteristik lanjutan item:
                        // 1. Baris dimulai dengan dash/minus TANPA spasi atau dengan spasi tunggal (bisa lanjutan kalimat)
                        // 2. Baris dimulai dengan huruf kecil (lanjutan kalimat)
                        // 3. Baris dimulai dengan tanda kurung tutup ")" (melengkapi tanda kurung buka sebelumnya)
                        // 4. Item saat ini belum diakhiri dengan tanda baca yang jelas (titik, titik dua)
                        // 5. Item saat ini memiliki tanda kurung buka yang belum ditutup

                        $startsWithDash = preg_match('/^[-–—]\s*/', $line);
                        $startsWithLowercase = preg_match('/^[a-z]/', $line);
                        $startsWithCloseParen = preg_match('/^\)/', $line);
                        $startsWithOpenParen = preg_match('/^\(/', $line);

                        // Hitung tanda kurung di item saat ini
                        $openParenCount = substr_count($currentItem, '(');
                        $closeParenCount = substr_count($currentItem, ')');
                        $hasUnclosedParen = $openParenCount > $closeParenCount;

                        // Cek apakah item sudah diakhiri dengan tanda baca
                        $itemEndsWithPunctuation = preg_match('/[\.;:]\s*$/', trim($currentItem));

                        // Jika item memiliki tanda kurung yang belum ditutup,
                        // baris berikutnya kemungkinan besar lanjutan
                        if ($hasUnclosedParen) {
                            // Lanjutan item (melengkapi tanda kurung)
                            $currentSubPoin['item'] .= ' ' . $line;
                            Log::debug('Continuing sub poin item (unclosed paren)', [
                                'current_item' => substr($currentItem, -50),
                                'added_line' => substr($line, 0, 50)
                            ]);
                            continue;
                        }

                        // Jika baris dimulai dengan dash/minus, bisa jadi lanjutan atau detail
                        if ($startsWithDash) {
                            // Jika item belum selesai (tidak diakhiri tanda baca) atau masih pendek,
                            // kemungkinan lanjutan item
                            if (!$itemEndsWithPunctuation || strlen($currentItem) < 80) {
                                // Hapus dash di awal dan tambahkan sebagai lanjutan
                                $lineWithoutDash = preg_replace('/^[-–—]\s*/', '', $line);
                                $currentSubPoin['item'] .= ' ' . $lineWithoutDash;
                                Log::debug('Continuing sub poin item (dash start, no punctuation)', [
                                    'current_item' => substr($currentItem, -50),
                                    'added_line' => substr($lineWithoutDash, 0, 50)
                                ]);
                                continue;
                            }
                        }

                        // Jika baris dimulai dengan huruf kecil dan item belum selesai,
                        // kemungkinan lanjutan item
                        if ($startsWithLowercase && !$itemEndsWithPunctuation) {
                            $currentSubPoin['item'] .= ' ' . $line;
                            Log::debug('Continuing sub poin item (lowercase, no punctuation)', [
                                'current_item' => substr($currentItem, -50),
                                'added_line' => substr($line, 0, 50)
                            ]);
                            continue;
                        }

                        // Jika baris dimulai dengan tanda kurung tutup, pasti lanjutan item
                        if ($startsWithCloseParen) {
                            $currentSubPoin['item'] .= ' ' . $line;
                            Log::debug('Continuing sub poin item (close paren)', [
                                'current_item' => substr($currentItem, -50),
                                'added_line' => substr($line, 0, 50)
                            ]);
                            continue;
                        }

                        // Jika baris dimulai dengan tanda kurung buka dan item belum selesai,
                        // kemungkinan lanjutan item (menambahkan keterangan dalam kurung)
                        if ($startsWithOpenParen && !$itemEndsWithPunctuation) {
                            $currentSubPoin['item'] .= ' ' . $line;
                            Log::debug('Continuing sub poin item (open paren, no punctuation)', [
                                'current_item' => substr($currentItem, -50),
                                'added_line' => substr($line, 0, 50)
                            ]);
                            continue;
                        }

                        // Jika item sudah diakhiri dengan tanda baca dan baris cukup panjang,
                        // atau jika item sudah cukup panjang (mungkin selesai),
                        // baris berikutnya mungkin detail
                        if ($itemEndsWithPunctuation || strlen($currentItem) > 100) {
                            // Detail sub poin (baris dengan indentasi atau baris pendek)
                            if (preg_match('/^\s{2,}/', $originalLine) || strlen($line) < 80) {
                                $currentSubPoin['details'][] = $line;
                                Log::debug('Adding sub poin detail', [
                                    'detail' => substr($line, 0, 50)
                                ]);
                                continue;
                            }
                        }

                        // Default: jika tidak jelas, lebih baik lanjutkan sebagai item
                        // (lebih baik terlalu panjang daripada terpecah)
                        $currentSubPoin['item'] .= ' ' . $line;
                        Log::debug('Continuing sub poin item (default, uncertain)', [
                            'current_item' => substr($currentItem, -50),
                            'added_line' => substr($line, 0, 50)
                        ]);
                        continue;
                    }
                }

                // Jika ada persyaratan tapi belum ada sub poin, dan baris ini tidak jelas formatnya
                // Bisa jadi sub poin tanpa format khusus
                if (
                    $currentPersyaratan && !$currentSubPoin &&
                    !preg_match('/^(?:KBLI|Kode|Persyaratan|Ruang\s+Lingkup|\d{4,5}|\d+\.\s)/i', $line) &&
                    strlen($line) > 10
                ) {

                    // Cek apakah ini benar-benar sub poin (bukan lanjutan nama persyaratan)
                    // Jika baris dimulai dengan karakter khusus atau cukup panjang, anggap sebagai sub poin
                    if (preg_match('/^[^\w]/', $line) || strlen($line) > 50) {
                        $currentSubPoin = [
                            'item' => $line,
                            'details' => []
                        ];
                        $state = 'subpoin';
                        continue;
                    }
                }
            }
        }

        // Simpan yang terakhir
        $this->saveCurrentKbli($currentKbli, $results, $currentPersyaratan, $currentSubPoin, $detectedDinas);

        // Log total
        $totalPersyaratan = 0;
        foreach ($results as $kbli) {
            $totalPersyaratan += count($kbli['persyaratan'] ?? []);
        }
        Log::info('Total detected after parsing', [
            'total_kbli' => count($results),
            'total_persyaratan' => $totalPersyaratan
        ]);

        // Bersihkan data invalid
        $results = array_filter($results, function ($kbli) {
            return !empty($kbli['kode']) && !empty($kbli['nama']);
        });

        return array_values($results);
    }

    private function saveCurrentSubPoin(&$currentSubPoin, &$currentPersyaratan)
    {
        if ($currentSubPoin && $currentPersyaratan) {
            unset($currentSubPoin['is_formatted']);
            unset($currentSubPoin['format_letter']);
            $currentPersyaratan['subpoin'][] = $currentSubPoin;
            $currentSubPoin = null;
        }
    }

    private function saveCurrentPersyaratan(&$currentPersyaratan, &$currentKbli)
    {
        if ($currentPersyaratan && $currentKbli) {
            $nama = trim($currentPersyaratan['nama'] ?? '');
            if (!empty($nama)) {
                Log::info('Saving persyaratan', [
                    'nama' => substr($nama, 0, 100),
                    'subpoin_count' => count($currentPersyaratan['subpoin'] ?? []),
                    'kbli_kode' => $currentKbli['kode'] ?? 'N/A'
                ]);
                $currentKbli['persyaratan'][] = $currentPersyaratan;
            }
            $currentPersyaratan = null;
        }
    }

    private function saveCurrentKbli(&$currentKbli, &$results, &$currentPersyaratan = null, &$currentSubPoin = null, $detectedDinas = null)
    {
        if ($currentKbli) {
            $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
            $this->saveCurrentPersyaratan($currentPersyaratan, $currentKbli);

            if (!empty($currentKbli['kode']) && !empty($currentKbli['nama'])) {
                $currentKbli['nama'] = trim($currentKbli['nama']);

                // Bersihkan kategori dari nama
                if (preg_match('/Kategori\s*:\s*([A-Z])\s*-\s*(.+)$/i', $currentKbli['nama'], $finalKategoriMatches)) {
                    if (empty($currentKbli['kategori_kode'])) {
                        $currentKbli['kategori_kode'] = strtoupper(trim($finalKategoriMatches[1]));
                        $currentKbli['kategori_nama'] = trim($finalKategoriMatches[2]);
                    }
                    $currentKbli['nama'] = preg_replace('/\s*Kategori\s*:.*$/i', '', $currentKbli['nama']);
                    $currentKbli['nama'] = trim($currentKbli['nama']);
                }

                if (empty($currentKbli['dinas_name']) && $detectedDinas) {
                    $currentKbli['dinas_name'] = $detectedDinas;
                }

                // Bersihkan ruang lingkup
                $ruangLingkup = trim($currentKbli['ruang_lingkup'] ?? '');
                if (!empty($ruangLingkup)) {
                    $ruangLingkup = preg_replace('/^Ruang\s+Lingkup[\s:]*[:]?\s*/i', '', $ruangLingkup);
                    $ruangLingkup = preg_replace('/\s*Persyaratan.*$/i', '', $ruangLingkup);
                    $ruangLingkup = preg_replace('/\s+/', ' ', trim($ruangLingkup));
                    $currentKbli['ruang_lingkup'] = $ruangLingkup;
                } else {
                    $currentKbli['ruang_lingkup'] = '';
                }

                $results[] = $currentKbli;
            }

            $currentKbli = null;
        }
    }

    /**
     * Import data KBLI ke database
     */
    private function importKbliData(array $data): Kbli
    {
        // Dinas
        $dinasName = $data['dinas_name'] ?? 'Dinas Kesehatan';
        $dinasName = trim($dinasName);
        if (empty($dinasName)) {
            $dinasName = 'Dinas Kesehatan';
        }

        Log::info('Using dinas', [
            'dinas_name' => $dinasName,
            'kbli_kode' => $data['kode'] ?? 'N/A'
        ]);

        $dinas = Dinas::firstOrCreate(
            ['nama' => $dinasName],
            ['nama' => $dinasName]
        );

        // Kategori
        $kategoriName = 'Umum';
        $kategoriKode = null;

        if (!empty($data['kategori_nama'])) {
            $kategoriName = trim($data['kategori_nama']);
            $kategoriKode = !empty($data['kategori_kode']) ? strtoupper(trim($data['kategori_kode'])) : null;

            Log::info('Using kategori', [
                'kategori_kode' => $kategoriKode,
                'kategori_nama' => $kategoriName,
                'kbli_kode' => $data['kode'] ?? 'N/A'
            ]);
        }

        $kategoriName = preg_replace('/\s+/', ' ', trim($kategoriName));
        $kategori = KategoriKbli::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($kategoriName)])->first();

        if (!$kategori) {
            $kategori = KategoriKbli::whereRaw('LOWER(TRIM(nama)) LIKE ?', ['%' . strtolower($kategoriName) . '%'])->first();
        }

        if (!$kategori) {
            $kategori = KategoriKbli::where('nama', $kategoriName)->first();
        }

        if (!$kategori) {
            $kategori = KategoriKbli::create(['nama' => $kategoriName]);
            Log::info('Created kategori', [
                'kategorikbli_id' => $kategori->kategorikbli_id,
                'nama' => $kategoriName
            ]);
        } else {
            Log::info('Using existing kategori', [
                'kategorikbli_id' => $kategori->kategorikbli_id,
                'nama' => $kategori->nama
            ]);
        }

        // KBLI
        $kbli = Kbli::where('kode', $data['kode'])->first();

        if ($kbli) {
            $kbli->update([
                'nama' => $data['nama'] ?: $kbli->nama,
                'ruang_lingkup' => $data['ruang_lingkup'] ?: $kbli->ruang_lingkup,
                'dinas_id' => $dinas->dinas_id,
                'kategorikbli_id' => $kategori->kategorikbli_id,
            ]);
        } else {
            $kbli = Kbli::create([
                'kode' => $data['kode'],
                'nama' => $data['nama'] ?: 'KBLI ' . $data['kode'],
                'ruang_lingkup' => $data['ruang_lingkup'] ?: '',
                'dinas_id' => $dinas->dinas_id,
                'kategorikbli_id' => $kategori->kategorikbli_id,
            ]);
        }

        // Import persyaratan
        foreach ($data['persyaratan'] ?? [] as $persIndex => $persyaratanData) {
            try {
                if (empty($persyaratanData['nama']) || trim($persyaratanData['nama']) === '') {
                    Log::warning('Persyaratan kosong, skip', [
                        'index' => $persIndex,
                        'kbli_id' => $kbli->kbli_id
                    ]);
                    continue;
                }

                $persyaratan = PersyaratanPerizinan::create([
                    'kbli_id' => $kbli->kbli_id,
                    'nama' => trim($persyaratanData['nama']),
                ]);

                Log::info('Persyaratan created', [
                    'persyaratan_id' => $persyaratan->persyaratan_id,
                    'nama' => substr($persyaratan->nama, 0, 50),
                    'subpoin_count' => count($persyaratanData['subpoin'] ?? [])
                ]);

                // Import sub poin
                foreach ($persyaratanData['subpoin'] ?? [] as $subIndex => $subPoinData) {
                    try {
                        if (empty($subPoinData['item']) || trim($subPoinData['item']) === '') {
                            Log::warning('Sub poin kosong, skip', [
                                'persyaratan_id' => $persyaratan->persyaratan_id,
                                'index' => $subIndex
                            ]);
                            continue;
                        }

                        $subPoin = SubPoin::create([
                            'persyaratan_id' => $persyaratan->persyaratan_id,
                            'item' => trim($subPoinData['item']),
                        ]);

                        // Import detail
                        foreach ($subPoinData['details'] ?? [] as $detailIndex => $detailText) {
                            try {
                                $trimmedDetail = trim($detailText);
                                if (!empty($trimmedDetail)) {
                                    SubPoinDetail::create([
                                        'subpoin_id' => $subPoin->subpoin_id,
                                        'text' => $trimmedDetail,
                                    ]);
                                }
                            } catch (\Exception $e) {
                                Log::error('Error creating detail', [
                                    'subpoin_id' => $subPoin->subpoin_id,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Error creating sub poin', [
                            'persyaratan_id' => $persyaratan->persyaratan_id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error creating persyaratan', [
                    'kbli_id' => $kbli->kbli_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $kbli;
    }
}
