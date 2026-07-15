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
            . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://fonts.googleapis.com; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net; "
            . "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net; "
            . "img-src 'self' data: https:; "
            . "frame-src https://www.google.com; "
            . "connect-src 'self'; "
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

        // Tambahkan header keamanan pada setiap respons
        foreach ($this->securityHeaders as $header => $value) {
            $response->headers->set($header, $value);
        }

        // Hapus header server yang mengekspos informasi
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
