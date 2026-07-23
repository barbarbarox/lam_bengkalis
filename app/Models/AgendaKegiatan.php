<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AgendaKegiatan extends Model
{
    protected $table = 'agenda_kegiatan';

    public const JENIS = [
        'internal'   => 'Internal',
        'publik'     => 'Publik',
        'seremonial' => 'Seremonial',
        'pelatihan'  => 'Pelatihan',
        'rapat'      => 'Rapat',
        'lainnya'    => 'Lainnya',
    ];

    public const STATUS = [
        'akan_datang' => 'Akan Datang',
        'berlangsung' => 'Berlangsung',
        'selesai'     => 'Selesai',
        'dibatalkan'  => 'Dibatalkan',
    ];

    protected $fillable = [
        'judul', 'slug', 'tanggal_mulai', 'tanggal_selesai',
        'waktu_mulai', 'waktu_selesai', 'lokasi',
        'deskripsi', 'konten', 'thumbnail', 'jenis', 'status',
        'penyelenggara', 'kuota', 'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'is_aktif'        => 'boolean',
            'kuota'           => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug($m->judul . '-' . now()->format('Ymd'));
            }
        });

        // Auto update status berdasarkan tanggal
        static::retrieved(function (self $m) {
            $today = now()->startOfDay();
            if ($m->status === 'akan_datang' && $m->tanggal_mulai && $m->tanggal_mulai < $today) {
                if ($m->tanggal_selesai && $m->tanggal_selesai < $today) {
                    $m->updateQuietly(['status' => 'selesai']);
                } elseif (!$m->tanggal_selesai) {
                    $m->updateQuietly(['status' => 'selesai']);
                } else {
                    $m->updateQuietly(['status' => 'berlangsung']);
                }
            }
        });
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function scopeAkanDatang(Builder $query): Builder
    {
        return $query->where('is_aktif', true)
                     ->whereIn('status', ['akan_datang', 'berlangsung'])
                     ->where('tanggal_mulai', '>=', now()->startOfDay()->subDays(1))
                     ->orderBy('tanggal_mulai');
    }

    public function scopeArsip(Builder $query): Builder
    {
        return $query->where('is_aktif', true)
                     ->where('status', 'selesai')
                     ->orderByDesc('tanggal_mulai');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getLabelStatusAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getLabelJenisAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? $this->jenis;
    }

    public function getRentangTanggalAttribute(): string
    {
        $mulai = $this->tanggal_mulai?->translatedFormat('d M Y');
        if (!$this->tanggal_selesai || $this->tanggal_selesai->eq($this->tanggal_mulai)) {
            return $mulai ?? '-';
        }
        return $mulai . ' – ' . $this->tanggal_selesai->translatedFormat('d M Y');
    }

    /** Warna badge berdasarkan status */
    public function getWarnaBadgeAttribute(): string
    {
        return match($this->status) {
            'akan_datang' => '#1B5E20',
            'berlangsung' => '#E65100',
            'selesai'     => '#555555',
            'dibatalkan'  => '#B71C1C',
            default       => '#777777',
        };
    }
}
