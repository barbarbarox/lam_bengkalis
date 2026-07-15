<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BackgroundSlide;
use App\Models\BannerIklan;
use App\Models\Berita;
use App\Models\BeritaKategori;
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

        // Berita terbaru — 6 artikel
        $beritaTerbaru = Berita::published()
            ->terbaru()
            ->with('kategori')
            ->limit(6)
            ->get();

        // Semua kategori untuk quick filter
        $kategori = BeritaKategori::withCount(['berita' => fn ($q) =>
            $q->where('status', Berita::STATUS_PUBLISHED)
        ])->get();

        return view('public.beranda.index', compact(
            'setting', 'slides', 'banners', 'sambutan', 'beritaTerbaru', 'kategori'
        ));
    }
}
