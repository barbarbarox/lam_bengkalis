<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * BlockLegacyAdminPath
 *
 * Mengembalikan 404 standar untuk semua request ke /admin dan /admin/*.
 *
 * PENTING: Sengaja TIDAK melakukan redirect ke /pentadbir.
 * Redirect akan membocorkan lokasi panel admin yang baru.
 * 404 yang diam (silent 404) adalah respons yang benar secara keamanan.
 *
 * Middleware ini didaftarkan SEBELUM routing sehingga path /admin
 * tidak pernah mencapai Filament router.
 */
class BlockLegacyAdminPath
{
    public function handle(Request $request, Closure $next): Response
    {
        // Tangkap /admin dan semua sub-path /admin/*
        if ($request->is('admin') || $request->is('admin/*')) {
            abort(404);
        }

        return $next($request);
    }
}
