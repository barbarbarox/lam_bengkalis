<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class GelarAdat extends Model
{
    protected $table = 'gelar_adat';

    public const JENIS = [
        'gelar_adat'  => 'Gelar Adat',
        'penghargaan' => 'Penghargaan',
        'kehormatan'  => 'Kehormatan',
        'pusaka'      => 'Pusaka',
    ];

    protected $fillable = [
        'nama_gelar', 'slug', 'jenis', 'tingkatan',
        'deskripsi', 'makna', 'syarat_pemberian',
        'penerima_terkini', 'is_aktif', 'urutan',
    ];

    protected function casts(): array
    {
        return ['is_aktif' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug($m->nama_gelar);
            }
        });
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function scopeJenis(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis', $jenis);
    }

    public function getLabelJenisAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? $this->jenis;
    }
}
