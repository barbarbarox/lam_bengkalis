<?php

namespace App\Providers;

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
    }
}
