<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RequireTwoFactorForSuperAdmin
 *
 * Middleware yang memaksa akun super_admin untuk mengaktifkan 2FA.
 *
 * Jika super_admin belum mengaktifkan 2FA:
 *   → Diarahkan ke halaman profil dengan pesan wajib setup 2FA.
 *   → Tidak bisa mengakses halaman manapun di panel sebelum 2FA aktif.
 *
 * Editor (role=editor) tidak diwajibkan namun tetap bisa aktifkan 2FA secara sukarela.
 */
class RequireTwoFactorForSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Lewati jika belum login (ditangani Authenticate middleware)
        if ($user === null) {
            return $next($request);
        }

        // Hanya wajibkan untuk super_admin
        if ($user->role !== User::ROLE_SUPER_ADMIN) {
            return $next($request);
        }

        // Cek apakah 2FA sudah diaktifkan via laragear/two-factor
        // hasTwoFactorEnabled() adalah method dari TwoFactorAuthentication trait
        if (method_exists($user, 'hasTwoFactorEnabled') && ! $user->hasTwoFactorEnabled()) {
            // Jangan loop redirect jika sudah di halaman profil/2FA setup
            if (! $request->is('pentadbir/profile*') && ! $request->is('pentadbir/two-factor*')) {
                return redirect()
                    ->to('/pentadbir/two-factor-setup')
                    ->with(
                        'warning',
                        '⚠️ Akun Super Admin wajib mengaktifkan Two-Factor Authentication (2FA). '
                        . 'Aktifkan sekarang sebelum melanjutkan.'
                    );
            }
        }

        return $next($request);
    }
}
