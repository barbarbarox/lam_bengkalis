<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LamKecamatan extends Model
{
    protected $table = 'lam_kecamatan';

    protected $fillable = [
        'nama_kecamatan', 'nama_ketua', 'jabatan_ketua', 'alamat',
        'no_telp', 'email', 'foto_ketua_path', 'foto_gedung_path',
        'deskripsi', 'jumlah_nagori', 'is_aktif', 'urutan',
    ];

    protected function casts(): array
    {
        return ['is_aktif' => 'boolean', 'jumlah_nagori' => 'integer'];
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function scopeTerurut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama_kecamatan');
    }
}
