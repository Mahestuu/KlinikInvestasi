<?php

namespace App\Http\Controllers;

use App\Models\Pbumku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PbumkuPdfController extends Controller
{
    public function generate(Request $request)
    {
        $pbumkuIds = $request->input('pbumku_ids', []);
        $locale = app()->getLocale();

        if (empty($pbumkuIds)) {
            $pbumkus = Pbumku::with(['dinas', 'kbli', 'persyaratanPbumku.subpoinPbumku'])->get();
        } elseif (is_array($pbumkuIds)) {
            $pbumkus = Pbumku::with(['dinas', 'kbli', 'persyaratanPbumku.subpoinPbumku'])->whereIn('pbumku_id', $pbumkuIds)->get();
        } else {
            $pbumkus = Pbumku::with(['dinas', 'kbli', 'persyaratanPbumku.subpoinPbumku'])->where('pbumku_id', $pbumkuIds)->get();
        }

        $pdf = Pdf::loadView('pdf.pbumku-export', compact('pbumkus', 'locale'));

        $filename = 'daftar-pbumku-persyaratan-' . ($pbumkuIds === 'all' || empty($pbumkuIds) ? 'semua' : implode('-', $pbumkuIds)) . '.pdf';
        return $pdf->download($filename);
    }
}