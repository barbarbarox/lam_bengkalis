<?php

use App\Http\Controllers\Public\BerandaController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\Public\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik — Situs LAM Bengkalis
|--------------------------------------------------------------------------
|
| Semua rute publik berada di sini. Panel admin berada di /pentadbir.
|
*/

// Beranda
Route::get('/', BerandaController::class)->name('beranda');

// Profil Lembaga
Route::get('/profil', ProfilController::class)->name('profil');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

// Kontak & Aduan
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak/kirim', [KontakController::class, 'kirim'])
    ->name('kontak.kirim')
    ->middleware('throttle:5,1');  // 5 pengiriman per menit per IP

// Museum / Jejak Layar — redirect ke URL eksternal dari SiteSetting
Route::get('/museum', function () {
    $url = \App\Models\SiteSetting::instance()->url_museum;
    if (empty($url)) {
        return redirect()->route('beranda');
    }
    return redirect()->away($url);
})->name('museum');
