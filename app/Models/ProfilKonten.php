<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Halaman konten statis yang dikelola CMS.
 * Lookup via slug: ProfilKonten::bySlug('sejarah-lam')
 *
 * @property int         $id
 * @property string      $slug
 * @property string      $judul
 * @property string      $konten
 * @property string|null $meta_deskripsi
 * @property bool        $is_active
 */
class ProfilKonten extends Model
{
    protected $table = 'profil_konten';

    protected $fillable = [
        'slug',
        'judul',
        'konten',
        'meta_deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Hanya konten yang aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Cari konten berdasarkan slug (publik, hanya aktif).
     *
     * Contoh: ProfilKonten::bySlug('sejarah-lam')
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug)->where('is_active', true);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Ambil satu konten aktif berdasarkan slug, atau null jika tidak ditemukan.
     */
    public static function findBySlug(string $slug): ?static
    {
        return static::bySlug($slug)->first();
    }

    /**
     * Ambil satu konten aktif berdasarkan slug, atau lempar 404.
     */
    public static function findBySlugOrFail(string $slug): static
    {
        return static::bySlug($slug)->firstOrFail();
    }
}
