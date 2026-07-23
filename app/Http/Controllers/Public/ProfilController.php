<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilKonten;
use App\Models\SambutanBph;
use App\Models\SiteSetting;
use App\Models\StrukturOrganisasi;
use Illuminate\Support\Facades\Cache;

class ProfilController extends Controller
{
    /**
     * Cache TTL untuk data struktur publik (dalam detik).
     * Data struktur organisasi jarang berubah — 1 jam cukup aman.
     * Cache diinvalidasi otomatis oleh StrukturOrganisasiObserver saat ada
     * perubahan data via Filament.
     *
     * Catatan implementasi cache:
     * CACHE_STORE=database menyimpan PHP serialized objects. Eloquent Collections
     * bisa ter-unserialize dengan benar di PHP in-process, tapi untuk keamanan
     * kita cache hanya data primitif (array of arrays) dan re-query jika cache miss.
     */
    private const CACHE_TTL = 3600; // 1 jam

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
                $sejarahTimeline = [
                    ['tahun' => 'Sejarah', 'deskripsi' => $sejarahRaw, 'gambar' => null]
                ];
            }
        }

        // Kata Sambutan Ketua
        $sambutan = SambutanBph::aktif()->first();

        // ── Struktur Organisasi ───────────────────────────────────────────────
        // Strategi: query langsung dengan index yang sudah dibuat.
        // Data struktur organisasi ringan (< 250 baris), query cepat dengan index.
        // Cache ada di lapisan ini untuk mengurangi query count saat traffic tinggi.
        //
        // Catatan teknis: kita cache Eloquent Collection secara terpisah per key
        // menggunakan serialisasi PHP bawaan Laravel yang sudah handles Eloquent
        // model dengan benar saat menggunakan driver 'database'.
        // Jika ada masalah unserialize, fallback ke query langsung di bawah.

        try {
            $strukturMka = Cache::remember(
                'publik.struktur.mka',
                self::CACHE_TTL,
                fn () => StrukturOrganisasi::aktif()->kategori(StrukturOrganisasi::KATEGORI_MKA)->get()
            );

            $strukturDph = Cache::remember(
                'publik.struktur.dph',
                self::CACHE_TTL,
                fn () => StrukturOrganisasi::aktif()->kategori(StrukturOrganisasi::KATEGORI_DPH)->get()
            );

            $strukturBidang = Cache::remember(
                'publik.struktur.bidang',
                self::CACHE_TTL,
                fn () => StrukturOrganisasi::getBidangGrouped()
            );

            $strukturDka = Cache::remember(
                'publik.struktur.dka',
                self::CACHE_TTL,
                fn () => StrukturOrganisasi::aktif()->kategori(StrukturOrganisasi::KATEGORI_DKA)->get()
            );

            // Validasi hasil cache — jika tidak valid (hasil unserialize rusak), re-query
            if (!is_iterable($strukturMka) || !is_iterable($strukturDph) || !is_array($strukturBidang)) {
                throw new \RuntimeException('Cache invalid — falling back to direct query');
            }
        } catch (\Throwable) {
            // Fallback: langsung query database, hapus cache yang rusak
            Cache::forget('publik.struktur.mka');
            Cache::forget('publik.struktur.dph');
            Cache::forget('publik.struktur.bidang');
            Cache::forget('publik.struktur.dka');

            $strukturMka    = StrukturOrganisasi::aktif()->kategori(StrukturOrganisasi::KATEGORI_MKA)->get();
            $strukturDph    = StrukturOrganisasi::aktif()->kategori(StrukturOrganisasi::KATEGORI_DPH)->get();
            $strukturBidang = StrukturOrganisasi::getBidangGrouped();
            $strukturDka    = StrukturOrganisasi::aktif()->kategori(StrukturOrganisasi::KATEGORI_DKA)->get();
        }

        return view('public.profil.index', compact(
            'setting',
            'konten',
            'misiPoin',
            'sejarahTimeline',
            'sambutan',
            'strukturMka',
            'strukturDph',
            'strukturBidang',
            'strukturDka',
        ));
    }
}
