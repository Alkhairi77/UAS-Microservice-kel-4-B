<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->isAdmin()) {
            if ($user->isPerusahaan()) {
                if (!$user->perusahaan) {
                    return redirect()->route('perusahaan.create')
                        ->with('warning', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
                }
                return redirect()->route('perusahaan.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak memiliki akses yang valid.');
        }

        return $next($request);
    }
} 