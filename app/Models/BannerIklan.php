<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property string      $image_path
 * @property string|null $link_url
 * @property string|null $alt_text
 * @property int         $urutan
 * @property bool        $is_active
 * @property Carbon|null $tanggal_mulai
 * @property Carbon|null $tanggal_selesai
 */
class BannerIklan extends Model
{
    protected $table = 'banner_iklan';

    protected $fillable = [
        'image_path',
        'link_url',
        'alt_text',
        'urutan',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'urutan'          => 'integer',
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Banner yang aktif dan dalam periode tayang saat ini.
     *
     * Aturan:
     *   - is_active = true
     *   - tanggal_mulai IS NULL atau sudah lewat/hari ini
     *   - tanggal_selesai IS NULL atau belum lewat/hari ini
     */
    public function scopeAktifSekarang(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->where('is_active', true)
            ->where(fn (Builder $q) => $q
                ->whereNull('tanggal_mulai')
                ->orWhere('tanggal_mulai', '<=', $today)
            )
            ->where(fn (Builder $q) => $q
                ->whereNull('tanggal_selesai')
                ->orWhere('tanggal_selesai', '>=', $today)
            )
            ->orderBy('urutan');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Apakah banner sedang dalam periode tayang saat ini.
     */
    public function sedangTayang(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = now()->startOfDay();

        if ($this->tanggal_mulai && $this->tanggal_mulai->gt($today)) {
            return false;
        }

        if ($this->tanggal_selesai && $this->tanggal_selesai->lt($today)) {
            return false;
        }

        return true;
    }
}
