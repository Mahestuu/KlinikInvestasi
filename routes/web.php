<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KbliController;
use App\Http\Controllers\PbumkuController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('utama.home');
});

Route::get('/kbli', [KbliController::class, 'index'])->name('kbli.index');
Route::get('/kbli/search', [KbliController::class, 'search'])->name('kbli.search');
Route::get('/kbli/live-search', [KbliController::class, 'liveSearch'])->name('kbli.live-search');
Route::get('/kbli/{kbli_id}', [KbliController::class, 'show'])->name('kbli.show');

Route::get('/pbumku', [PbumkuController::class, 'index'])->name('pbumku.index');
Route::get('/pbumku/search', [PbumkuController::class, 'search'])->name('pbumku.search');
Route::get('/pbumku/live-search', [PbumkuController::class, 'liveSearch'])->name('pbumku.live-search');
Route::get('/pbumku/{pbumku_id}', [PbumkuController::class, 'show'])->name('pbumku.show');

// Route::get('/kbli/search', [TestController::class, 'search'])->name('kbli.search');


// Route::get('/kbli/search', function () {
//     return 'Route kbli/search works!';
// })->name('kbli.search');

// Route::get('/pbumku', function () {
//     return view('utama.pbumku');
// });

Route::get('kbli/detailkbli', function () {
    return view('utama.detailkbli');
});

// Route::get('/kbli/search', function () {
//     return 'Route kbli/search works!';
// })->name('kbli.search');