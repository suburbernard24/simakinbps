<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek jika akun nonaktif
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan oleh Administrator.');
        }

        // Cek permission berdasarkan role yang diizinkan
        foreach ($roles as $role) {
            if ($role === 'admin' && $user->isAdmin()) {
                return $next($request);
            }
            if ($role === 'kepala_bps' && $user->isKepala()) {
                return $next($request);
            }
            if ($role === 'ketua_tim' && $user->isKetuaTim()) {
                return $next($request);
            }
            if ($role === 'pegawai' && ($user->isPegawai() || $user->role === 'pegawai' || $user->role === 'anggota_tim')) {
                return $next($request);
            }
            if ($role === 'non-admin' && !$user->isAdmin()) {
                return $next($request);
            }
            if ($role === 'penilai' && ($user->isKepala() || $user->isKetuaTim())) {
                return $next($request);
            }
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman atau fungsi ini.');
    }
}
