<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Model berita/artikel situs LAM Bengkalis.
 *
 * FK:
 *   - berita_kategori_id → BeritaKategori (restrict delete)
 *   - penulis_id         → User (null on delete)
 *
 * Workflow status: draft → published
 *
 * @property int              $id
 * @property string           $judul
 * @property string           $slug
 * @property int              $berita_kategori_id
 * @property string           $konten
 * @property string|null      $thumbnail
 * @property string|null      $excerpt
 * @property string           $status           'draft' | 'published'
 * @property Carbon|null      $tanggal_publish
 * @property int|null         $penulis_id
 * @property int              $jumlah_dilihat
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 * @property-read BeritaKategori $kategori
 * @property-read User|null      $penulis
 */
class Berita extends Model
{
    protected $table = 'berita';

    // ── Constants ────────────────────────────────────────────────────────────

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';

    /** @var list<string> */
    public const STATUSES = [self::STATUS_DRAFT, self::STATUS_PUBLISHED];

    // ── Mass Assignment ───────────────────────────────────────────────────────

    protected $fillable = [
        'judul',
        'slug',
        'berita_kategori_id',
        'konten',
        'thumbnail',
        'excerpt',
        'status',
        'tanggal_publish',
        'penulis_id',
        'jumlah_dilihat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publish' => 'datetime',
            'jumlah_dilihat'  => 'integer',
        ];
    }

    // ── Model Events ─────────────────────────────────────────────────────────

    /**
     * Auto-generate slug dari judul jika belum diisi.
     * Auto-generate excerpt dari konten jika belum diisi.
     */
    protected static function booted(): void
    {
        static::creating(function (Berita $berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
            if (empty($berita->excerpt)) {
                $berita->excerpt = Str::limit(strip_tags($berita->konten), 200);
            }
        });

        static::updating(function (Berita $berita) {
            if (empty($berita->excerpt)) {
                $berita->excerpt = Str::limit(strip_tags($berita->konten), 200);
            }
        });
    }

    // ── Relasi ───────────────────────────────────────────────────────────────

    /**
     * Kategori berita ini.
     * belongsTo — FK: berita_kategori_id
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(BeritaKategori::class, 'berita_kategori_id');
    }

    /**
     * User (editor) yang menulis/mempublish berita ini.
     * belongsTo — FK: penulis_id (nullable, null on delete)
     */
    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Hanya berita yang sudah dipublish.
     */
    public function scopePublished(Builder $query): Builder
    {
        // Menambahkan buffer 24 jam untuk mengatasi masalah zona waktu (timezone) 
        // antara PHP dan Database yang sering terjadi di shared hosting.
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->where(fn (Builder $q) => $q
                         ->whereNull('tanggal_publish')
                         ->orWhere('tanggal_publish', '<=', now()->addHours(24))
                     );
    }

    /**
     * Hanya berita draft.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Urutkan berita dari terbaru (berdasarkan tanggal_publish).
     */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('tanggal_publish');
    }

    /**
     * Filter berdasarkan kategori slug.
     */
    public function scopeKategori(Builder $query, string $slugKategori): Builder
    {
        return $query->whereHas('kategori', fn (Builder $q) =>
            $q->where('slug', $slugKategori)
        );
    }

    /**
     * Lookup berdasarkan slug (publik — hanya published).
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Tambah view counter secara atomik (race-condition safe).
     */
    public function tambahView(): void
    {
        $this->increment('jumlah_dilihat');
    }

    /**
     * Publish berita sekarang.
     */
    public function publish(): bool
    {
        return $this->update([
            'status'          => self::STATUS_PUBLISHED,
            'tanggal_publish' => $this->tanggal_publish ?? now(),
        ]);
    }

    /**
     * Kembalikan ke draft.
     */
    public function unpublish(): bool
    {
        return $this->update(['status' => self::STATUS_DRAFT]);
    }

    /**
     * Apakah berita sudah dipublish dan tidak terjadwal di masa depan.
     */
    public function sudahPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && ($this->tanggal_publish === null || $this->tanggal_publish->lte(now()->addHours(24)));
    }
}
