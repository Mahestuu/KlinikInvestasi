<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KbliController;
use App\Http\Controllers\PbumkuController;
use App\Http\Controllers\TestController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\KbliPdfController;
use App\Http\Controllers\PbumkuPdfController;
use App\Http\Controllers\ImportKbliFromPdfController;
use App\Http\Controllers\ImportPbumkuFromPdfController;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect', 'localeViewPath']
], function () {
    Route::get('/pbumku/pdf/export', [PbumkuPdfController::class, 'generate'])->name('pbumku.pdf.export');
    Route::get('/kbli/export-pdf', [KbliPdfController::class, 'generate'])->name('kbli.export-pdf');
    Route::post('/kbli/import-pdf', [ImportKbliFromPdfController::class, 'import'])->name('kbli.import-pdf');
    Route::post('/pbumku/import-pdf', [ImportPbumkuFromPdfController::class, 'import'])->name('pbumku.import-pdf');


    Route::get('/', function () {
        return view('utama.home');
    })->name('home');

    Route::get('/kbli', [KbliController::class, 'index'])->name('kbli.index');
    Route::get('/kbli/search', [KbliController::class, 'search'])->name('kbli.search');
    Route::get('/kbli/live-search', [KbliController::class, 'liveSearch'])->name('kbli.live-search');
    Route::get('/kbli/{kbli:slug}', [KbliController::class, 'show'])->name('kbli.show');

    Route::get('/pbumku', [PbumkuController::class, 'index'])->name('pbumku.index');
    Route::get('/pbumku/search', [PbumkuController::class, 'search'])->name('pbumku.search');
    Route::get('/pbumku/live-search', [PbumkuController::class, 'liveSearch'])->name('pbumku.live-search');
    Route::get('/pbumku/{pbumku:slug}', [PbumkuController::class, 'show'])->name('pbumku.show');
});

// Route::get('/', function () {
//     return view('utama.home');
// });

// Route::get('/kbli', [KbliController::class, 'index'])->name('kbli.index');
// Route::get('/kbli/search', [KbliController::class, 'search'])->name('kbli.search');
// Route::get('/kbli/live-search', [KbliController::class, 'liveSearch'])->name('kbli.live-search');
// Route::get('/kbli/{kbli_id}', [KbliController::class, 'show'])->name('kbli.show');

// Route::get('/pbumku', [PbumkuController::class, 'index'])->name('pbumku.index');
// Route::get('/pbumku/search', [PbumkuController::class, 'search'])->name('pbumku.search');
// Route::get('/pbumku/live-search', [PbumkuController::class, 'liveSearch'])->name('pbumku.live-search');
// Route::get('/pbumku/{pbumku_id}', [PbumkuController::class, 'show'])->name('pbumku.show');

// Route::get('/kbli/search', [TestController::class, 'search'])->name('kbli.search');


// Route::get('/kbli/search', function () {
//     return 'Route kbli/search works!';
// })->name('kbli.search');

// Route::get('/pbumku', function () {
//     return view('utama.pbumku');
// });


// Route::get('/kbli/search', function () {
//     return 'Route kbli/search works!';
// })->name('kbli.search');