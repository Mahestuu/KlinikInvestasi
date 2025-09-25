<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
      public function search(Request $request)
    {
        Log::info('TestController search called', $request->all());
        return response()->json(['message' => 'Test search works!', 'query' => $request->input('query')]);
    }
}
