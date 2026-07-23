<?php

use App\Http\Controllers\Public\AgendaKegiatanController;
use App\Http\Controllers\Public\BerandaController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\DokumenPeraturanController;
use App\Http\Controllers\Public\GaleriController;
use App\Http\Controllers\Public\GelarAdatController;
use App\Http\Controllers\Public\HukumAdatController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\Public\LamKecamatanController;
use App\Http\Controllers\Public\PendidikanPelatihanController;
use App\Http\Controllers\Public\PencarianController;
use App\Http\Controllers\Public\PermohonanInformasiController;
use App\Http\Controllers\Public\ProfilController;
use App\Http\Controllers\Public\SetLocaleController;
use App\Http\Controllers\Public\TokohAdatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik — Situs LAM Bengkalis
|--------------------------------------------------------------------------
*/

// ─── Beranda ──────────────────────────────────────────────────────────────────
Route::get('/', BerandaController::class)->name('beranda');

// ─── Newsletter ───────────────────────────────────────────────────────────────
Route::post('/subscribe', [\App\Http\Controllers\SubscriberController::class, 'store'])->name('subscribe');

// ─── Bahasa / Locale Switcher ──────────────────────────────────────────────────
Route::get('/bahasa/{locale}', SetLocaleController::class)->name('locale.switch');

// ─── Pencarian Global ─────────────────────────────────────────────────────────
Route::get('/cari', PencarianController::class)->name('cari');

// ─── Profil Lembaga ───────────────────────────────────────────────────────────
Route::get('/profil', ProfilController::class)->name('profil');

// ─── Berita & Publikasi ───────────────────────────────────────────────────────
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

// ─── Galeri Foto ──────────────────────────────────────────────────────────────
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

// ─── LAM Kecamatan ────────────────────────────────────────────────────────────
Route::get('/lam-kecamatan', [LamKecamatanController::class, 'index'])->name('lam-kecamatan.index');

// ─── Hukum Adat ───────────────────────────────────────────────────────────────
Route::get('/hukum-adat', [HukumAdatController::class, 'index'])->name('hukum-adat.index');
Route::get('/hukum-adat/{slug}', [HukumAdatController::class, 'show'])->name('hukum-adat.show');
Route::get('/hukum-adat/{hukumAdat}/unduh', [HukumAdatController::class, 'unduh'])
    ->name('hukum-adat.unduh')
    ->middleware('throttle:30,1');

// ─── Tokoh Adat ───────────────────────────────────────────────────────────────
Route::get('/tokoh-adat', [TokohAdatController::class, 'index'])->name('tokoh-adat.index');
Route::get('/tokoh-adat/{slug}', [TokohAdatController::class, 'show'])->name('tokoh-adat.show');

// ─── Gelar & Kehormatan Adat ──────────────────────────────────────────────────
Route::get('/gelar-adat', [GelarAdatController::class, 'index'])->name('gelar-adat.index');
Route::get('/gelar-adat/{slug}', [GelarAdatController::class, 'show'])->name('gelar-adat.show');

// ─── Agenda Kegiatan ──────────────────────────────────────────────────────────
Route::get('/agenda', [AgendaKegiatanController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{slug}', [AgendaKegiatanController::class, 'show'])->name('agenda.show');

// ─── Dokumen & Peraturan ──────────────────────────────────────────────────────
Route::get('/dokumen', [DokumenPeraturanController::class, 'index'])->name('dokumen.index');
Route::get('/dokumen/{dokumen}/unduh', [DokumenPeraturanController::class, 'unduh'])
    ->name('dokumen.unduh')
    ->middleware('throttle:30,1');

// ─── Permohonan Informasi ─────────────────────────────────────────────────────
Route::get('/permohonan-informasi', [PermohonanInformasiController::class, 'index'])
    ->name('permohonan-informasi.index');
Route::post('/permohonan-informasi/kirim', [PermohonanInformasiController::class, 'kirim'])
    ->name('permohonan-informasi.kirim')
    ->middleware('throttle:3,10'); // 3 per 10 menit
Route::get('/permohonan-informasi/status', [PermohonanInformasiController::class, 'cekStatus'])
    ->name('permohonan-informasi.status');

// ─── Pendidikan & Pelatihan ───────────────────────────────────────────────────
Route::get('/pendidikan-pelatihan', [PendidikanPelatihanController::class, 'index'])
    ->name('pendidikan.index');
Route::get('/pendidikan-pelatihan/{slug}', [PendidikanPelatihanController::class, 'show'])
    ->name('pendidikan.show');

// ─── Kontak & Aduan ───────────────────────────────────────────────────────────
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak/kirim', [KontakController::class, 'kirim'])
    ->name('kontak.kirim')
    ->middleware('throttle:5,1');

// ─── Jejak Layar (redirect ke URL eksternal) ──────────────────────────────────
Route::get('/museum', function () {
    $url = \App\Models\SiteSetting::instance()->url_museum;
    if (empty($url)) return redirect()->route('beranda');
    return redirect()->away($url);
})->name('museum');

Route::get('/jejaklayar/{fitur}', function (string $fitur) {
    $setting = \App\Models\SiteSetting::instance();
    $url     = $setting->urlJejak($fitur);
    if (!$url || $url === '#') return redirect()->route('beranda');
    return redirect()->away($url);
})->name('jejaklayar')->where('fitur', 'bahasa|warisan|kesenian|destinasi|artikel|pustaka');

// ─── Sitemap XML ──────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])
    ->name('sitemap');
