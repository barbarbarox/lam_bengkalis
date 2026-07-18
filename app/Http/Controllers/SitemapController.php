<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $beritaList = Berita::published()
            ->terbaru()
            ->get(['slug', 'updated_at', 'tanggal_publish']);

        $content = view('sitemap', compact('beritaList'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
