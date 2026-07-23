<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PermohonanInformasi extends Model
{
    protected $table = 'permohonan_informasi';

    public const STATUS = [
        'baru'      => 'Baru',
        'diproses'  => 'Diproses',
        'selesai'   => 'Selesai',
        'ditolak'   => 'Ditolak',
    ];

    protected $fillable = [
        'nomor_tiket', 'nama_pemohon', 'email', 'no_hp',
        'instansi', 'jenis_informasi', 'uraian_permohonan',
        'status', 'catatan_admin', 'ip_address',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->nomor_tiket)) {
                $m->nomor_tiket = 'PI-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
            }
        });
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeBaru(Builder $query): Builder
    {
        return $query->where('status', 'baru');
    }

    public function getLabelStatusAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getWarnaBadgeAttribute(): string
    {
        return match($this->status) {
            'baru'     => '#1565C0',
            'diproses' => '#E65100',
            'selesai'  => '#1B5E20',
            'ditolak'  => '#B71C1C',
            default    => '#777',
        };
    }
}
