<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $image_path
 * @property string $alt_text
 * @property int    $urutan
 * @property bool   $is_active
 */
class BackgroundSlide extends Model
{
    protected $table = 'background_slides';

    protected $fillable = [
        'image_path',
        'alt_text',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'urutan'    => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Slide aktif, terurut ascending.
     * Gunakan: BackgroundSlide::aktif()->get()
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}
