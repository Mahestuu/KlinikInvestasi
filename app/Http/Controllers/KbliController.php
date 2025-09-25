<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kbli;
use App\Models\Dinas;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log;

class KbliController extends Controller

{
    public function index()
    {
        Log::info('Index method called');
        $dinas = Dinas::with('kbli')->get();
        return view('utama.kbli', compact('dinas'));
    }

   public function show($kbli_id)
    {
        Log::info('Show method called', ['kbli_id' => $kbli_id]);
        $kbli = Kbli::with('dinas', 'kategoriKbli', 'persyaratanPerizinan.subpoin')->findOrFail($kbli_id);
        return view('utama.detailkbli', compact('kbli'));
    }

    public function search(Request $request)
    {
        Log::info('Search method called', $request->all());
        $query = $request->input('query');
        $kbli = $query ? Kbli::search($query)->get() : collect([]);
        $dinas = Dinas::with('kbli')->get();
        return view('utama.kbli', compact('dinas', 'kbli', 'query'));
    }

    public function liveSearch(Request $request)
    {
        Log::info('LiveSearch method called', $request->all());
        $query = $request->input('query');
        $results = $query ? Kbli::search($query)->take(5)->get() : collect([]);
        return response()->json(
            $results->map(function ($item) {
                return [
                    'kbli_id' => $item->kbli_id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'ruang_lingkup' => $item->ruang_lingkup,
                    'kategori' => $item->kategoriKbli->nama ?? 'Tidak Ada Kategori',
                    'dinas' => $item->dinas->nama ?? 'Tidak Ada Dinas',
                ];
            })
        );
    }
}
