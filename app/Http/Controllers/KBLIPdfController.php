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
            $kbliId = $request->get('kbli_id', 'all');
            $locale = LaravelLocalization::getCurrentLocale();
            // Log::info('PDF Generation Started', ['kbli_id' => $kbliId, 'locale' => $locale]);

            if ($kbliId === 'all') {
                $kblis = Kbli::with(['kategoriKbli', 'dinas', 'persyaratanPerizinan.subpoin.details'])->get();
            } elseif (is_array($kbliId)) {
                $kblis = Kbli::with(['kategoriKbli', 'dinas', 'persyaratanPerizinan.subpoin.details'])->whereIn('kbli_id', $kbliId)->get();
            } else {
                $kblis = Kbli::with(['kategoriKbli', 'dinas', 'persyaratanPerizinan.subpoin.details'])->where('kbli_id', $kbliId)->get();
            }

            $kategoriKblis = KategoriKbli::all();
            $dinasList = Dinas::all();

            $pdf = Pdf::loadView('pdf.kbli-export', compact('kblis', 'kategoriKblis', 'dinasList', 'kbliId', 'locale'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                ]);

            $filename = 'daftar-kbli-persyaratan-' . ($kbliId === 'all' ? 'semua' : (is_array($kbliId) ? implode('-', $kbliId) : $kbliId)) . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
}