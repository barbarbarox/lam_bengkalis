<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LamKecamatan;
use App\Models\SiteSetting;

class LamKecamatanController extends Controller
{
    public function index()
    {
        $setting    = SiteSetting::instance();
        $kecamatans = LamKecamatan::aktif()->terurut()->get();

        return view('public.lam-kecamatan.index', compact('setting', 'kecamatans'));
    }
}
