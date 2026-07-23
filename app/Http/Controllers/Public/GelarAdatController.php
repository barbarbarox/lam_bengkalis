<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GelarAdat;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class GelarAdatController extends Controller
{
    public function index(Request $request)
    {
        $setting = SiteSetting::instance();
        $jenis   = $request->get('jenis');

        $query = GelarAdat::aktif()->orderBy('urutan')->orderBy('nama_gelar');
        if ($jenis && array_key_exists($jenis, GelarAdat::JENIS)) {
            $query->jenis($jenis);
        }

        $items = $query->paginate(15)->withQueryString();

        return view('public.gelar-adat.index', compact('setting', 'items', 'jenis'));
    }

    public function show(string $slug)
    {
        $setting = SiteSetting::instance();
        $item    = GelarAdat::aktif()->where('slug', $slug)->firstOrFail();

        return view('public.gelar-adat.show', compact('setting', 'item'));
    }
}
