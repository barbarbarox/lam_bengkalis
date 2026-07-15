<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Pengaduan / pesan kontak dari publik.
 *
 * Workflow status: baru → diproses → selesai
 *
 * @property int          $id
 * @property string       $nama_pengadu
 * @property string       $email
 * @property string|null  $no_telp
 * @property string       $subjek
 * @property string       $isi_aduan
 * @property string       $status          'baru' | 'diproses' | 'selesai'
 * @property string|null  $catatan_admin
 * @property float        $recaptcha_score
 * @property string       $ip_address
 * @property Carbon       $created_at
 * @property Carbon       $updated_at
 */
class KontakAduan extends Model
{
    protected $table = 'kontak_aduan';

    // ── Constants ────────────────────────────────────────────────────────────

    public const STATUS_BARU      = 'baru';
    public const STATUS_DIPROSES  = 'diproses';
    public const STATUS_SELESAI   = 'selesai';

    /** @var list<string> */
    public const STATUSES = [self::STATUS_BARU, self::STATUS_DIPROSES, self::STATUS_SELESAI];

    /**
     * Skor minimum reCAPTCHA agar pengaduan diterima.
     * Nilai di bawah ini sebaiknya ditolak atau dimasukkan ke antrian review.
     */
    public const RECAPTCHA_THRESHOLD = 0.5;

    // ── Mass Assignment ───────────────────────────────────────────────────────

    protected $fillable = [
        'nama_pengadu',
        'email',
        'no_telp',
        'subjek',
        'isi_aduan',
        'status',
        'catatan_admin',
        'recaptcha_score',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'recaptcha_score' => 'float',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Filter berdasarkan status.
     * Contoh: KontakAduan::status('baru')->latest()->get()
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Hanya pengaduan baru yang belum diproses.
     */
    public function scopeBaru(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_BARU);
    }

    /**
     * Hanya pengaduan yang sedang diproses admin.
     */
    public function scopeDiproses(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DIPROSES);
    }

    /**
     * Hanya pengaduan yang sudah selesai ditangani.
     */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    /**
     * Filter pengaduan dari IP address tertentu (untuk rate-limit audit).
     */
    public function scopeDariIp(Builder $query, string $ipAddress): Builder
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Pengaduan dengan skor reCAPTCHA mencurigakan (kemungkinan bot).
     */
    public function scopeMencurigakan(Builder $query): Builder
    {
        return $query->where('recaptcha_score', '<', self::RECAPTCHA_THRESHOLD);
    }

    // ── Helpers / Workflow ───────────────────────────────────────────────────

    /**
     * Tandai pengaduan sedang diproses.
     */
    public function proses(string $catatan = null): bool
    {
        return $this->update([
            'status'         => self::STATUS_DIPROSES,
            'catatan_admin'  => $catatan ?? $this->catatan_admin,
        ]);
    }

    /**
     * Tandai pengaduan sudah selesai ditangani.
     */
    public function selesaikan(string $catatan = null): bool
    {
        return $this->update([
            'status'        => self::STATUS_SELESAI,
            'catatan_admin' => $catatan ?? $this->catatan_admin,
        ]);
    }

    /**
     * Apakah pengaduan ini kemungkinan dari bot (skor rendah).
     */
    public function kemungkinanBot(): bool
    {
        return $this->recaptcha_score < self::RECAPTCHA_THRESHOLD;
    }

    /**
     * Label warna untuk badge status di Filament.
     * Mengembalikan nama warna Tailwind/Filament.
     *
     * @return 'warning'|'info'|'success'
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_BARU     => 'warning',
            self::STATUS_DIPROSES => 'info',
            self::STATUS_SELESAI  => 'success',
            default               => 'gray',
        };
    }
}
