<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class HukumAdat extends Model
{
    protected $table = 'hukum_adat';

    public const JENIS = [
        'peraturan_adat'   => 'Peraturan Adat',
        'keputusan'        => 'Keputusan',
        'pedoman'          => 'Pedoman',
        'fatwa_adat'       => 'Fatwa Adat',
        'undang_undang_adat' => 'Undang-Undang Adat',
        'lainnya'          => 'Lainnya',
    ];

    protected $fillable = [
        'judul', 'slug', 'jenis', 'nomor_dokumen', 'tahun',
        'konten', 'file_path', 'ukuran_file', 'thumbnail',
        'ringkasan', 'is_aktif', 'jumlah_unduh',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif'     => 'boolean',
            'tahun'        => 'integer',
            'ukuran_file'  => 'integer',
            'jumlah_unduh' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug($m->judul);
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

    public function getUkuranHumanAttribute(): string
    {
        $bytes = $this->ukuran_file ?? 0;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function tambahUnduh(): void
    {
        $this->increment('jumlah_unduh');
    }
}
