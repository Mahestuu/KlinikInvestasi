<?php

namespace App\Http\Controllers;

use App\Models\Kbli;
use App\Models\KategoriKbli;
use App\Models\Dinas;
use App\Models\PersyaratanPerizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class KbliPdfController extends Controller
{
    public function generate(Request $request)
    {
        try {
            // Handle parameter kbli_id yang bisa berupa string, array, atau query string
            $kbliId = $request->get('kbli_id', 'all');

            // Jika kbli_id berupa string yang berisi array (dari query string)
            if (is_string($kbliId) && str_starts_with($kbliId, '[')) {
                $kbliId = json_decode($kbliId, true);
            }

            // Jika kbli_id berupa query string dengan format "1,2,3"
            if (is_string($kbliId) && str_contains($kbliId, ',')) {
                $kbliId = explode(',', $kbliId);
            }

            $locale = LaravelLocalization::getCurrentLocale();
            Log::info('PDF Generation Started', ['kbli_id' => $kbliId, 'locale' => $locale, 'type' => gettype($kbliId)]);

            if ($kbliId === 'all' || $kbliId === null || (is_array($kbliId) && empty($kbliId))) {
                $kblis = Kbli::with(['kategoriKbli', 'dinas', 'persyaratanPerizinan.subpoin.details'])->get();
            } elseif (is_array($kbliId)) {
                $kblis = Kbli::with(['kategoriKbli', 'dinas', 'persyaratanPerizinan.subpoin.details'])->whereIn('kbli_id', $kbliId)->get();
            } else {
                $kblis = Kbli::with(['kategoriKbli', 'dinas', 'persyaratanPerizinan.subpoin.details'])->where('kbli_id', $kbliId)->get();
            }

            $kategoriKblis = KategoriKbli::all();
            $dinasList = Dinas::all();

            Log::info('KBLI Data Loaded', ['count' => $kblis->count()]);

            // Pastikan fungsi linkify tersedia
            if (!function_exists('linkify')) {
                // Load helper file manually jika belum ter-load
                if (file_exists(app_path('helpers.php'))) {
                    require_once app_path('helpers.php');
                }
            }

            $pdf = Pdf::loadView('pdf.kbli-export', compact('kblis', 'kategoriKblis', 'dinasList', 'kbliId', 'locale'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => false,
                    'chroot' => realpath(base_path()),
                    'enableCssFloat' => true,
                    'enableFontSubsetting' => false,
                    'dpi' => 150,
                ]);

            // Helper function untuk membersihkan nama untuk filename
            $cleanFilename = function ($nama) {
                // Hapus karakter khusus kecuali spasi, huruf, angka, dan underscore
                $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $nama);
                // Ganti spasi ganda dengan single space
                $clean = preg_replace('/\s+/', ' ', trim($clean));
                // Ganti spasi dengan underscore
                $clean = str_replace(' ', '_', $clean);
                // Batasi panjang nama (maksimal 50 karakter)
                if (strlen($clean) > 50) {
                    $clean = substr($clean, 0, 50);
                }
                return $clean;
            };

            // Generate filename berdasarkan format: kode kbli_nama kbli
            if ($kbliId === 'all') {
                $filename = 'semua-kbli.pdf';
            } elseif (is_array($kbliId)) {
                // Jika multiple KBLI, gunakan kode pertama dan nama KBLI pertama
                if ($kblis->count() > 0) {
                    $firstKbli = $kblis->first();
                    $filename = $firstKbli->kode . '_' . $cleanFilename($firstKbli->nama);
                    if ($kblis->count() > 1) {
                        $filename .= '_dan_' . ($kblis->count() - 1) . '_lainnya';
                    }
                    $filename .= '.pdf';
                } else {
                    $filename = 'kbli-' . implode('-', $kbliId) . '.pdf';
                }
            } else {
                // Single KBLI: format kode kbli_nama kbli
                if ($kblis->count() > 0) {
                    $kbli = $kblis->first();
                    $filename = $kbli->kode . '_' . $cleanFilename($kbli->nama) . '.pdf';
                } else {
                    $filename = 'kbli-' . $kbliId . '.pdf';
                }
            }

            Log::info('PDF Generated Successfully', ['filename' => $filename]);

            // Return PDF download response
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF Generation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Jika dalam konteks Filament, return response error
            if ($request->expectsJson() || str_contains($request->header('Accept', ''), 'application/json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal generate PDF: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}
