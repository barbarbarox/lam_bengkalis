<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $nama_ketua
 * @property string      $jabatan
 * @property string|null $foto
 * @property string      $isi_sambutan
 * @property bool        $is_active
 * @property int|null    $periode_mulai
 * @property int|null    $periode_selesai
 */
class SambutanBph extends Model
{
    protected $table = 'sambutan_bph';

    protected $fillable = [
        'nama_ketua',
        'jabatan',
        'foto',
        'isi_sambutan',
        'is_active',
        'periode_mulai',
        'periode_selesai',
    ];

    protected function casts(): array
    {
        return [
            'is_active'      => 'boolean',
            'periode_mulai'  => 'integer',
            'periode_selesai' => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Ambil sambutan yang sedang aktif/ditampilkan.
     * Biasanya hanya satu, tapi model mendukung multiple.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true)->latest();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * String periode jabatan yang mudah ditampilkan.
     * Contoh output: "2022–2027" atau "2022–sekarang"
     */
    public function periodeLabel(): string
    {
        $mulai   = $this->periode_mulai ?? '?';
        $selesai = $this->periode_selesai ?? 'sekarang';

        return "{$mulai}–{$selesai}";
    }
}
