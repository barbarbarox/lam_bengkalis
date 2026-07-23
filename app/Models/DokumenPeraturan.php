<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DokumenPeraturan extends Model
{
    protected $table = 'dokumen_peraturan';

    public const JENIS = [
        'perda'   => 'Peraturan Daerah (PERDA)',
        'perbup'  => 'Peraturan Bupati (PERBUP)',
        'sk'      => 'Surat Keputusan (SK)',
        'sop'     => 'Standar Operasional Prosedur (SOP)',
        'ad_art'  => 'AD/ART',
        'panduan' => 'Panduan & Pedoman',
        'laporan' => 'Laporan',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'judul', 'slug', 'jenis', 'nomor', 'tahun',
        'deskripsi', 'file_path', 'mime_type', 'ukuran_file',
        'jumlah_unduh', 'is_aktif',
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

    public function getIsPdfAttribute(): bool
    {
        return in_array($this->mime_type, ['application/pdf', 'pdf'])
            || Str::endsWith(strtolower($this->file_path ?? ''), '.pdf');
    }

    public function tambahUnduh(): void
    {
        $this->increment('jumlah_unduh');
    }
}
