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
            return [
                'id'        => $f->id,
                'src'       => Storage::url($f->foto_path),
                'judul'     => $f->judul,
                'deskripsi' => $f->deskripsi,
                'height'    => 200 + ($f->id % 5) * 80,
            ];
        })->values()->toArray();

        return view('public.galeri.index', compact('setting', 'fotos', 'fotosJson'));
    }
}

