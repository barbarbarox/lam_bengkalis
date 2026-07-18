<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\BeritaKategori;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $setting  = SiteSetting::instance();
        $kategori = BeritaKategori::all();

        $query = Berita::published()->terbaru()->with('kategori');

        // Filter kategori via slug di query string ?kategori=adat-budaya
        if ($request->filled('kategori')) {
            $query->kategori($request->kategori);
        }

        // Pencarian judul
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($sq) =>
                $sq->where('judul', 'like', "%{$q}%")
                   ->orWhere('excerpt', 'like', "%{$q}%")
            );
        }

        $berita = $query->paginate(9)->withQueryString();

        return view('public.berita.index', compact('setting', 'kategori', 'berita'));
    }

    public function show(string $slug, Request $request)
    {
        $setting = SiteSetting::instance();

        $artikel = Berita::published()
            ->bySlug($slug)
            ->with(['kategori', 'penulis'])
            ->firstOrFail();

        // Tambah view counter (atomic increment)
        $artikel->tambahView();

        // Paginate konten HTML
        $maxLength = 3000; // batas karakter teks per halaman
        $pages = \App\Services\HtmlPaginator::paginate($artikel->konten ?? '', $maxLength);
        
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        // Pastikan current page valid
        if ($currentPage > count($pages) || $currentPage < 1) {
            $currentPage = 1;
        }
        
        $currentKonten = $pages[$currentPage - 1];
        
        // Buat LengthAwarePaginator manual
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            [$currentKonten], // item di halaman ini (hanya 1 chunk html)
            count($pages), // total item (total halaman)
            1, // per page (1 item per halaman)
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Berita terkait (kategori sama, 3 artikel)
        $terkait = Berita::published()
            ->where('berita_kategori_id', $artikel->berita_kategori_id)
            ->where('id', '!=', $artikel->id)
            ->terbaru()
            ->with('kategori')
            ->limit(3)
            ->get();

        // Rekomendasi Artikel (Random, 3 artikel)
        $rekomendasi = Berita::published()
            ->where('id', '!=', $artikel->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('public.berita.show', compact('setting', 'artikel', 'currentKonten', 'paginator', 'terkait', 'rekomendasi'));
    }
}
