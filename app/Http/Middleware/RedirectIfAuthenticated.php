<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }
                
                if ($user->isPerusahaan()) {
                    if (!$user->perusahaan) {
                        return redirect()->route('perusahaan.create')
                            ->with('warning', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
                    }
                    return redirect()->route('perusahaan.dashboard');
                }

                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Akun Anda tidak memiliki akses yang valid.');
            }
        }

        return $next($request);
    }
} 