<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\BackgroundSlide;
use App\Models\BannerIklan;
use App\Models\Berita;
use App\Models\BeritaKategori;
use App\Models\DokumenPeraturan;
use App\Models\JelajahBudaya;
use App\Models\Layanan;
use App\Models\SambutanBph;
use App\Models\SiteSetting;

class BerandaController extends Controller
{
    public function __invoke()
    {
        $setting = SiteSetting::instance();

        $slides = BackgroundSlide::aktif()->get();
        $banners = BannerIklan::aktifSekarang()->get();
        $sambutan = SambutanBph::aktif()->first();
        $layanans = Layanan::aktif()->terurut()->get();

        // Berita terbaru — 6 artikel
        $beritaTerbaru = Berita::published()
            ->terbaru()
            ->with('kategori')
            ->limit(6)
            ->get();

        // Satu berita unggulan (paling baru)
        $beritaUnggulan = $beritaTerbaru->first();
        // Daftar berita terbaru sisanya (tanpa yang unggulan)
        $beritaSampingan = $beritaTerbaru->skip(1)->take(4);

        // Kategori berita untuk quick filter
        $kategori = BeritaKategori::withCount(['berita' => fn ($q) =>
            $q->where('status', Berita::STATUS_PUBLISHED)
        ])->get();

        // Agenda mendatang — 4 event terdekat
        $agendaMendatang = AgendaKegiatan::akanDatang()->limit(4)->get();

        // Jelajah Budaya
        $jelajahItems = JelajahBudaya::aktif()->terurut()->get();

        // Dokumen terbaru — 4 dokumen
        $dokumenTerbaru = DokumenPeraturan::aktif()->orderByDesc('tahun')->orderByDesc('id')->limit(4)->get();

        return view('public.beranda.index', compact(
            'setting', 'slides', 'banners', 'sambutan', 'layanans',
            'beritaTerbaru', 'beritaUnggulan', 'beritaSampingan',
            'kategori', 'agendaMendatang', 'dokumenTerbaru', 'jelajahItems'
        ));
    }
}


