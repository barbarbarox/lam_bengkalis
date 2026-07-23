<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PendidikanPelatihan extends Model
{
    protected $table = 'pendidikan_pelatihan';

    public const JENIS = [
        'pelatihan'        => 'Pelatihan',
        'workshop'         => 'Workshop',
        'seminar'          => 'Seminar',
        'kursus'           => 'Kursus',
        'beasiswa'         => 'Beasiswa',
        'pendidikan_formal'=> 'Pendidikan Formal',
        'lainnya'          => 'Lainnya',
    ];

    protected $fillable = [
        'judul', 'slug', 'jenis', 'penyelenggara',
        'tanggal_mulai', 'tanggal_selesai', 'lokasi',
        'kuota', 'biaya', 'deskripsi', 'konten', 'thumbnail',
        'link_pendaftaran', 'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'is_aktif'        => 'boolean',
            'kuota'           => 'integer',
            'biaya'           => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug($m->judul . '-' . now()->format('Y'));
            }
        });
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function scopeMendatang(Builder $query): Builder
    {
        return $query->where('is_aktif', true)
                     ->where(fn($q) => $q
                         ->whereNull('tanggal_mulai')
                         ->orWhere('tanggal_mulai', '>=', now()->startOfDay())
                     )
                     ->orderBy('tanggal_mulai');
    }

    public function getLabelJenisAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? $this->jenis;
    }

    public function getBiayaHumanAttribute(): string
    {
        if (!$this->biaya || $this->biaya == 0) return 'Gratis';
        return 'Rp ' . number_format($this->biaya, 0, ',', '.');
    }
}
