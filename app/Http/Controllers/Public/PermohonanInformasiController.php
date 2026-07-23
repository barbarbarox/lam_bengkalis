<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PermohonanInformasi;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PermohonanInformasiController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::instance();
        $item    = null;
        $nomor   = null;
        return view('public.permohonan-informasi.index', compact('setting', 'item', 'nomor'));
    }

    public function kirim(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon'      => 'required|string|max:200',
            'email'             => 'required|email|max:200',
            'no_hp'             => 'nullable|string|max:30',
            'instansi'          => 'nullable|string|max:200',
            'jenis_informasi'   => 'required|string|max:200',
            'uraian_permohonan' => 'required|string|min:20|max:5000',
        ]);

        $permohonan = PermohonanInformasi::create([
            ...$validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('permohonan-informasi.index')
            ->with('sukses_tiket', $permohonan->nomor_tiket)
            ->with('success', 'Permohonan informasi Anda telah berhasil dikirim.');
    }

    /** Cek status via nomor tiket */
    public function cekStatus(Request $request)
    {
        $nomor  = $request->get('tiket');
        $item   = null;
        $setting = SiteSetting::instance();

        if ($nomor) {
            $item = PermohonanInformasi::where('nomor_tiket', strtoupper($nomor))->first();
        }

        return view('public.permohonan-informasi.index', compact('setting', 'item', 'nomor'));
    }
}
