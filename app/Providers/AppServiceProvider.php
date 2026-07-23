<?php

namespace App\Providers;

use App\Models\Layanan;
use App\Models\SiteSetting;
use App\Models\StrukturOrganisasi;
use App\Observers\StrukturOrganisasiObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Durasi idle timeout panel admin (menit). Default: 30 menit.
        // Override di .env: ADMIN_SESSION_TIMEOUT=60
        config([
            'app.admin_session_timeout' => (int) env('ADMIN_SESSION_TIMEOUT', 30),
        ]);

        // Register observer untuk invalidasi cache publik saat data struktur berubah
        StrukturOrganisasi::observe(StrukturOrganisasiObserver::class);

        // Paksa skema HTTPS di production (mengatasi isu canonical & sitemap jika menggunakan Cloudflare)
        if (config('app.env') === 'production' || request()->header('x-forwarded-proto') === 'https' || str_contains(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Bagikan data global ke semua views yang menggunakan layouts.app
        // Ini memungkinkan navbar menampilkan daftar Layanan di semua halaman
        View::composer('layouts.app', function ($view) {
            try {
                if (!isset($view->getData()['layanans'])) {
                    $view->with('layanans', Layanan::aktif()->terurut()->get());
                }
                if (!isset($view->getData()['setting'])) {
                    $view->with('setting', SiteSetting::instance());
                }
            } catch (\Throwable $e) {
                // Jika DB belum siap (misal saat migrate), abaikan
                $view->with('layanans', collect());
            }
        });
    }
}
