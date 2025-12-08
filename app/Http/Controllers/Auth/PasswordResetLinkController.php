<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        try {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
            }

            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Gagal mengirim email reset password. Silakan coba lagi.']);

        } catch (\Exception $e) {
            \Log::error('Password reset link error: ' . $e->getMessage());
            
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        }
    }
}
