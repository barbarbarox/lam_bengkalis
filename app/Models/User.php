<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laragear\TwoFactor\TwoFactorAuthentication;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements TwoFactorAuthenticatable, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, TwoFactorAuthentication;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    // ── Constants ───────────────────────────────────────────────────────────

    /** Nilai enum role yang tersedia di kolom `role`. */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_EDITOR      = 'editor';

    /** @var list<string> Semua nilai enum role. */
    public const ROLES = [self::ROLE_SUPER_ADMIN, self::ROLE_EDITOR];

    // ── Casts ────────────────────────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Hanya user yang aktif dan boleh login.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter berdasarkan role.
     */
    public function scopeRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    // ── Relasi ───────────────────────────────────────────────────────────────

    /**
     * Berita yang ditulis/dipublish oleh user ini.
     */
    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'penulis_id');
    }
}
