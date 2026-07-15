<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Anggota struktur organisasi LAM Bengkalis.
 *
 * @property int         $id
 * @property string      $nama
 * @property string      $jabatan
 * @property string      $kategori    'MKA' | 'DPH'
 * @property string|null $foto
 * @property int         $urutan
 * @property string|null $periode
 * @property bool        $is_active
 */
class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasi';

    /** Nilai enum kategori yang valid. */
    public const KATEGORI_MKA = 'MKA';
    public const KATEGORI_DPH = 'DPH';

    /** @var list<string> */
    public const KATEGORI_OPTIONS = [self::KATEGORI_MKA, self::KATEGORI_DPH];

    protected $fillable = [
        'nama',
        'jabatan',
        'kategori',
        'foto',
        'urutan',
        'periode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'urutan'    => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Hanya anggota yang aktif, terurut berdasarkan urutan.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }

    /**
     * Filter berdasarkan kategori (MKA atau DPH).
     */
    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Ambil semua anggota aktif dikelompokkan per kategori.
     *
     * Contoh output:
     *   ['MKA' => Collection, 'DPH' => Collection]
     *
     * @return array<string, Collection<int, static>>
     */
    public static function groupedByKategori(): array
    {
        return static::aktif()
            ->get()
            ->groupBy('kategori')
            ->toArray();
    }

    /**
     * Ambil anggota MKA yang aktif.
     */
    public static function getMka(): Collection
    {
        return static::aktif()->kategori(self::KATEGORI_MKA)->get();
    }

    /**
     * Ambil anggota DPH yang aktif.
     */
    public static function getDph(): Collection
    {
        return static::aktif()->kategori(self::KATEGORI_DPH)->get();
    }
}
