<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Anggota struktur organisasi LAM Bengkalis.
 *
 * @property int         $id
 * @property string      $nama
 * @property string      $jabatan
 * @property string      $kategori        'MKA' | 'DPH' | 'Bidang' | 'DKA' | 'Pembimbing' | 'Penasehat'
 * @property string|null $nama_bidang     Nama bidang (hanya untuk kategori 'Bidang')
 * @property string      $tingkat_jabatan 'pimpinan' | 'anggota'
 * @property string|null $foto
 * @property int         $urutan
 * @property string|null $periode
 * @property bool        $is_active
 */
class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasi';

    // ── Konstanta Kategori ────────────────────────────────────────────────────

    public const KATEGORI_MKA        = 'MKA';
    public const KATEGORI_DPH        = 'DPH';
    public const KATEGORI_BIDANG     = 'Bidang';
    public const KATEGORI_DKA        = 'DKA';
    public const KATEGORI_PEMBIMBING = 'Pembimbing';
    public const KATEGORI_PENASEHAT  = 'Penasehat';

    /** @var list<string> */
    public const KATEGORI_OPTIONS = [
        self::KATEGORI_MKA,
        self::KATEGORI_DPH,
        self::KATEGORI_BIDANG,
        self::KATEGORI_DKA,
        self::KATEGORI_PEMBIMBING,
        self::KATEGORI_PENASEHAT,
    ];

    // ── Konstanta Tingkat Jabatan ─────────────────────────────────────────────

    public const TINGKAT_PIMPINAN = 'pimpinan';
    public const TINGKAT_ANGGOTA  = 'anggota';

    // ── Mass Assignment ───────────────────────────────────────────────────────

    /** @var list<string> */
    protected $fillable = [
        'nama',
        'jabatan',
        'kategori',
        'nama_bidang',
        'tingkat_jabatan',
        'foto',
        'urutan',
        'periode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'      => 'boolean',
            'urutan'         => 'integer',
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
     * Filter berdasarkan kategori.
     */
    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Filter anggota dengan tingkat_jabatan = 'pimpinan'.
     */
    public function scopePimpinan(Builder $query): Builder
    {
        return $query->where('tingkat_jabatan', self::TINGKAT_PIMPINAN);
    }

    /**
     * Filter anggota dengan tingkat_jabatan = 'anggota'.
     */
    public function scopeAnggotaBiasa(Builder $query): Builder
    {
        return $query->where('tingkat_jabatan', self::TINGKAT_ANGGOTA);
    }

    /**
     * Filter berdasarkan nama_bidang (untuk kategori 'Bidang').
     */
    public function scopeNamaBidang(Builder $query, string $namaBidang): Builder
    {
        return $query->where('nama_bidang', $namaBidang);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

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

    /**
     * Ambil anggota DKA yang aktif.
     */
    public static function getDka(): Collection
    {
        return static::aktif()->kategori(self::KATEGORI_DKA)->get();
    }

    /**
     * Ambil semua Bidang dikelompokkan per nama_bidang.
     *
     * Setiap item dalam array hasil berisi:
     *   [
     *     'nama'        => string (nama bidang),
     *     'pimpinan'    => StrukturOrganisasi|null,
     *     'anggota_lain' => Collection<StrukturOrganisasi>
     *   ]
     *
     * @return array<int, array{nama: string, pimpinan: static|null, anggota_lain: Collection}>
     */
    public static function getBidangGrouped(): array
    {
        $semua = static::aktif()
            ->kategori(self::KATEGORI_BIDANG)
            ->orderBy('nama_bidang')
            ->orderBy('urutan')
            ->get();

        // Kelompokkan berdasarkan nama_bidang
        $grouped = $semua->groupBy('nama_bidang');

        $result = [];

        foreach ($grouped as $namaBidang => $anggotaList) {
            // Pisahkan pimpinan dan anggota biasa
            $pimpinan    = $anggotaList->firstWhere('tingkat_jabatan', self::TINGKAT_PIMPINAN);
            $anggotaLain = $anggotaList->where('tingkat_jabatan', self::TINGKAT_ANGGOTA)->values();

            $result[] = [
                'nama'         => (string) $namaBidang,
                'pimpinan'     => $pimpinan,
                'anggota_lain' => $anggotaLain,
            ];
        }

        return $result;
    }

    // ── Accessor Helpers ─────────────────────────────────────────────────────

    /**
     * Ambil inisial nama untuk placeholder avatar.
     * Contoh: "Datuk H. Ahmad Toha" → "AT"
     */
    public function getInisialAttribute(): string
    {
        $kata = explode(' ', trim($this->nama));
        // Skip gelar umum di awal
        $gelar = ['datuk', 'puan', 'h.', 'hj.', 'dr.', 'ir.', 'prof.', 'drs.', 'seri', 'ir'];
        $filtered = array_filter($kata, fn($k) => !in_array(strtolower($k), $gelar));
        $filtered = array_values($filtered);

        if (empty($filtered)) {
            return strtoupper(substr($this->nama, 0, 2));
        }

        if (count($filtered) === 1) {
            return strtoupper(substr($filtered[0], 0, 2));
        }

        return strtoupper(substr($filtered[0], 0, 1) . substr($filtered[1], 0, 1));
    }
}
