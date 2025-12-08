<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerusahaanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Cek apakah user adalah perusahaan
        if (!$user->isPerusahaan()) {
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Halaman ini khusus untuk perusahaan.');
        }

        // Untuk route perusahaan.create dan perusahaan.store, izinkan tanpa perusahaan
        if (in_array($request->route()->getName(), ['perusahaan.create', 'perusahaan.store'])) {
            return $next($request);
        }

        // Untuk route lainnya, periksa apakah user memiliki perusahaan
        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
