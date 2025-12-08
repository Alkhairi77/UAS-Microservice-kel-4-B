<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class SecurityNotifications extends Command
{
    protected $signature = 'security:check';
    protected $description = 'Check security and send notifications';

    public function handle()
    {
        $this->checkPasswordAge();
        $this->checkInactiveUsers();
        $this->checkSuspiciousActivity();
        
        $this->info('Security check completed');
    }

    private function checkPasswordAge()
    {
        // Cek user yang belum ganti password > 90 hari
        $users = User::where('password_changed_at', '<', Carbon::now()->subDays(90))
            ->orWhereNull('password_changed_at')
            ->get();

        foreach ($users as $user) {
            NotificationHelper::notifyUser(
                $user,
                'Ganti Password Anda',
                'Untuk keamanan akun, disarankan untuk mengganti password secara berkala. Terakhir Anda mengganti password lebih dari 90 hari yang lalu.',
                'warning',
                route('profile.edit')
            );
        }
    }

    private function checkInactiveUsers()
    {
        // Cek user yang tidak login > 30 hari
        $inactiveUsers = User::where('last_login_at', '<', Carbon::now()->subDays(30))
            ->where('role', 'perusahaan')
            ->get();

        foreach ($inactiveUsers as $user) {
            NotificationHelper::notifyUser(
                $user,
                'Akun Tidak Aktif',
                'Akun Anda sudah tidak aktif selama 30 hari. Silakan login untuk menjaga keamanan akun.',
                'warning',
                route('login')
            );
        }

        // Notifikasi admin tentang user tidak aktif
        if ($inactiveUsers->count() > 0) {
            NotificationHelper::notifyAdmins(
                'User Tidak Aktif',
                "{$inactiveUsers->count()} user tidak login selama 30 hari terakhir",
                'info',
                route('admin.users.index')
            );
        }
    }

    private function checkSuspiciousActivity()
    {
        // Contoh: Cek login dari IP yang berbeda dalam waktu singkat
        // Implementasi sesuai kebutuhan security
        
        // Cek multiple failed login attempts
        // Cek akses dari lokasi yang tidak biasa
        // dll.
    }
}