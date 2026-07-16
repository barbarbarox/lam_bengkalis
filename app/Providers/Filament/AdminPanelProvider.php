<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\PentadbirLogin;
use App\Http\Middleware\PentadbirSessionTimeout;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * AdminPanelProvider — Panel Pentadbir LAM Bengkalis
 *
 * Spesifikasi keamanan:
 *   ✅ Path: /pentadbir (bukan /admin default)
 *   ✅ Registrasi publik dinonaktifkan
 *   ✅ Login page kustom dengan rate limiting 5x/menit per IP+email
 *   ✅ 2FA via laragear/two-factor (wajib untuk super_admin)
 *   ✅ Session timeout 30 menit idle (PentadbirSessionTimeout middleware)
 *   ✅ Session ID regenerasi saat login (di PentadbirLogin::authenticate())
 *   ✅ Warna: primary hijau tua #0B4F30, accent/secondary kuning emas #D4AF37
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ── Identitas Panel ───────────────────────────────────────────────
            ->default()
            ->id('pentadbir')
            ->path('pentadbir')
            ->brandName('LAM Bengkalis')
            ->brandLogo(function () {
                $setting = \App\Models\SiteSetting::first();
                if ($setting && $setting->logo_path) {
                    return \Illuminate\Support\Facades\Storage::url($setting->logo_path);
                }
                return asset('images/logo-lam.gif');
            })

            // ── Auth & Keamanan ───────────────────────────────────────────────
            ->login(PentadbirLogin::class)      // login page kustom dengan rate limiting
            ->registration(false)               // ❌ registrasi publik DINONAKTIFKAN
            ->passwordReset()                   // reset password via email (untuk admin)
            ->emailVerification()               // verifikasi email wajib

            // ── Warna Identitas LAM Bengkalis ─────────────────────────────────
            ->colors([
                // Primary: Hijau Tua Melayu (#0B4F30)
                'primary'   => Color::hex('#0B4F30'),
                // Secondary/Accent: Kuning Emas Adat (#D4AF37)
                'secondary' => Color::hex('#D4AF37'),
                // Info, Success, Warning, Danger — turunan dari primary
                'info'      => Color::Cyan,
                'success'   => Color::Emerald,
                'warning'   => Color::Amber,
                'danger'    => Color::Rose,
                'gray'      => Color::Slate,
            ])

            // ── Discovery Resources ───────────────────────────────────────────
            ->discoverResources(
                in:  app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in:  app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in:  app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])

            // ── Middleware Stack ──────────────────────────────────────────────
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // Session timeout: 30 menit idle → auto logout
                PentadbirSessionTimeout::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                // Wajibkan 2FA untuk akun super_admin
                \App\Http\Middleware\RequireTwoFactorForSuperAdmin::class,
            ])

            // ── Navigasi & UX ─────────────────────────────────────────────────
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->favicon(fn () => asset('images/favicon.ico'));
    }
}
