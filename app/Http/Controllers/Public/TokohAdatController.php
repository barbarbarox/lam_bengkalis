<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TokohAdat;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class TokohAdatController extends Controller
{
    public function index(Request $request)
    {
        $setting    = SiteSetting::instance();
        $kecamatan  = $request->get('kecamatan');

        $query = TokohAdat::aktif()->terurut();
        if ($kecamatan) {
            $query->where('kecamatan', $kecamatan);
        }

        $items       = $query->paginate(12)->withQueryString();
        $kecamatans  = TokohAdat::aktif()->whereNotNull('kecamatan')
                         ->distinct()->orderBy('kecamatan')
                         ->pluck('kecamatan');

        return view('public.tokoh-adat.index', compact('setting', 'items', 'kecamatan', 'kecamatans'));
    }

    public function show(string $slug)
    {
        $setting = SiteSetting::instance();
        $item    = TokohAdat::aktif()->where('slug', $slug)->firstOrFail();
        $lainnya = TokohAdat::aktif()->where('id', '!=', $item->id)
                    ->inRandomOrder()->limit(3)->get();

        return view('public.tokoh-adat.show', compact('setting', 'item', 'lainnya'));
    }
}
