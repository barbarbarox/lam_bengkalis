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

        // Sejarah Timeline: parse JSON dari profil 'sejarah-lam'
        $sejarahRaw = $konten->get('sejarah-lam')?->konten;
        $sejarahTimeline = [];
        if ($sejarahRaw) {
            $decoded = json_decode($sejarahRaw, true);
            if (is_array($decoded)) {
                $sejarahTimeline = $decoded;
            } elseif (is_string($sejarahRaw) && trim($sejarahRaw) !== '') {
                // Fallback untuk konten lama yang masih berupa HTML string
                $sejarahTimeline = [
                    ['tahun' => 'Sejarah', 'deskripsi' => $sejarahRaw, 'gambar' => null]
                ];
            }
        }

        // Struktur Organisasi
        $strukturMka = StrukturOrganisasi::aktif()
            ->kategori(StrukturOrganisasi::KATEGORI_MKA)
            ->get();

        $strukturDph = StrukturOrganisasi::aktif()
            ->kategori(StrukturOrganisasi::KATEGORI_DPH)
            ->get();

        return view('public.profil.index', compact(
            'setting', 'konten', 'misiPoin', 'sejarahTimeline', 'strukturMka', 'strukturDph'
        ));
    }
}
