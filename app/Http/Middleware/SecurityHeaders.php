<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SecurityHeaders
 *
 * Middleware global yang menyuntikkan header keamanan HTTP pada setiap respons
 * dan memaksa HTTPS di environment production.
 */
class SecurityHeaders
{
    /**
     * Daftar header keamanan yang akan ditambahkan pada setiap respons.
     *
     * @var array<string, string>
     */
    private array $securityHeaders = [
        'X-Frame-Options'           => 'DENY',
        'X-Content-Type-Options'    => 'nosniff',
        'Referrer-Policy'           => 'strict-origin-when-cross-origin',
        'X-XSS-Protection'          => '1; mode=block',
        'Permissions-Policy'        => 'camera=(), microphone=(), geolocation=()',
        'Content-Security-Policy'   => "default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://fonts.googleapis.com https://cdn.jsdelivr.net https://static.cloudflareinsights.com; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net; "
            . "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net; "
            . "img-src 'self' data: blob: https:; "
            . "frame-src https://www.google.com; "
            . "connect-src 'self' blob: https://lambengkalis.id https://static.cloudflareinsights.com wss://lambengkalis.id ws://lambengkalis.id; "
            . "worker-src 'self' blob:; "
            . "object-src 'none'; "
            . "base-uri 'self'; "
            . "form-action 'self';",
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Paksa HTTPS di environment production
        if (app()->environment('production') && ! $request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        $response = $next($request);

        // Panel admin (Filament/Livewire) membutuhkan CSP yang lebih longgar
        // agar komponen file-upload, preview gambar, dan WebSocket bisa bekerja
        $isAdminRoute = str_starts_with($request->path(), 'pentadbir');

        foreach ($this->securityHeaders as $header => $value) {
            if ($header === 'Content-Security-Policy' && $isAdminRoute) {
                // CSP khusus admin: izinkan koneksi Livewire, blob, dan storage
                // connect-src harus mengizinkan http: maupun https: karena APP_URL
                // mungkin masih http:// di server (tergantung konfigurasi hosting).
                $adminCsp = "default-src 'self'; "
                    . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://www.google.com https://www.gstatic.com https://static.cloudflareinsights.com; "
                    . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net; "
                    . "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net; "
                    . "img-src 'self' data: blob: http: https:; "
                    . "frame-src 'self' https://www.google.com; "
                    . "connect-src 'self' blob: http: https: wss: ws:; "
                    . "worker-src 'self' blob:; "
                    . "object-src 'none'; "
                    . "base-uri 'self'; "
                    . "form-action 'self' http: https:;";
                $response->headers->set($header, $adminCsp);
            } else {
                $response->headers->set($header, $value);
            }
        }

        // Hapus header server yang mengekspos informasi
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
