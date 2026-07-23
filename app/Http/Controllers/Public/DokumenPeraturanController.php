<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DokumenPeraturan;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenPeraturanController extends Controller
{
    public function index(Request $request)
    {
        $setting = SiteSetting::instance();
        $jenis   = $request->get('jenis');
        $tahun   = $request->get('tahun');
        $q       = $request->get('q');

        $query = DokumenPeraturan::aktif()->orderByDesc('tahun')->orderBy('judul');

        if ($jenis && array_key_exists($jenis, DokumenPeraturan::JENIS)) {
            $query->where('jenis', $jenis);
        }
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        if ($q) {
            $query->where(fn($sq) => $sq
                ->where('judul', 'LIKE', "%{$q}%")
                ->orWhere('deskripsi', 'LIKE', "%{$q}%")
                ->orWhere('nomor', 'LIKE', "%{$q}%")
            );
        }

        $items  = $query->paginate(16)->withQueryString();
        $tahuns = DokumenPeraturan::aktif()->whereNotNull('tahun')
                    ->distinct()->orderByDesc('tahun')
                    ->pluck('tahun');

        return view('public.dokumen.index', compact('setting', 'items', 'jenis', 'tahun', 'q', 'tahuns'));
    }

    public function unduh(DokumenPeraturan $dokumen)
    {
        abort_unless($dokumen->is_aktif && $dokumen->file_path, 404);
        $dokumen->tambahUnduh();
        return Storage::download($dokumen->file_path, basename($dokumen->file_path));
    }
}
