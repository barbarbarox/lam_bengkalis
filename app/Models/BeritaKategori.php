<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Kategori berita. Slug digunakan di URL publik.
 *
 * @property int    $id
 * @property string $nama
 * @property string $slug
 * @property string|null $deskripsi
 */
class BeritaKategori extends Model
{
    protected $table = 'berita_kategori';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    /**
     * Semua berita dalam kategori ini.
     */
    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'berita_kategori_id');
    }

    /**
     * Berita yang sudah dipublish dalam kategori ini.
     */
    public function beritaPublished(): HasMany
    {
        return $this->hasMany(Berita::class, 'berita_kategori_id')
                    ->where('status', Berita::STATUS_PUBLISHED)
                    ->latest('tanggal_publish');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Lookup berdasarkan slug.
     * Contoh: BeritaKategori::bySlug('adat-budaya')
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }
}
