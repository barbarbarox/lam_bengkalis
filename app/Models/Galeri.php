<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk galeri foto LAM Bengkalis.
 *
 * @property int         $id
 * @property string|null $judul
 * @property string|null $deskripsi
 * @property string      $foto_path   Path relatif di storage
 * @property int         $urutan
 * @property bool        $is_aktif
 */
class Galeri extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'foto_path',
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
        return $query->orderBy('urutan')->orderBy('id');
    }
}
