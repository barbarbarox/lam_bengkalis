<?php

namespace App\Providers;

use App\Models\Layanan;
use App\Models\SiteSetting;
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
