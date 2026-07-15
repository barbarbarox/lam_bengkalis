<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * PentadbirSessionTimeout
 *
 * Logout otomatis admin jika tidak ada aktivitas selama N menit.
 * Timeout default: 30 menit (configurable via env ADMIN_SESSION_TIMEOUT).
 *
 * Mekanisme:
 *   1. Setiap request panel dicek timestamp aktivitas terakhir.
 *   2. Jika sudah melewati timeout → logout + invalidate session.
 *   3. Jika masih aktif → perbarui timestamp.
 */
class PentadbirSessionTimeout
{
    /**
     * Durasi idle timeout dalam menit (default 30).
     * Set ADMIN_SESSION_TIMEOUT=60 di .env untuk override.
     */
    private int $timeoutMinutes;

    /** Key yang digunakan di session untuk menyimpan timestamp. */
    private const SESSION_KEY = '_pentadbir_last_activity';

    public function __construct()
    {
        $this->timeoutMinutes = (int) config('app.admin_session_timeout', 30);
    }

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya aktif untuk guard web yang sudah login
        if (! Auth::check()) {
            return $next($request);
        }

        $lastActivity = $request->session()->get(self::SESSION_KEY);

        if ($lastActivity !== null) {
            $idleSeconds = now()->timestamp - $lastActivity;

            if ($idleSeconds > ($this->timeoutMinutes * 60)) {
                // Idle timeout tercapai — logout dan invalidate session
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->to('/pentadbir/login')
                    ->with('status', 'Sesi Anda telah berakhir karena tidak aktif. Silakan login kembali.');
            }
        }

        // Perbarui timestamp aktivitas terakhir
        $request->session()->put(self::SESSION_KEY, now()->timestamp);

        return $next($request);
    }
}
