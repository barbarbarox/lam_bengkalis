<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HukumAdat;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HukumAdatController extends Controller
{
    public function index(Request $request)
    {
        $setting  = SiteSetting::instance();
        $jenis    = $request->get('jenis');

        $query = HukumAdat::aktif()->latest('tahun')->orderByDesc('id');

        if ($jenis && array_key_exists($jenis, HukumAdat::JENIS)) {
            $query->jenis($jenis);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('public.hukum-adat.index', compact('setting', 'items', 'jenis'));
    }

    public function show(string $slug)
    {
        $setting = SiteSetting::instance();
        $item    = HukumAdat::aktif()->where('slug', $slug)->firstOrFail();

        return view('public.hukum-adat.show', compact('setting', 'item'));
    }

    /** Download file & increment counter */
    public function unduh(HukumAdat $hukumAdat)
    {
        abort_unless($hukumAdat->is_aktif && $hukumAdat->file_path, 404);

        $hukumAdat->tambahUnduh();

        return Storage::download($hukumAdat->file_path, basename($hukumAdat->file_path));
    }
}
