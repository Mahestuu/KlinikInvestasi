<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\Pbumku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PbumkuController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Pbumku index called', $request->all());
        $dinas = Dinas::all();
        $selectedDinas = $request->input('dinas_id');
        $query = $request->input('query');

        $pbumkuQuery = Pbumku::query();

        if ($selectedDinas) {
            $pbumkuQuery->where('dinas_id', $selectedDinas);
        }

        if ($query) {
            $pbumkuQuery = Pbumku::search($query);
        }

        $pbumku = $pbumkuQuery->with('dinas', 'kbli', 'persyaratanPbumku.subpoin')->paginate(6);

        return view('utama.pbumku', compact('dinas', 'pbumku', 'selectedDinas', 'query'));
    }

    public function show($pbumku_id)
    {
        Log::info('Pbumku show called', ['pbumku_id' => $pbumku_id]);
        $pbumku = Pbumku::with('dinas', 'kbli', 'persyaratanPbumku.subpoin')->findOrFail($pbumku_id);
        return view('utama.detailpbumku', compact('pbumku'));
    }

    public function search(Request $request)
    {
        Log::info('Pbumku search called', $request->all());
        $dinas = Dinas::all();
        $selectedDinas = $request->input('dinas_id');
        $query = $request->input('query');

        $pbumkuQuery = Pbumku::query();

        if ($query) {
            $searchResults = Pbumku::search($query)->get()->pluck('pbumku_id');
            $pbumkuQuery->whereIn('pbumku_id', $searchResults);
        }

        if ($selectedDinas) {
            $pbumkuQuery->where('dinas_id', $selectedDinas);
        }

        $pbumku = $pbumkuQuery->with('dinas', 'kbli', 'persyaratanPbumku.subpoin')->paginate(6);

        return view('utama.pbumku', compact('dinas', 'pbumku', 'selectedDinas', 'query'));
    }

    public function liveSearch(Request $request)
    {
        Log::info('Pbumku live search called', $request->all());
        $query = $request->input('query');
        $dinas_id = $request->input('dinas_id');

        $pbumkuQuery = Pbumku::search($query);

        if ($dinas_id) {
            $pbumkuQuery->where('dinas_id', $dinas_id);
        }

        $results = $pbumkuQuery->take(5)->get()->map(function ($pbumku) {
            return [
                'nama' => $pbumku->nama,
                'search_url' => route('pbumku.show', ['pbumku_id' => $pbumku->pbumku_id]),
            ];
        });

        return response()->json($results);
    }
}
