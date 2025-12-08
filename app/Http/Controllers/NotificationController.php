<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function destroy(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->notifications()->delete();
        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
