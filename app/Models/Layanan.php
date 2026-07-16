<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk daftar layanan LAM Bengkalis yang ditampilkan di beranda
 * dan navigasi situs.
 *
 * @property int         $id
 * @property string      $nama
 * @property string|null $deskripsi
 * @property string|null $icon      Heroicon name (mis. "heroicon-o-building-library")
 * @property string|null $url       Link eksternal atau internal
 * @property string|null $warna     Hex warna aksen kartu
 * @property int         $urutan
 * @property bool        $is_aktif
 */
class Layanan extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'jenis_icon',
        'icon',
        'image',
        'url',
        'warna',
        'urutan',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan'   => 'integer',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function scopeTerurut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }
}
