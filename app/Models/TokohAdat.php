<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TokohAdat extends Model
{
    protected $table = 'tokoh_adat';

    protected $fillable = [
        'nama', 'slug', 'gelar_adat', 'jabatan', 'kecamatan',
        'biografi', 'ringkasan', 'foto_path',
        'tahun_lahir', 'tahun_wafat', 'is_aktif', 'urutan',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif'    => 'boolean',
            'tahun_lahir' => 'integer',
            'tahun_wafat' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug($m->nama);
            }
        });
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function scopeTerurut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /** Apakah tokoh ini masih hidup */
    public function getMasihHidupAttribute(): bool
    {
        return is_null($this->tahun_wafat);
    }

    /** Nama lengkap dengan gelar */
    public function getNamaLengkapAttribute(): string
    {
        return $this->gelar_adat
            ? $this->gelar_adat . ' ' . $this->nama
            : $this->nama;
    }
}
