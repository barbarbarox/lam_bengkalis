<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * PentadbirLoginRateLimit
 *
 * Membatasi percobaan login ke panel /pentadbir:
 *   - Maksimum 5 percobaan per menit
 *   - Kunci berdasarkan kombinasi IP + email yang dicoba
 *   - Lockout selama 60 detik setelah limit tercapai
 *   - Mengembalikan 429 Too Many Requests dengan header Retry-After
 */
class PentadbirLoginRateLimit
{
    public function __construct(
        private readonly RateLimiter $limiter
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        // Hanya aktif untuk POST ke endpoint login
        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        $key = $this->resolveKey($request);

        if ($this->limiter->tooManyAttempts($key, maxAttempts: 5)) {
            $seconds = $this->limiter->availableIn($key);

            return response()->json([
                'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ], Response::HTTP_TOO_MANY_REQUESTS)
                ->withHeaders([
                    'Retry-After'       => $seconds,
                    'X-RateLimit-Limit' => 5,
                    'X-RateLimit-Reset' => now()->addSeconds($seconds)->timestamp,
                ]);
        }

        $this->limiter->hit($key, decaySeconds: 60);

        $response = $next($request);

        // Jika login berhasil (redirect ke panel), reset counter
        if ($response->isRedirect() && ! $response->isClientError()) {
            $this->limiter->clear($key);
        }

        return $response;
    }

    /**
     * Kunci rate limiter berdasarkan IP + email (lowercase, dihash).
     * Menggunakan SHA-256 agar email tidak tersimpan di cache plaintext.
     */
    private function resolveKey(Request $request): string
    {
        $email = Str::lower(trim((string) $request->input('email', '')));
        $ip    = $request->ip() ?? '0.0.0.0';

        return 'pentadbir_login:' . hash('sha256', $ip . '|' . $email);
    }
}
