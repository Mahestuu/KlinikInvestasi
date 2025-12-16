<?php

namespace App\Http\Controllers;

use App\Models\Kbli;
use App\Models\Pbumku;
use App\Models\Dinas;
use App\Models\PersyaratanPbumku;
use App\Models\SubPoinPbumku;
use App\Models\SubPoinDetailPbumku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;

class ImportPbumkuFromPdfController extends Controller
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
            $text = $pdf->getText();

            // Coba ambil informasi font dari pages jika tersedia
            $pages = $pdf->getPages();
            $textWithFontInfo = [];
            foreach ($pages as $page) {
                try {
                    $details = $page->get('Details');
                    $textWithFontInfo[] = $page->getText();
                } catch (\Exception $e) {
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
                    'nama' => substr($importedData[0]['nama'] ?? 'N/A', 0, 50),
                    'persyaratan_count' => count($importedData[0]['persyaratan'] ?? [])
                ] : []
            ]);

            // Import ke database dalam transaction
            DB::beginTransaction();
            Log::info('Transaction started for PBUMKU import');

            $importedCount = 0;
            $errors = [];
            $importedPbumkuIds = []; // Track ID PBUMKU yang berhasil di-import

            foreach ($importedData as $index => $data) {
                try {
                    Log::info("Importing PBUMKU", [
                        'index' => $index,
                        'nama' => substr($data['nama'] ?? 'N/A', 0, 50),
                        'persyaratan_count' => count($data['persyaratan'] ?? [])
                    ]);

                    $pbumku = $this->importPbumkuData($data);

                    if ($pbumku && $pbumku->pbumku_id) {
                        // Verifikasi PBUMKU benar-benar ada dalam transaction
                        $verified = Pbumku::find($pbumku->pbumku_id);
                        if ($verified) {
                            $importedCount++;
                            $importedPbumkuIds[] = $pbumku->pbumku_id;

                            // Hitung persyaratan yang benar-benar ada
                            $actualPersyaratanCount = $verified->persyaratanPbumku()->count();

                            Log::info("PBUMKU imported successfully", [
                                'pbumku_id' => $pbumku->pbumku_id,
                                'nama' => $pbumku->nama,
                                'persyaratan_count_in_db' => $actualPersyaratanCount,
                                'persyaratan_count_expected' => count($data['persyaratan'] ?? [])
                            ]);
                        } else {
                            $errors[] = "PBUMKU {$data['nama']} dibuat tapi tidak ditemukan di database setelah create";
                            Log::error("PBUMKU not found after creation", [
                                'pbumku_id' => $pbumku->pbumku_id,
                                'nama' => $data['nama'] ?? 'unknown'
                            ]);
                        }
                    } else {
                        $errors[] = "PBUMKU {$data['nama']} gagal dibuat (return null)";
                        Log::warning("PBUMKU import returned null", ['nama' => $data['nama'] ?? 'unknown']);
                    }
                } catch (\Exception $e) {
                    $nama = $data['nama'] ?? 'unknown';
                    $errorMsg = "Error importing PBUMKU {$nama}: " . $e->getMessage();
                    $errors[] = $errorMsg;
                    Log::error('Import Error', [
                        'nama' => $nama,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Jika tidak ada yang berhasil di-import, rollback HANYA jika ada error
            // Jika tidak ada error tapi tidak ada yang di-import, mungkin filter terlalu ketat
            if ($importedCount === 0) {
                // Cek apakah ada data yang di-parse
                if (count($importedData) === 0) {
                    DB::rollBack();
                    Log::error('No PBUMKU parsed from PDF', [
                        'total_parsed' => 0
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data PBUMKU yang terdeteksi dari PDF. Pastikan file PDF berisi data PBUMKU dengan format yang benar.',
                        'imported_count' => 0,
                        'total_parsed' => 0,
                        'errors' => ['Tidak ada data PBUMKU yang terdeteksi dari PDF']
                    ], 400);
                } else {
                    // Ada data yang di-parse tapi tidak ada yang berhasil di-import
                    // Mungkin karena error atau filter terlalu ketat
                    DB::rollBack();
                    Log::error('No PBUMKU imported despite parsed data', [
                        'total_parsed' => count($importedData),
                        'errors' => $errors,
                        'sample_data' => isset($importedData[0]) ? [
                            'nama' => substr($importedData[0]['nama'] ?? 'N/A', 0, 50),
                            'persyaratan_count' => count($importedData[0]['persyaratan'] ?? [])
                        ] : []
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data terdeteksi dari PDF tapi tidak ada yang berhasil diimport. ' . (count($errors) > 0 ? 'Error: ' . implode('; ', array_slice($errors, 0, 3)) : 'Tidak ada error yang dicatat. Mungkin filter terlalu ketat.'),
                        'imported_count' => 0,
                        'total_parsed' => count($importedData),
                        'errors' => $errors
                    ], 400);
                }
            }

            // Commit transaction
            DB::commit();
            Log::info('Transaction committed successfully', [
                'imported_count' => $importedCount,
                'imported_pbumku_ids' => $importedPbumkuIds
            ]);

            // Verifikasi data benar-benar tersimpan setelah commit (di luar transaction)
            $verifiedCount = 0;
            $verifiedPbumkuNames = [];
            $verifiedWithPersyaratan = 0;

            foreach ($importedPbumkuIds as $pbumkuId) {
                $verified = Pbumku::find($pbumkuId);
                if ($verified) {
                    $verifiedCount++;
                    $verifiedPbumkuNames[] = substr($verified->nama, 0, 50);

                    // Hitung persyaratan
                    $persyaratanCount = $verified->persyaratanPbumku()->count();
                    if ($persyaratanCount > 0) {
                        $verifiedWithPersyaratan++;
                    }

                    Log::info('PBUMKU verified after commit', [
                        'pbumku_id' => $pbumkuId,
                        'nama' => substr($verified->nama, 0, 50),
                        'persyaratan_count' => $persyaratanCount
                    ]);
                } else {
                    Log::error('PBUMKU not found after commit', [
                        'pbumku_id' => $pbumkuId
                    ]);
                }
            }

            Log::info('Import completed', [
                'imported_count' => $importedCount,
                'verified_count' => $verifiedCount,
                'verified_with_persyaratan' => $verifiedWithPersyaratan,
                'total_parsed' => count($importedData),
                'imported_pbumku_ids' => $importedPbumkuIds,
                'verified_names' => $verifiedPbumkuNames
            ]);

            return response()->json([
                'success' => $importedCount > 0,
                'message' => $importedCount > 0
                    ? "Berhasil mengimpor {$importedCount} PBUMKU"
                    : "Tidak ada data yang berhasil diimport",
                'imported_count' => $importedCount,
                'total_parsed' => count($importedData),
                'errors' => $errors,
                'verified_count' => $verifiedCount,
                'verified_with_persyaratan' => $verifiedWithPersyaratan
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
     * STRUKTUR PBUMKU:
     * 1. Nama PBUMKU (baris-baris sebelum "KBLI :")
     * 2. KBLI : kode1 kode2 kode3 ... (bisa multi-line)
     * 3. Sektor : nama sektor (Dinas)
     * 4. Persyaratan perizinan berusaha :
     * 5. Persyaratan (1. ... 2. ... dst)
     */
    private function parsePdfText(string $text): array
    {
        $results = [];
        $lines = explode("\n", $text);

        $currentPbumku = null;
        $currentPersyaratan = null;
        $currentSubPoin = null;
        $detectedSektor = null;

        // State flags untuk tracking
        $state = 'none'; // none, pbumku_name, kbli_codes, sektor, waiting_persyaratan, persyaratan_name, subpoin, skip_ruang_lingkup
        $emptyLineCount = 0; // Track baris kosong berturut-turut
        $collectingKbliCodes = false;
        $pbumkuNameBuffer = []; // Buffer untuk nama PBUMKU
        $consecutiveEmptyLines = 0; // Track baris kosong berturut-turut untuk deteksi akhir PBUMKU

        foreach ($lines as $lineIndex => $line) {
            $originalLine = $line;
            $line = trim($line);
            $isEmptyLine = empty($line);

            if ($isEmptyLine) {
                $emptyLineCount++;
                $consecutiveEmptyLines++;

                // Jika baris kosong saat mengumpulkan nama persyaratan,
                // mungkin ini pemisah, tapi tetap lanjutkan mengumpulkan
                // sampai menemukan indikator jelas (jangan terlalu cepat reset)
                if ($emptyLineCount > 3 && $state === 'persyaratan_name' && $currentPersyaratan) {
                    // Jika terlalu banyak baris kosong, kemungkinan nama sudah selesai
                    $state = 'subpoin';
                }

                // Jika banyak baris kosong berturut-turut (5+), kemungkinan PBUMKU sudah selesai
                // dan akan mulai PBUMKU baru
                if ($consecutiveEmptyLines >= 5 && $currentPbumku && ($state === 'subpoin' || $state === 'waiting_persyaratan')) {
                    // Reset state untuk siap menerima PBUMKU baru
                    $state = 'none';
                }

                continue;
            }

            $emptyLineCount = 0;
            $consecutiveEmptyLines = 0; // Reset counter baris kosong

            // ========== SKIP RUANG LINGKUP ==========
            // Abaikan baris "Ruang Lingkup :" dan isinya (jangan tambahkan ke nama PBUMKU)
            if (preg_match('/^Ruang\s+Lingkup\s*[:]/i', $line)) {
                // Skip baris ini dan baris berikutnya sampai menemukan "Persyaratan" atau persyaratan dimulai
                if ($currentPbumku) {
                    $state = 'skip_ruang_lingkup';
                    $collectingKbliCodes = false; // Stop collecting KBLI codes
                }
                continue;
            }

            if ($state === 'skip_ruang_lingkup') {
                // Skip sampai menemukan "Persyaratan" atau persyaratan dimulai (angka dengan titik)
                if (preg_match('/^Persyaratan/i', $line)) {
                    $state = 'waiting_persyaratan';
                    // Jangan continue, biarkan diproses sebagai header persyaratan
                } elseif (preg_match('/^\d+[\.\)]\s+/', $line)) {
                    // Persyaratan dimulai, keluar dari skip mode
                    $state = 'waiting_persyaratan';
                    // Jangan continue, biarkan diproses sebagai persyaratan
                } else {
                    // Masih dalam ruang lingkup, skip
                    continue;
                }
            }

            // ========== DETEKSI NAMA PBUMKU BARU (PRIORITAS TINGGI - SEBELUM KBLI) ==========
            // Deteksi nama PBUMKU baru: baris yang jelas seperti nama organisasi/koperasi
            // PRIORITAS: Deteksi ini SEBELUM memproses sebagai sub poin atau persyaratan
            // Nama PBUMKU biasanya muncul setelah persyaratan selesai, SEBELUM "KBLI :"

            // Karakteristik nama PBUMKU yang JELAS:
            // 1. HARUS mengandung kata kunci PBUMKU (Koperasi, Unit, Usaha, Primer, Sekunder, USPPS, KSPPS, dll)
            // 2. Panjang minimal 15 karakter
            // 3. Dimulai dengan huruf kapital
            // 4. TIDAK mengandung kata kunci persyaratan (Jangka, waktu, pemenuhan, Jaringan, Pelayanan, dll)
            // 5. Bukan keyword header (KBLI, Sektor, Persyaratan, dll)

            $isClearPbumkuName = (
                strlen($line) >= 15 &&
                preg_match('/^[A-Z]/', $line) &&
                // HARUS mengandung kata kunci PBUMKU (WAJIB)
                preg_match('/(?:Koperasi|Unit\s+Simpan|Unit\s+Usaha|Usaha\s+Kecil|Primer|Sekunder|Syariah|Simpan\s+Pinjam|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $line) &&
                // BUKAN keyword header
                !preg_match('/^(?:KBLI|Sektor|Persyaratan|Ruang\s+Lingkup|Dinas)\s*[:]/i', $line) &&
                !preg_match('/^\d+[\.\)]\s+/', $line) &&
                !preg_match('/^[a-z][\.\)]\s+/i', $line) &&
                !preg_match('/^\d+\s+/', $line) &&
                // BUKAN kata kunci persyaratan (PENTING: JANGAN MASUKKAN)
                !preg_match('/^(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)\s+/i', $line) &&
                !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $line) &&
                // TIDAK mengandung keyword di tengah
                !preg_match('/Ruang\s+Lingkup/i', $line) &&
                !preg_match('/Persyaratan\s+perizinan/i', $line)
            );

            // Jika ini jelas nama PBUMKU, proses sebagai nama PBUMKU baru
            if ($isClearPbumkuName) {
                // Cek konteks: apakah ini setelah persyaratan atau tidak ada PBUMKU aktif?
                $shouldStartNewPbumku = false;

                if (!$currentPbumku) {
                    // Tidak ada PBUMKU aktif, ini pasti nama PBUMKU baru
                    $shouldStartNewPbumku = true;
                } elseif ($currentPbumku) {
                    // Ada PBUMKU aktif - cek apakah ini nama PBUMKU baru
                    // Jika sedang dalam state persyaratan atau setelah banyak baris kosong,
                    // kemungkinan besar ini nama PBUMKU baru
                    if ($state === 'subpoin' || $state === 'waiting_persyaratan' || $consecutiveEmptyLines >= 2) {
                        // Pastikan baris ini benar-benar nama PBUMKU (bukan bagian dari persyaratan)
                        // Nama PBUMKU baru HARUS mengandung kata kunci PBUMKU dan TIDAK mengandung kata kunci persyaratan
                        if (
                            !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $line) &&
                            preg_match('/(?:Koperasi|Unit|Usaha|Primer|Sekunder|Syariah|USPPS|KSPPS|KSP)/i', $line)
                        ) {
                            $shouldStartNewPbumku = true;
                        }
                    }
                }

                if ($shouldStartNewPbumku) {
                    // Simpan PBUMKU sebelumnya jika ada (dengan menyimpan semua data yang sudah dikumpulkan)
                    if ($currentPbumku) {
                        // Simpan sub poin dan persyaratan terlebih dahulu
                        $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                        $this->saveCurrentPersyaratan($currentPersyaratan, $currentPbumku);
                        // Simpan PBUMKU
                        $this->saveCurrentPbumku($currentPbumku, $results, $currentPersyaratan, $currentSubPoin, $detectedSektor);
                    }

                    // Mulai PBUMKU baru dengan nama ini
                    $currentPbumku = [
                        'nama' => $line,
                        'persyaratan' => [],
                        'dinas_name' => null,
                        'kbli_codes' => []
                    ];
                    $currentPersyaratan = null;
                    $currentSubPoin = null;
                    $detectedSektor = null;
                    $pbumkuNameBuffer = [$line]; // Simpan nama ke buffer
                    $state = 'none'; // Reset state, tunggu KBLI
                    $consecutiveEmptyLines = 0; // Reset counter

                    Log::info('New PBUMKU name detected (clear match)', [
                        'nama' => substr($line, 0, 100),
                        'line_index' => $lineIndex,
                        'state_before' => $state,
                        'previous_pbumku_saved' => true
                    ]);
                    continue;
                }
            }

            // ========== DETEKSI KBLI HEADER (PRIORITAS TERTINGGI) ==========
            // Setiap kali menemukan "KBLI :", berarti PBUMKU baru dimulai (atau lanjutan)
            if (preg_match('/^KBLI\s*[:]/i', $line)) {
                // Jika sudah ada PBUMKU aktif, simpan sebelumnya TERLEBIH DAHULU
                if ($currentPbumku) {
                    $this->saveCurrentPbumku($currentPbumku, $results, $currentPersyaratan, $currentSubPoin, $detectedSektor);
                }

                // Ambil nama PBUMKU - PRIORITAS:
                // 1. Dari PBUMKU aktif (jika ada)
                // 2. Dari buffer
                // 3. Dari baris-baris sebelumnya (lookback)
                $pbumkuName = '';

                // 1. Jika PBUMKU sudah aktif (dari deteksi sebelumnya), gunakan nama yang sudah ada
                if ($currentPbumku && !empty($currentPbumku['nama'])) {
                    $pbumkuName = $currentPbumku['nama'];
                    Log::info('Using existing PBUMKU name from current PBUMKU', [
                        'nama' => substr($pbumkuName, 0, 100)
                    ]);
                }

                // 2. Jika belum ada nama, cari dari buffer
                if (empty($pbumkuName) && !empty($pbumkuNameBuffer)) {
                    // Iterasi buffer dari belakang (baris terakhir dulu)
                    $bufferReversed = array_reverse($pbumkuNameBuffer);
                    foreach ($bufferReversed as $bufferLine) {
                        $trimmedBufferLine = trim($bufferLine);

                        // Filter: hanya baris yang BENAR-BENAR seperti nama PBUMKU
                        $isValidPbumkuName = (
                            !empty($trimmedBufferLine) &&
                            strlen($trimmedBufferLine) >= 15 &&
                            preg_match('/^[A-Z]/', $trimmedBufferLine) &&
                            !preg_match('/^(?:KBLI|Sektor|Persyaratan|Ruang\s+Lingkup|Dinas)\s*[:]/i', $trimmedBufferLine) &&
                            !preg_match('/^\d+[\.\)]\s+/', $trimmedBufferLine) &&
                            !preg_match('/^\d+\s+/', $trimmedBufferLine) &&
                            !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $trimmedBufferLine) &&
                            preg_match('/(?:Koperasi|Unit\s+Simpan|Unit\s+Usaha|Usaha\s+Kecil|Primer|Sekunder|Syariah|Simpan\s+Pinjam|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $trimmedBufferLine) &&
                            !preg_match('/Ruang\s+Lingkup/i', $trimmedBufferLine) &&
                            !preg_match('/Persyaratan\s+perizinan/i', $trimmedBufferLine)
                        );

                        if ($isValidPbumkuName) {
                            $pbumkuName = $trimmedBufferLine;
                            // Cek baris dalam kurung
                            $bufferIndex = array_search($bufferLine, $pbumkuNameBuffer);
                            if ($bufferIndex !== false && $bufferIndex > 0) {
                                $prevLine = trim($pbumkuNameBuffer[$bufferIndex - 1] ?? '');
                                if (preg_match('/^\([^)]+\)$/', $prevLine)) {
                                    $pbumkuName = $prevLine . ' ' . $pbumkuName;
                                }
                            }
                            break;
                        }
                    }
                }

                // 3. Jika masih belum ada nama, cari di baris-baris sebelumnya (LOOKBACK)
                if (empty($pbumkuName) && $lineIndex > 0) {
                    // Cari lebih jauh (maksimal 20 baris sebelumnya) untuk menemukan nama PBUMKU
                    $maxLookback = min(20, $lineIndex);

                    for ($i = 1; $i <= $maxLookback; $i++) {
                        $prevLineIndex = $lineIndex - $i;
                        if ($prevLineIndex < 0) break;

                        $prevLine = trim($lines[$prevLineIndex] ?? '');
                        if (empty($prevLine)) continue;

                        // Skip baris yang jelas bukan nama PBUMKU
                        if (
                            preg_match('/^(?:KBLI|Sektor|Persyaratan|Ruang\s+Lingkup|Dinas)\s*[:]/i', $prevLine) ||
                            preg_match('/^\d+[\.\)]\s+/', $prevLine) ||
                            preg_match('/^\d+\s+/', $prevLine) ||
                            preg_match('/Ruang\s+Lingkup/i', $prevLine) ||
                            preg_match('/Persyaratan\s+perizinan/i', $prevLine)
                        ) {
                            continue;
                        }

                        // Cek apakah baris ini seperti nama PBUMKU
                        $isLikePbumkuName = (
                            strlen($prevLine) >= 15 &&
                            preg_match('/^[A-Z]/', $prevLine) &&
                            !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $prevLine) &&
                            preg_match('/(?:Koperasi|Unit\s+Simpan|Unit\s+Usaha|Usaha\s+Kecil|Primer|Sekunder|Syariah|Simpan\s+Pinjam|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $prevLine) &&
                            !preg_match('/^\d+\s+Surabaya/i', $prevLine) &&
                            !preg_match('/^Hotel|^Restoran|^Apartemen/i', $prevLine)
                        );

                        if ($isLikePbumkuName) {
                            // Gabungkan dengan baris sebelumnya jika baris dalam kurung
                            if ($prevLineIndex > 0) {
                                $prevPrevLine = trim($lines[$prevLineIndex - 1] ?? '');
                                if (preg_match('/^\([^)]+\)$/', $prevPrevLine)) {
                                    $pbumkuName = $prevPrevLine . ' ' . $prevLine;
                                } else {
                                    $pbumkuName = $prevLine;
                                }
                            } else {
                                $pbumkuName = $prevLine;
                            }

                            Log::info('PBUMKU name found from previous lines (lookback)', [
                                'nama' => substr($pbumkuName, 0, 100),
                                'line_index' => $lineIndex,
                                'lookback_lines' => $i,
                                'found_at_line' => $prevLineIndex
                            ]);
                            break;
                        }
                    }
                }

                // Clear buffer setelah digunakan
                $pbumkuNameBuffer = [];

                // Filter nama PBUMKU: minimal 10 karakter dan harus mengandung kata kunci PBUMKU
                // DAN tidak boleh mengandung kata kunci invalid
                $isValidName = (
                    !empty($pbumkuName) &&
                    strlen($pbumkuName) >= 10 &&
                    preg_match('/(?:Koperasi|Unit|Usaha|Primer|Sekunder|Syariah|Simpan|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $pbumkuName) &&
                    // TIDAK boleh mengandung kata kunci invalid
                    !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Ruang\s+Lingkup|55193|55120|Surabaya)/i', $pbumkuName) &&
                    !preg_match('/^Ruang\s+Lingkup/i', $pbumkuName) &&
                    !preg_match('/^Persyaratan\s+perizinan/i', $pbumkuName) &&
                    !preg_match('/^\d+\s+Surabaya/i', $pbumkuName) &&
                    !preg_match('/^Hotel|^Restoran|^Apartemen/i', $pbumkuName)
                );

                if ($isValidName) {
                    // Mulai PBUMKU baru
                    $currentPbumku = [
                        'nama' => $pbumkuName,
                        'persyaratan' => [],
                        'dinas_name' => null,
                        'kbli_codes' => []
                    ];
                    $currentPersyaratan = null;
                    $currentSubPoin = null;
                    $detectedSektor = null;
                    $collectingKbliCodes = true;
                    $state = 'kbli_codes';

                    Log::info('PBUMKU detected (KBLI header)', [
                        'nama' => substr($pbumkuName, 0, 100),
                        'line_index' => $lineIndex
                    ]);
                } else {
                    // Jika nama tidak valid atau tidak ditemukan, log warning
                    if (!empty($pbumkuName)) {
                        // Nama ada tapi tidak valid
                        Log::warning('Invalid PBUMKU name, skipping PBUMKU creation', [
                            'pbumku_name' => substr($pbumkuName, 0, 100),
                            'line_index' => $lineIndex,
                            'reasons' => [
                                'too_short' => strlen($pbumkuName) < 10,
                                'no_pbumku_keyword' => !preg_match('/(?:Koperasi|Unit|Usaha|Primer|Sekunder|Syariah|Simpan|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $pbumkuName),
                                'has_invalid_keyword' => preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Ruang\s+Lingkup|55193|55120|Surabaya)/i', $pbumkuName)
                            ]
                        ]);
                    } else {
                        // Nama tidak ditemukan sama sekali - coba cari lebih jauh atau gunakan placeholder
                        Log::warning('PBUMKU name not found before KBLI header, trying extended search', [
                            'line_index' => $lineIndex,
                            'buffer_was_empty' => empty($pbumkuNameBuffer),
                            'lookback_attempted' => true
                        ]);

                        // Coba cari lebih jauh (maksimal 50 baris sebelumnya) dengan filter lebih longgar
                        if ($lineIndex > 0) {
                            $extendedLookback = min(50, $lineIndex);
                            for ($i = 1; $i <= $extendedLookback; $i++) {
                                $prevLineIndex = $lineIndex - $i;
                                if ($prevLineIndex < 0) break;

                                $prevLine = trim($lines[$prevLineIndex] ?? '');
                                if (empty($prevLine)) continue;

                                // Filter lebih longgar: hanya skip yang jelas bukan nama
                                if (
                                    preg_match('/^(?:KBLI|Sektor|Persyaratan|Ruang\s+Lingkup|Dinas)\s*[:]/i', $prevLine) ||
                                    preg_match('/^\d+[\.\)]\s+/', $prevLine) ||
                                    preg_match('/^\d+\s+Surabaya/i', $prevLine)
                                ) {
                                    continue;
                                }

                                // Jika baris panjang dan dimulai kapital, mungkin nama PBUMKU
                                if (
                                    strlen($prevLine) >= 20 &&
                                    preg_match('/^[A-Z]/', $prevLine) &&
                                    !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan)/i', $prevLine)
                                ) {
                                    $pbumkuName = $prevLine;
                                    Log::info('PBUMKU name found with extended search', [
                                        'nama' => substr($pbumkuName, 0, 100),
                                        'line_index' => $lineIndex,
                                        'lookback_lines' => $i
                                    ]);
                                    break;
                                }
                            }
                        }

                        // Jika masih tidak ditemukan, gunakan placeholder
                        if (empty($pbumkuName)) {
                            $pbumkuName = 'PBUMKU ' . ($lineIndex + 1);
                            Log::warning('Using placeholder name for PBUMKU', [
                                'placeholder' => $pbumkuName,
                                'line_index' => $lineIndex
                            ]);
                        }

                        // Coba buat PBUMKU dengan nama yang ditemukan (meskipun mungkin tidak sempurna)
                        if (!empty($pbumkuName) && strlen($pbumkuName) >= 10) {
                            $currentPbumku = [
                                'nama' => $pbumkuName,
                                'persyaratan' => [],
                                'dinas_name' => null,
                                'kbli_codes' => []
                            ];
                            $currentPersyaratan = null;
                            $currentSubPoin = null;
                            $detectedSektor = null;
                            $collectingKbliCodes = true;
                            $state = 'kbli_codes';

                            Log::info('PBUMKU created with extended search/placeholder', [
                                'nama' => substr($pbumkuName, 0, 100),
                                'line_index' => $lineIndex
                            ]);
                        }
                    }
                }

                // Ekstrak kode KBLI dari baris ini (jika ada)
                if ($currentPbumku && preg_match('/^KBLI\s*[:]?\s*(.+)$/i', $line, $kbliMatches)) {
                    $kbliLine = trim($kbliMatches[1]);
                    if (!empty($kbliLine)) {
                        $this->extractKbliCodesFromLine($kbliLine, $currentPbumku['kbli_codes']);
                    }
                }
                continue;
            }

            // Jika belum ada PBUMKU, kumpulkan baris ke buffer (mungkin nama PBUMKU)
            // Tapi hanya jika baris ini memiliki karakteristik nama PBUMKU yang JELAS
            if (!$currentPbumku && $state !== 'skip_ruang_lingkup') {
                // Filter SANGAT KETAT: hanya baris yang BENAR-BENAR seperti nama PBUMKU
                $isClearPbumkuName = (
                    strlen($line) >= 15 && // Minimal 15 karakter
                    preg_match('/^[A-Z]/', $line) && // Dimulai kapital
                    // Bukan keyword
                    !preg_match('/^(?:KBLI|Sektor|Persyaratan|Ruang\s+Lingkup|Dinas)\s*[:]/i', $line) &&
                    !preg_match('/^\d+[\.\)]\s+/', $line) &&
                    !preg_match('/^[a-z][\.\)]\s+/i', $line) &&
                    !preg_match('/^\d+\s+/', $line) &&
                    // BUKAN kata kunci persyaratan (PENTING)
                    !preg_match('/^(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)\s+/i', $line) &&
                    !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $line) &&
                    // Tidak mengandung keyword
                    !preg_match('/Ruang\s+Lingkup/i', $line) &&
                    !preg_match('/Persyaratan\s+perizinan/i', $line) &&
                    // HARUS mengandung kata kunci PBUMKU (WAJIB)
                    preg_match('/(?:Koperasi|Unit\s+Simpan|Unit\s+Usaha|Usaha\s+Kecil|Primer|Sekunder|Syariah|Simpan\s+Pinjam|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $line)
                );

                if ($isClearPbumkuName) {
                    $pbumkuNameBuffer[] = $line;
                    // Batasi buffer maksimal 2 baris (nama PBUMKU biasanya 1-2 baris, termasuk baris dalam kurung)
                    if (count($pbumkuNameBuffer) > 2) {
                        // Hapus baris tertua, tapi pastikan kita menyimpan baris yang paling relevan
                        // Ambil 2 baris terakhir (yang paling mungkin nama PBUMKU)
                        $pbumkuNameBuffer = array_slice($pbumkuNameBuffer, -2);
                    }
                }
                continue;
            }

            // ========== DETEKSI KODE KBLI (MULTI-LINE) ==========
            if ($collectingKbliCodes && $state === 'kbli_codes') {
                if (preg_match('/^Sektor\s*[:]/i', $line)) {
                    // Selesai mengumpulkan kode KBLI
                    $collectingKbliCodes = false;
                    $state = 'sektor';
                    // Jangan continue, biarkan diproses sebagai Sektor
                } elseif (preg_match('/^Ruang\s+Lingkup\s*[:]/i', $line)) {
                    // Skip ruang lingkup, tetap dalam state kbli_codes (bisa ada kode KBLI setelahnya, tapi biasanya tidak)
                    $state = 'skip_ruang_lingkup';
                    $collectingKbliCodes = false; // Stop collecting setelah ruang lingkup
                    continue;
                } elseif (preg_match('/^Persyaratan/i', $line)) {
                    // Persyaratan dimulai, selesai mengumpulkan KBLI
                    $collectingKbliCodes = false;
                    $state = 'waiting_persyaratan';
                    // Jangan continue, biarkan diproses sebagai header persyaratan
                } elseif (preg_match('/^\d+[\.\)]\s+/', $line)) {
                    // Persyaratan dimulai (angka dengan titik/kurung)
                    $collectingKbliCodes = false;
                    $state = 'waiting_persyaratan';
                    // Jangan continue, biarkan diproses sebagai persyaratan
                } else {
                    // Masih dalam baris kode KBLI (bisa baris kosong atau baris dengan kode)
                    // Baris kosong setelah "KBLI :" masih valid, lanjutkan mencari kode
                    if (!empty($line)) {
                        $this->extractKbliCodesFromLine($line, $currentPbumku['kbli_codes']);
                    }
                    // Jika baris kosong, tetap lanjutkan (kode KBLI bisa di baris berikutnya)
                    continue;
                }
            }

            // ========== DETEKSI SEKTOR (DINAS) ==========
            if (preg_match('/^Sektor\s*[:]?\s*(.+)$/i', $line, $sektorMatches)) {
                $sektorName = trim($sektorMatches[1]);
                $sektorName = rtrim($sektorName, '.,;');
                // Bersihkan "Dinas" di awal jika ada (karena sektor sudah berarti Dinas)
                $sektorName = preg_replace('/^Dinas\s+/i', '', $sektorName);
                $sektorName = trim($sektorName);

                if ($currentPbumku) {
                    $currentPbumku['dinas_name'] = $sektorName;
                    $detectedSektor = $sektorName;
                    $collectingKbliCodes = false;
                    $state = 'sektor';

                    Log::info('Sektor detected', [
                        'sektor' => $sektorName,
                        'pbumku_nama' => substr($currentPbumku['nama'] ?? 'N/A', 0, 50)
                    ]);
                }
                continue;
            }

            // ========== DETEKSI HEADER PERSYARATAN ==========
            if (preg_match('/^Persyaratan\s+(?:perizinan\s+berusaha)?\s*[:]?\s*$/i', $line)) {
                if ($currentPbumku) {
                    $state = 'waiting_persyaratan';
                    $collectingKbliCodes = false;

                    Log::info('Persyaratan header detected', [
                        'pbumku_nama' => substr($currentPbumku['nama'] ?? 'N/A', 0, 50),
                        'line_index' => $lineIndex
                    ]);
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

            $canDetectPersyaratan = (
                $state === 'waiting_persyaratan' ||
                $state === 'persyaratan_name' ||
                $state === 'subpoin' ||
                ($state === 'sektor' && $currentPbumku) ||
                ($currentPbumku && !$collectingKbliCodes && $state !== 'kbli_codes' && $state !== 'pbumku_name')
            );

            if ($persMatches && isset($persMatches[1]) && isset($persMatches[2]) && $canDetectPersyaratan) {
                // Pastikan ini bukan bagian dari angka yang lebih besar (contoh: "123.456" bukan persyaratan)
                // Hanya deteksi jika angka diikuti titik/kurung dan kemudian teks (bukan angka lagi)
                $nomor = $persMatches[1];

                // Simpan persyaratan sebelumnya
                $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
                $this->saveCurrentPersyaratan($currentPersyaratan, $currentPbumku);

                Log::info('Persyaratan detected', [
                    'nomor' => $nomor,
                    'nama' => substr($persyaratanNama, 0, 100),
                    'pbumku_nama' => $currentPbumku['nama'] ?? 'N/A'
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
                    $this->saveCurrentPersyaratan($currentPersyaratan, $currentPbumku);

                    $persyaratanNama = trim($persMatchesNew[2]);
                    Log::info('Persyaratan detected (during name collection)', [
                        'nomor' => $persMatchesNew[1],
                        'nama' => substr($persyaratanNama, 0, 100),
                        'pbumku_nama' => $currentPbumku['nama'] ?? 'N/A'
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
                            'pbumku_nama' => $currentPbumku['nama'] ?? 'N/A'
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
                            'pbumku_nama' => $currentPbumku['nama'] ?? 'N/A'
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

                        // Cek apakah baris ini mungkin nama PBUMKU baru (bukan lanjutan item)
                        // Nama PBUMKU baru biasanya:
                        // - Panjang (20+ karakter)
                        // - Dimulai dengan huruf kapital
                        // - Mengandung kata kunci PBUMKU (Koperasi, Unit, Usaha, dll)
                        // - Tidak mengandung kata kunci persyaratan (Jangka, waktu, pemenuhan, dll)
                        $mightBeNewPbumkuName = (
                            strlen($line) >= 20 &&
                            preg_match('/^[A-Z]/', $line) &&
                            !preg_match('/^(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $line) &&
                            !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang)/i', $line) &&
                            preg_match('/(?:Koperasi|Unit\s+Simpan|Unit\s+Usaha|Usaha\s+Kecil|Primer|Sekunder|Syariah|Simpan\s+Pinjam|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $line) &&
                            // Tidak mengandung keyword
                            !preg_match('/Ruang\s+Lingkup/i', $line) &&
                            !preg_match('/Persyaratan\s+perizinan/i', $line)
                        );

                        if ($mightBeNewPbumkuName) {
                            // Ini jelas nama PBUMKU baru, simpan PBUMKU sebelumnya dan mulai baru
                            // Simpan sub poin saat ini terlebih dahulu
                            $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);

                            // Simpan PBUMKU sebelumnya
                            if ($currentPbumku) {
                                $this->saveCurrentPbumku($currentPbumku, $results, $currentPersyaratan, $currentSubPoin, $detectedSektor);
                            }

                            // Mulai PBUMKU baru dengan nama ini
                            $currentPbumku = [
                                'nama' => $line,
                                'persyaratan' => [],
                                'dinas_name' => null,
                                'kbli_codes' => []
                            ];
                            $currentPersyaratan = null;
                            $currentSubPoin = null;
                            $detectedSektor = null;
                            $pbumkuNameBuffer = [$line];
                            $state = 'none'; // Reset state, tunggu KBLI
                            $consecutiveEmptyLines = 0;

                            Log::info('New PBUMKU name detected during sub poin processing', [
                                'nama' => substr($line, 0, 100),
                                'line_index' => $lineIndex,
                                'previous_pbumku_saved' => true
                            ]);
                            continue;
                        } else {
                            // Jika item sudah diakhiri dengan tanda baca dan baris cukup panjang,
                            // atau jika item sudah cukup panjang (mungkin selesai),
                            // baris berikutnya mungkin detail
                            if ($itemEndsWithPunctuation || strlen($currentItem) > 100) {
                                // Detail sub poin (baris dengan indentasi atau baris pendek)
                                if (preg_match('/^\s{2,}/', $originalLine) || strlen($line) < 80) {
                                    $currentSubPoin['details'][] = ['text' => $line];
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
        $this->saveCurrentPbumku($currentPbumku, $results, $currentPersyaratan, $currentSubPoin, $detectedSektor);

        // Log total
        $totalPersyaratan = 0;
        foreach ($results as $pbumku) {
            $totalPersyaratan += count($pbumku['persyaratan'] ?? []);
        }
        Log::info('Total detected after parsing', [
            'total_pbumku' => count($results),
            'total_persyaratan' => $totalPersyaratan
        ]);

        // Bersihkan data invalid - Filter SEDIKIT lebih longgar untuk memastikan data masuk
        $filteredResults = [];
        foreach ($results as $pbumku) {
            $nama = trim($pbumku['nama'] ?? '');

            // Nama tidak boleh kosong
            if (empty($nama)) {
                continue;
            }

            // Nama harus minimal 5 karakter (dikurangi dari 10)
            if (strlen($nama) < 5) {
                Log::warning('PBUMKU nama terlalu pendek, skipped', [
                    'nama' => substr($nama, 0, 50)
                ]);
                continue;
            }

            // Skip yang jelas bukan PBUMKU (kata kunci invalid di awal)
            if (
                preg_match('/^Ruang\s+Lingkup/i', $nama) ||
                preg_match('/^Persyaratan\s+perizinan/i', $nama) ||
                preg_match('/^\d+\s+Surabaya/i', $nama) ||
                preg_match('/^Jangka\s+waktu/i', $nama) ||
                preg_match('/^55193|^55120/i', $nama)
            ) {
                Log::warning('PBUMKU nama jelas invalid (starts with invalid keyword), skipped', [
                    'nama' => substr($nama, 0, 50)
                ]);
                continue;
            }

            // Jika nama mengandung kata kunci PBUMKU, PASTI VALID
            if (preg_match('/(?:Koperasi|Unit\s+Simpan|Unit\s+Usaha|Usaha\s+Kecil|Primer|Sekunder|Syariah|Simpan\s+Pinjam|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $nama)) {
                $filteredResults[] = $pbumku;
                continue;
            }

            // Jika nama panjang (>=20) dan dimulai kapital, mungkin valid (lebih longgar)
            if (strlen($nama) >= 20 && preg_match('/^[A-Z]/', $nama)) {
                // Skip jika jelas bukan nama (mengandung kata kunci invalid)
                if (!preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan|Ruang\s+Lingkup|55193|55120)/i', $nama)) {
                    $filteredResults[] = $pbumku;
                    Log::info('PBUMKU accepted (long name, starts with capital)', [
                        'nama' => substr($nama, 0, 50)
                    ]);
                    continue;
                }
            }

            Log::warning('PBUMKU filtered out', [
                'nama' => substr($nama, 0, 50),
                'length' => strlen($nama),
                'starts_with_capital' => preg_match('/^[A-Z]/', $nama)
            ]);
        }

        Log::info('PBUMKU filtered results', [
            'before_filter' => count($results),
            'after_filter' => count($filteredResults),
            'removed_count' => count($results) - count($filteredResults)
        ]);

        // Jika setelah filter tidak ada data, return semua yang ada (jangan hapus semua)
        if (empty($filteredResults) && !empty($results)) {
            Log::warning('All PBUMKU filtered out, returning original results', [
                'original_count' => count($results)
            ]);
            return array_values($results);
        }

        return $filteredResults;
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

    private function saveCurrentPersyaratan(&$currentPersyaratan, &$currentPbumku)
    {
        if ($currentPersyaratan && $currentPbumku) {
            $nama = trim($currentPersyaratan['nama'] ?? '');
            if (!empty($nama)) {
                Log::info('Saving persyaratan', [
                    'nama' => substr($nama, 0, 100),
                    'subpoin_count' => count($currentPersyaratan['subpoin'] ?? [])
                ]);
                $currentPbumku['persyaratan'][] = $currentPersyaratan;
            }
            $currentPersyaratan = null;
        }
    }

    private function saveCurrentPbumku(&$currentPbumku, &$results, &$currentPersyaratan = null, &$currentSubPoin = null, $detectedSektor = null)
    {
        if ($currentPbumku) {
            $this->saveCurrentSubPoin($currentSubPoin, $currentPersyaratan);
            $this->saveCurrentPersyaratan($currentPersyaratan, $currentPbumku);

            $nama = trim($currentPbumku['nama'] ?? '');
            if (!empty($nama)) {
                // Bersihkan nama dari bagian yang tidak perlu
                // Hapus "Ruang Lingkup :" dan isinya jika masih ada
                $nama = preg_replace('/\s*Ruang\s+Lingkup\s*:.*$/i', '', $nama);
                // Hapus "Persyaratan perizinan berusaha :" jika masih ada
                $nama = preg_replace('/\s*Persyaratan\s+(?:perizinan\s+berusaha)?\s*:.*$/i', '', $nama);
                // Hapus "KBLI :" dan kode KBLI jika masih ada di nama
                $nama = preg_replace('/\s*KBLI\s*:.*$/i', '', $nama);
                // Hapus "Sektor :" jika masih ada di nama
                $nama = preg_replace('/\s*Sektor\s*:.*$/i', '', $nama);
                // Hapus angka di awal (seperti "55193 Surabaya")
                $nama = preg_replace('/^\d+\s+/', '', $nama);
                // Hapus kata kunci persyaratan jika masih ada (seperti "Jangka waktu", "Jaringan Pelayanan", dll)
                $nama = preg_replace('/\s*(?:Jangka|waktu|pemenuhan|persyaratan|Jaringan|Pelayanan|Kantor|Cabang).*$/i', '', $nama);
                // Hapus kata "Dan" di awal jika ada (seperti "Dan USPPS Koperasi Primer")
                $nama = preg_replace('/^Dan\s+/i', '', $nama);
                // Bersihkan multiple spaces
                $nama = preg_replace('/\s+/', ' ', trim($nama));

                $currentPbumku['nama'] = $nama;

                if (empty($currentPbumku['dinas_name']) && $detectedSektor) {
                    $currentPbumku['dinas_name'] = $detectedSektor;
                }

                // Hanya simpan jika nama masih valid setelah dibersihkan
                // Kriteria lebih longgar: minimal 5 karakter (bukan 10)
                // ATAU mengandung kata kunci PBUMKU
                if (
                    strlen($nama) >= 5 &&
                    (
                        preg_match('/(?:Koperasi|Unit|Usaha|Primer|Sekunder|Syariah|Simpan|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $nama) ||
                        (strlen($nama) >= 15 && preg_match('/^[A-Z]/', $nama) && !preg_match('/(?:Jangka|waktu|pemenuhan|persyaratan)/i', $nama))
                    )
                ) {
                    $results[] = $currentPbumku;
                    Log::info('PBUMKU saved to results', [
                        'nama' => substr($nama, 0, 100),
                        'persyaratan_count' => count($currentPbumku['persyaratan'] ?? [])
                    ]);
                } else {
                    Log::warning('PBUMKU nama tidak valid setelah dibersihkan', [
                        'nama_original' => $currentPbumku['nama'] ?? '',
                        'nama_cleaned' => $nama,
                        'length' => strlen($nama),
                        'has_pbumku_keyword' => preg_match('/(?:Koperasi|Unit|Usaha|Primer|Sekunder|Syariah|Simpan|Pinjam|Pembiayaan|PSAT|PDUK|USPPS|KSPPS|KSP)/i', $nama)
                    ]);
                    // TETAP SIMPAN jika panjang minimal 10 (meskipun tidak ada keyword) - lebih baik masuk daripada tidak masuk
                    if (strlen($nama) >= 10) {
                        $results[] = $currentPbumku;
                        Log::info('PBUMKU saved to results (fallback: long name)', [
                            'nama' => substr($nama, 0, 100),
                            'persyaratan_count' => count($currentPbumku['persyaratan'] ?? [])
                        ]);
                    }
                }
            }

            $currentPbumku = null;
            $currentPersyaratan = null;
            $currentSubPoin = null;
        }
    }

    /**
     * Ekstrak kode KBLI dari sebuah baris teks
     * Hanya menambahkan kode KBLI yang benar-benar ada di database
     */
    private function extractKbliCodesFromLine(string $line, array &$kbliCodes): void
    {
        preg_match_all('/\b(\d{4,5}[A-Z]?)\b/', $line, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $kbliCode) {
                $kbliCode = strtoupper(trim($kbliCode));

                $kbliExists = Kbli::where('kode', $kbliCode)->exists();

                if ($kbliExists) {
                    if (!in_array($kbliCode, $kbliCodes)) {
                        $kbliCodes[] = $kbliCode;
                        Log::debug('KBLI code extracted and verified', ['kode' => $kbliCode]);
                    }
                } else {
                    Log::warning('KBLI code extracted but not found in database', ['kode' => $kbliCode]);
                }
            }
        }
    }

    /**
     * Import data PBUMKU ke database
     */
    private function importPbumkuData(array $data): Pbumku
    {
        // Dinas (Sektor) - HANYA gunakan yang sudah ada, jangan buat baru
        $dinasName = $data['dinas_name'] ?? null;
        $dinas = null;

        if (!empty($dinasName)) {
            $dinasName = trim($dinasName);
            // Bersihkan "Dinas" di awal jika ada (karena sektor sudah berarti Dinas)
            $dinasName = preg_replace('/^Dinas\s+/i', '', $dinasName);
            $dinasName = trim($dinasName);

            // Cari Dinas yang sudah ada (case insensitive, trim whitespace)
            // Exact match dulu
            $dinas = Dinas::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($dinasName)])->first();

            if (!$dinas) {
                // Coba cari dengan LIKE (partial match) - lebih longgar
                $dinas = Dinas::whereRaw('LOWER(TRIM(nama)) LIKE ?', ['%' . strtolower($dinasName) . '%'])->first();
            }

            if (!$dinas) {
                // Coba cari dengan menghilangkan "Dinas" di depan nama yang dicari
                $dinasNameWithoutDinas = preg_replace('/^Dinas\s+/i', '', $dinasName);
                $dinas = Dinas::whereRaw('LOWER(TRIM(nama)) LIKE ?', ['%' . strtolower($dinasNameWithoutDinas) . '%'])->first();
            }

            if ($dinas) {
                Log::info('Using existing dinas', [
                    'dinas_name_searched' => $dinasName,
                    'dinas_name_found' => $dinas->nama,
                    'dinas_id' => $dinas->dinas_id,
                    'pbumku_nama' => $data['nama'] ?? 'N/A'
                ]);
            } else {
                // JANGAN buat baru - hanya log warning
                Log::warning('Dinas not found in database, skipping dinas assignment', [
                    'dinas_name_searched' => $dinasName,
                    'pbumku_nama' => $data['nama'] ?? 'N/A'
                ]);
                // Set dinas = null, PBUMKU akan dibuat tanpa dinas_id
            }
        } else {
            Log::warning('No sektor found', [
                'pbumku_nama' => $data['nama'] ?? 'N/A'
            ]);
        }

        // PBUMKU - Validasi nama tidak kosong
        $pbumkuNama = trim($data['nama'] ?? '');
        if (empty($pbumkuNama)) {
            throw new \Exception('Nama PBUMKU tidak boleh kosong');
        }

        Log::info('Creating/updating PBUMKU', [
            'nama' => substr($pbumkuNama, 0, 100),
            'dinas_id' => $dinas ? $dinas->dinas_id : null
        ]);

        // PBUMKU - Gunakan firstOrCreate untuk menghindari duplikasi
        // Tapi pastikan data selalu di-update dengan informasi terbaru
        $pbumku = Pbumku::where('nama', $pbumkuNama)->first();

        if ($pbumku) {
            // PBUMKU sudah ada, update jika diperlukan
            $needsUpdate = false;
            if ($dinas && $pbumku->dinas_id !== $dinas->dinas_id) {
                $pbumku->dinas_id = $dinas->dinas_id;
                $needsUpdate = true;
            }
            if ($needsUpdate) {
                $pbumku->save();
            }

            Log::info('PBUMKU found (existing)', [
                'pbumku_id' => $pbumku->pbumku_id,
                'nama' => $pbumku->nama,
                'was_recently_created' => false,
                'dinas_id' => $pbumku->dinas_id
            ]);
        } else {
            // PBUMKU baru, buat
            $pbumku = Pbumku::create([
                'nama' => $pbumkuNama,
                'slug' => Str::slug($pbumkuNama),
                'dinas_id' => $dinas ? $dinas->dinas_id : null
            ]);

            Log::info('PBUMKU created (new)', [
                'pbumku_id' => $pbumku->pbumku_id,
                'nama' => $pbumku->nama,
                'was_recently_created' => true,
                'dinas_id' => $pbumku->dinas_id
            ]);
        }

        // Verifikasi PBUMKU dibuat/ditemukan
        if (!$pbumku || !$pbumku->pbumku_id) {
            throw new \Exception('Gagal membuat atau menemukan PBUMKU: ' . $pbumkuNama);
        }

        // Pastikan PBUMKU benar-benar ada di database
        $pbumku->refresh();
        if (!$pbumku->exists) {
            throw new \Exception('PBUMKU tidak ada di database setelah create: ' . $pbumkuNama);
        }

        // Update slug jika belum ada atau tidak sesuai
        $expectedSlug = Str::slug($pbumku->nama);
        if (!$pbumku->slug || $pbumku->slug !== $expectedSlug) {
            $pbumku->slug = $expectedSlug;
            $pbumku->save();
            Log::info('PBUMKU slug updated', [
                'pbumku_id' => $pbumku->pbumku_id,
                'slug' => $pbumku->slug
            ]);
        }

        // Sync KBLI codes
        if (!empty($data['kbli_codes'])) {
            try {
                $kbliIds = [];
                foreach ($data['kbli_codes'] as $kbliCode) {
                    $kbli = Kbli::where('kode', $kbliCode)->first();
                    if ($kbli) {
                        $kbliIds[] = $kbli->kbli_id;
                    } else {
                        Log::warning('KBLI code not found during sync', [
                            'kbli_code' => $kbliCode,
                            'pbumku_id' => $pbumku->pbumku_id
                        ]);
                    }
                }
                if (!empty($kbliIds)) {
                    $pbumku->kbli()->sync($kbliIds);
                    Log::info('KBLI codes synced', [
                        'pbumku_id' => $pbumku->pbumku_id,
                        'kbli_count' => count($kbliIds)
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error syncing KBLI codes', [
                    'pbumku_id' => $pbumku->pbumku_id,
                    'error' => $e->getMessage()
                ]);
                // Jangan throw exception, karena PBUMKU sudah dibuat
            }
        }

        // Import persyaratan
        $persyaratanCount = 0;
        foreach ($data['persyaratan'] ?? [] as $persIndex => $persyaratanData) {
            try {
                if (empty($persyaratanData['nama']) || trim($persyaratanData['nama']) === '') {
                    Log::warning('Persyaratan kosong, skip', [
                        'index' => $persIndex,
                        'pbumku_id' => $pbumku->pbumku_id
                    ]);
                    continue;
                }

                // Pastikan persyaratan benar-benar dibuat
                $persyaratan = new PersyaratanPbumku();
                $persyaratan->pbumku_id = $pbumku->pbumku_id;
                $persyaratan->nama = trim($persyaratanData['nama']);
                $persyaratan->save();

                // Verifikasi persyaratan benar-benar ada
                if (!$persyaratan->persyaratan_pbumku_id) {
                    Log::error('Persyaratan created but no ID returned', [
                        'pbumku_id' => $pbumku->pbumku_id,
                        'nama' => substr($persyaratanData['nama'], 0, 50)
                    ]);
                    continue; // Skip jika gagal
                }

                // Refresh untuk memastikan data terbaru
                $persyaratan->refresh();

                $persyaratanCount++;
                Log::info('Persyaratan created', [
                    'persyaratan_id' => $persyaratan->persyaratan_pbumku_id,
                    'nama' => substr($persyaratan->nama, 0, 50),
                    'subpoin_count' => count($persyaratanData['subpoin'] ?? []),
                    'pbumku_id' => $pbumku->pbumku_id
                ]);

                // Import sub poin
                $subPoinCount = 0;
                foreach ($persyaratanData['subpoin'] ?? [] as $subIndex => $subPoinData) {
                    try {
                        if (empty($subPoinData['item']) || trim($subPoinData['item']) === '') {
                            Log::warning('Sub poin kosong, skip', [
                                'persyaratan_id' => $persyaratan->persyaratan_pbumku_id,
                                'index' => $subIndex
                            ]);
                            continue;
                        }

                        $subPoin = SubPoinPbumku::create([
                            'persyaratan_pbumku_id' => $persyaratan->persyaratan_pbumku_id,
                            'item' => trim($subPoinData['item']),
                        ]);

                        $subPoinCount++;

                        // Import detail
                        $detailCount = 0;
                        foreach ($subPoinData['details'] ?? [] as $detailIndex => $detailData) {
                            try {
                                $trimmedDetail = is_array($detailData) ? trim($detailData['text'] ?? '') : trim($detailData);
                                if (!empty($trimmedDetail)) {
                                    SubPoinDetailPbumku::create([
                                        'subpoin_pbumku_id' => $subPoin->subpoin_pbumku_id,
                                        'text' => $trimmedDetail,
                                    ]);
                                    $detailCount++;
                                }
                            } catch (\Exception $e) {
                                Log::error('Error creating detail', [
                                    'subpoin_pbumku_id' => $subPoin->subpoin_pbumku_id,
                                    'error' => $e->getMessage()
                                ]);
                                // Jangan throw, lanjutkan ke detail berikutnya
                            }
                        }

                        if ($detailCount > 0) {
                            Log::info('Sub poin details created', [
                                'subpoin_id' => $subPoin->subpoin_pbumku_id,
                                'detail_count' => $detailCount
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error creating sub poin', [
                            'persyaratan_id' => $persyaratan->persyaratan_pbumku_id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Jangan throw, lanjutkan ke sub poin berikutnya
                    }
                }

                if ($subPoinCount > 0) {
                    Log::info('Sub poins created', [
                        'persyaratan_id' => $persyaratan->persyaratan_pbumku_id,
                        'subpoin_count' => $subPoinCount
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error creating persyaratan', [
                    'pbumku_id' => $pbumku->pbumku_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Jangan throw, lanjutkan ke persyaratan berikutnya
                // PBUMKU sudah dibuat, jadi kita tetap return PBUMKU
            }
        }

        Log::info('PBUMKU import completed', [
            'pbumku_id' => $pbumku->pbumku_id,
            'nama' => $pbumku->nama,
            'persyaratan_count' => $persyaratanCount,
            'expected_persyaratan_count' => count($data['persyaratan'] ?? [])
        ]);

        // Refresh model untuk memastikan semua relasi ter-load
        $pbumku->refresh();

        return $pbumku;
    }
}
