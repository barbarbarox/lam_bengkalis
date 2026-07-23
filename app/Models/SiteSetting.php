<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SiteSetting — Model Singleton
 *
 * Tabel ini selalu memiliki tepat satu baris dengan id=1.
 * Jangan pernah menggunakan ::create() atau ::insert() langsung.
 *
 * Cara penggunaan yang benar:
 *   // Baca pengaturan (selalu tersedia karena singleton):
 *   $setting = SiteSetting::instance();
 *   echo $setting->nama_lembaga;
 *
 *   // Update pengaturan:
 *   SiteSetting::updateSettings(['nama_lembaga' => 'LAM Bengkalis Baru']);
 *
 * @property int    $id
 * @property string $nama_lembaga
 * @property string $singkatan
 * @property string|null $logo_path
 * @property string|null $foto_balai_adat
 * @property string|null $favicon_path
 * @property string|null $alamat
 * @property string|null $email_kontak
 * @property string|null $no_telp
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $youtube_url
 * @property string|null $twitter_url
 * @property string|null $meta_deskripsi
 * @property string|null $meta_keywords
 * @property string|null $teks_footer
 * @property string|null $selayang_pandang
 * @property int|null    $tahun_berdiri
 * @property int         $stat_kecamatan
 * @property int         $stat_desa_kelurahan
 * @property int         $stat_kegiatan_budaya
 * @property int         $stat_naskah_koleksi
 */
class SiteSetting extends Model
{
    protected $table = 'site_settings';

    /** Singleton ID — tidak boleh diubah. */
    public const SINGLETON_ID = 1;

    /**
     * Kolom yang boleh diisi via updateSettings().
     * `id` sengaja dikecualikan untuk mencegah pembuatan baris baru.
     */
    protected $fillable = [
        'nama_lembaga',
        'singkatan',
        'logo_path',
        'foto_balai_adat',
        'favicon_path',
        'alamat',
        'email_kontak',
        'no_telp',
        'no_wa',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'meta_deskripsi',
        'meta_keywords',
        'teks_footer',
        'selayang_pandang',
        'tahun_berdiri',
        'embed_peta',
        'url_museum',
        'url_jejaklayar',
        'url_jl_bahasa',
        'url_jl_warisan',
        'url_jl_kesenian',
        'url_jl_destinasi',
        'url_jl_artikel',
        'url_jl_pustaka',
        'bahasa_aktif',
        'hero_profil_path',
        'hero_berita_path',
        'hero_kontak_path',
        'hero_galeri_path',
        // Hero untuk halaman modul baru
        'hero_lam_kecamatan_path',
        'hero_hukum_adat_path',
        'hero_tokoh_adat_path',
        'hero_gelar_adat_path',
        'hero_agenda_path',
        'hero_dokumen_path',
        'hero_permohonan_path',
        'hero_pendidikan_path',
        'hero_cari_path',
        // Statistik lembaga
        'stat_kecamatan',
        'stat_desa_kelurahan',
        'stat_kegiatan_budaya',
        'stat_naskah_koleksi',
    ];

    // ── Singleton Pattern ─────────────────────────────────────────────────────

    /**
     * Ambil instance singleton pengaturan situs.
     * Selalu mengembalikan baris id=1 yang dibuat saat migration.
     */
    public static function instance(): static
    {
        return static::findOrFail(self::SINGLETON_ID);
    }

    /**
     * Update kolom pengaturan situs secara aman.
     * Menggunakan updateOrInsert untuk memastikan baris singleton tetap ada.
     *
     * @param  array<string, mixed>  $data
     */
    public static function updateSettings(array $data): static
    {
        $instance = static::instance();
        $instance->fill($data)->save();

        return $instance->fresh();
    }

    // ── Override: blokir pembuatan baris baru ─────────────────────────────────

    /**
     * Mencegah pembuatan baris baru secara tidak sengaja.
     * Selalu gunakan updateSettings() sebagai pengganti.
     *
     * @throws \LogicException
     */
    public static function create(array $attributes = []): never
    {
        throw new \LogicException(
            'SiteSetting adalah singleton. Gunakan SiteSetting::updateSettings([...]) untuk mengubah nilai.'
        );
    }

    /**
     * Accessor untuk mendapatkan url_museum dari JSON meta_keywords
     */
    public function getUrlMuseumAttribute(): ?string
    {
        if ($this->meta_keywords) {
            $decoded = json_decode($this->meta_keywords, true);
            return $decoded['url_museum'] ?? null;
        }
        return null;
    }

    /**
     * Accessor untuk daftar bahasa aktif sebagai array.
     */
    public function getBahasaAktifArrayAttribute(): array
    {
        if ($this->bahasa_aktif) {
            $decoded = json_decode($this->bahasa_aktif, true);
            return is_array($decoded) ? $decoded : ['id'];
        }
        return ['id'];
    }

    /**
     * URL Jejak Layar untuk fitur tertentu.
     * Jika belum dikonfigurasi, fallback ke url_jejaklayar dengan path.
     */
    public function urlJejak(string $fitur): ?string
    {
        $col = match($fitur) {
            'bahasa'   => $this->url_jl_bahasa,
            'warisan'  => $this->url_jl_warisan,
            'kesenian' => $this->url_jl_kesenian,
            'destinasi'=> $this->url_jl_destinasi,
            'artikel'  => $this->url_jl_artikel,
            'pustaka'  => $this->url_jl_pustaka,
            default    => null,
        };

        if ($col) return $col;

        // Fallback: url_jejaklayar + path
        $base = rtrim($this->url_jejaklayar ?? '', '/');
        if (!$base) return '#';

        return match($fitur) {
            'bahasa'   => $base . '/bahasa',
            'warisan'  => $base . '/warisan-budaya',
            'kesenian' => $base . '/kesenian',
            'destinasi'=> $base . '/destinasi',
            'artikel'  => $base . '/artikel',
            'pustaka'  => $base . '/pustaka',
            default    => $base,
        };
    }

    /**
     * Helper: URL publik gambar hero untuk halaman tertentu.
     * Mengembalikan null jika belum diset.
     */
    public function heroUrl(string $halaman): ?string
    {
        $col = match($halaman) {
            'profil'         => $this->hero_profil_path,
            'berita'         => $this->hero_berita_path,
            'kontak'         => $this->hero_kontak_path,
            'galeri'         => $this->hero_galeri_path,
            'lam-kecamatan'  => $this->hero_lam_kecamatan_path ?: $this->hero_profil_path,
            'hukum-adat'     => $this->hero_hukum_adat_path    ?: $this->hero_profil_path,
            'tokoh-adat'     => $this->hero_tokoh_adat_path    ?: $this->hero_profil_path,
            'gelar-adat'     => $this->hero_gelar_adat_path    ?: $this->hero_profil_path,
            'agenda'         => $this->hero_agenda_path        ?: $this->hero_berita_path,
            'dokumen'        => $this->hero_dokumen_path       ?: $this->hero_profil_path,
            'permohonan'     => $this->hero_permohonan_path    ?: $this->hero_kontak_path,
            'pendidikan'     => $this->hero_pendidikan_path    ?: $this->hero_berita_path,
            'cari'           => $this->hero_cari_path          ?: $this->hero_profil_path,
            default          => null,
        };
        return $col ? \Illuminate\Support\Facades\Storage::url($col) : null;
    }
}
