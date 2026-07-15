<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilKonten;
use App\Models\SiteSetting;
use App\Models\StrukturOrganisasi;

class ProfilController extends Controller
{
    public function __invoke()
    {
        $setting = SiteSetting::instance();

        // Semua konten profil aktif, di-key-by slug
        $konten = ProfilKonten::aktif()->get()->keyBy('slug');

        // Misi: parse JSON dari profil 'misi-lam'
        $misiRaw = $konten->get('misi-lam')?->konten;
        $misiPoin = [];
        if ($misiRaw) {
            $decoded = json_decode($misiRaw, true);
            $misiPoin = is_array($decoded) ? $decoded : [];
        }

        // Struktur Organisasi
        $strukturMka = StrukturOrganisasi::aktif()
            ->kategori(StrukturOrganisasi::KATEGORI_MKA)
            ->get();

        $strukturDph = StrukturOrganisasi::aktif()
            ->kategori(StrukturOrganisasi::KATEGORI_DPH)
            ->get();

        return view('public.profil.index', compact(
            'setting', 'konten', 'misiPoin', 'strukturMka', 'strukturDph'
        ));
    }
}
