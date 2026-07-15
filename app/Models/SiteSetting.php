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
 * @property int|null    $tahun_berdiri
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
        'favicon_path',
        'alamat',
        'email_kontak',
        'no_telp',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'meta_deskripsi',
        'meta_keywords',
        'teks_footer',
        'tahun_berdiri',
        'embed_peta',
        'url_museum',
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
}
