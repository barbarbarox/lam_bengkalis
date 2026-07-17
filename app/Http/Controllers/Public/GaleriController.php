<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::instance();

        $fotos = Galeri::aktif()
            ->terurut()
            ->get();

        // Pre-build data JSON untuk JavaScript (hindari arrow function di Blade @json)
        $fotosJson = $fotos->map(function ($f) {
            // Coba baca dimensi gambar asli untuk Masonry yang proporsional
            $height = 400; // default fallback
            try {
                $diskPath = Storage::disk('public')->path($f->foto_path);
                if (file_exists($diskPath)) {
                    [$w, $h] = getimagesize($diskPath);
                    if ($w && $h) {
                        // Normalise: perlebar kolom = 400px, scale tinggi proporsional
                        $height = (int) round(($h / $w) * 400);
                        // Batasi antara 200px - 800px
                        $height = max(200, min(800, $height));
                    }
                }
            } catch (\Throwable $e) {
                // Bila gagal, gunakan fallback variasi agar masonry tetap dinamis
                $height = 250 + ($f->id % 6) * 60;
            }

            return [
                'id'        => $f->id,
                'src'       => Storage::url($f->foto_path),
                'judul'     => $f->judul,
                'deskripsi' => $f->deskripsi,
                'height'    => $height,
            ];
        })->values()->toArray();

        return view('public.galeri.index', compact('setting', 'fotos', 'fotosJson'));
    }
}

