<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PendidikanPelatihan;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PendidikanPelatihanController extends Controller
{
    public function index(Request $request)
    {
        $setting = SiteSetting::instance();
        $jenis   = $request->get('jenis');

        $query = PendidikanPelatihan::aktif()->orderBy('tanggal_mulai')->orderByDesc('id');
        if ($jenis && array_key_exists($jenis, PendidikanPelatihan::JENIS)) {
            $query->where('jenis', $jenis);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('public.pendidikan-pelatihan.index', compact('setting', 'items', 'jenis'));
    }

    public function show(string $slug)
    {
        $setting = SiteSetting::instance();
        $item    = PendidikanPelatihan::aktif()->where('slug', $slug)->firstOrFail();
        $lainnya = PendidikanPelatihan::aktif()->where('id', '!=', $item->id)
                    ->where('jenis', $item->jenis)->latest()->limit(3)->get();

        return view('public.pendidikan-pelatihan.show', compact('setting', 'item', 'lainnya'));
    }
}
