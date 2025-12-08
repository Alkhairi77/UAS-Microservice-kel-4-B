<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Notification;
use App\Models\Perusahaan;
use App\Models\LaporanHarian;
use App\Models\PengelolaanLimbah;
use App\Models\Penyimpanan;

class NotificationHelper
{
    /**
     * Send notification to user
     */
    public static function notifyUser(User $user, string $title, string $message, string $type = 'info', string $actionUrl = null, array $data = null)
    {
        return Notification::createForUser(
            $user->id,
            $title,
            $message,
            $type,
            $actionUrl,
            $data
        );
    }

    /**
     * Send notification to all admins
     */
    public static function notifyAdmins(string $title, string $message, string $type = 'info', string $actionUrl = null, array $data = null)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            self::notifyUser($admin, $title, $message, $type, $actionUrl, $data);
        }
    }

    /**
     * Notification when new perusahaan registers
     */
    public static function perusahaanRegistered(Perusahaan $perusahaan)
    {
        self::notifyAdmins(
            'Perusahaan Baru Terdaftar',
            "Perusahaan {$perusahaan->nama_perusahaan} telah mendaftar dan memerlukan verifikasi",
            'info',
            route('admin.perusahaan.show', $perusahaan),
            ['perusahaan_id' => $perusahaan->id]
        );
    }

    /**
     * Welcome notification for new user
     */
    public static function welcomeNewUser(User $user)
    {
        $message = $user->isPerusahaan() 
            ? 'Selamat datang di EcoCycle! Silakan lengkapi profil perusahaan Anda untuk memulai.'
            : 'Selamat datang di EcoCycle! Akun Anda telah berhasil dibuat.';
            
        $actionUrl = $user->isPerusahaan() 
            ? route('perusahaan.dashboard')
            : route('profile.edit');

        self::notifyUser(
            $user,
            'Selamat Datang!',
            $message,
            'success',
            $actionUrl
        );
    }

    /**
     * Notification when perusahaan profile is updated
     */
    public static function perusahaanUpdated(Perusahaan $perusahaan)
    {
        self::notifyUser(
            $perusahaan->user,
            'Profil Perusahaan Diperbarui',
            'Profil perusahaan Anda telah berhasil diperbarui',
            'success',
            route('perusahaan.show', $perusahaan)
        );
    }

    /**
     * Notification when laporan harian is submitted
     */
    public static function laporanHarianSubmitted(LaporanHarian $laporan)
    {
        // Notify admins
        self::notifyAdmins(
            'Laporan Harian Baru',
            "Laporan harian dari {$laporan->perusahaan->nama_perusahaan} untuk {$laporan->jenisLimbah->nama} telah disubmit",
            'info',
            route('laporan-harian.show', $laporan),
            ['laporan_id' => $laporan->id]
        );

        // Notify perusahaan
        self::notifyUser(
            $laporan->perusahaan->user,
            'Laporan Harian Berhasil Disubmit',
            "Laporan harian untuk {$laporan->jenisLimbah->nama} sebanyak {$laporan->jumlah} {$laporan->satuan} telah berhasil disubmit",
            'success',
            route('laporan-harian.show', $laporan)
        );
    }

    /**
     * Notification for overdue laporan harian
     */
    public static function laporanHarianOverdue(Perusahaan $perusahaan)
    {
        self::notifyUser(
            $perusahaan->user,
            'Pengingat Laporan Harian',
            'Anda belum mengisi laporan harian hari ini. Silakan lengkapi laporan Anda.',
            'warning',
            route('laporan-harian.create')
        );
    }

    /**
     * Notification when penyimpanan is nearly full
     */
    public static function penyimpananFull(Penyimpanan $penyimpanan)
    {
        $percentage = ($penyimpanan->kapasitas_terpakai / $penyimpanan->kapasitas_maksimal) * 100;
        
        $title = $percentage >= 95 ? 'Penyimpanan Penuh!' : 'Penyimpanan Hampir Penuh';
        $type = $percentage >= 95 ? 'danger' : 'warning';
        
        self::notifyUser(
            $penyimpanan->perusahaan->user,
            $title,
            "Penyimpanan {$penyimpanan->nama_penyimpanan} sudah terisi {$percentage}%. Segera lakukan pengelolaan limbah.",
            $type,
            route('penyimpanan.show', $penyimpanan)
        );
    }

    /**
     * Notification when pengelolaan limbah is created
     */
    public static function pengelolaanCreated(PengelolaanLimbah $pengelolaan)
    {
        self::notifyUser(
            $pengelolaan->perusahaan->user,
            'Pengelolaan Limbah Dimulai',
            "Pengelolaan {$pengelolaan->jenisLimbah->nama} sebanyak {$pengelolaan->jumlah_dikelola} {$pengelolaan->satuan} telah dimulai",
            'info',
            route('pengelolaan-limbah.show', $pengelolaan)
        );

        // Notify admins
        self::notifyAdmins(
            'Pengelolaan Limbah Baru',
            "Pengelolaan limbah baru dari {$pengelolaan->perusahaan->nama_perusahaan}",
            'info',
            route('pengelolaan-limbah.show', $pengelolaan)
        );
    }

    /**
     * Notification when pengelolaan limbah is completed
     */
    public static function pengelolaanCompleted(PengelolaanLimbah $pengelolaan)
    {
        self::notifyUser(
            $pengelolaan->perusahaan->user,
            'Pengelolaan Limbah Selesai',
            "Pengelolaan {$pengelolaan->jenisLimbah->nama} telah selesai. Silakan buat laporan hasil pengelolaan.",
            'success',
            route('laporan-hasil-pengelolaan.create')
        );
    }

    /**
     * Notification for system maintenance
     */
    public static function systemMaintenance(string $message, \DateTime $scheduledTime = null)
    {
        $allUsers = User::all();
        
        $title = 'Pemeliharaan Sistem';
        $fullMessage = $scheduledTime 
            ? "Sistem akan menjalani pemeliharaan pada {$scheduledTime->format('d/m/Y H:i')}. {$message}"
            : $message;

        foreach ($allUsers as $user) {
            self::notifyUser(
                $user,
                $title,
                $fullMessage,
                'warning'
            );
        }
    }

    /**
     * Notification for low storage capacity across all perusahaan
     */
    public static function checkAllPenyimpananCapacity()
    {
        $fullPenyimpanans = Penyimpanan::whereRaw('(kapasitas_terpakai / kapasitas_maksimal) >= 0.9')
            ->with('perusahaan.user')
            ->get();

        foreach ($fullPenyimpanans as $penyimpanan) {
            self::penyimpananFull($penyimpanan);
        }
    }

    /**
     * Daily reminder for companies without recent reports
     */
    public static function dailyReminderInactiveCompanies()
    {
        $inactiveCompanies = Perusahaan::whereDoesntHave('laporanHarian', function ($query) {
            $query->where('created_at', '>=', now()->subDays(3));
        })->with('user')->get();

        foreach ($inactiveCompanies as $perusahaan) {
            self::notifyUser(
                $perusahaan->user,
                'Pengingat Aktivitas',
                'Anda belum melaporkan aktivitas limbah dalam 3 hari terakhir. Jangan lupa untuk membuat laporan harian.',
                'warning',
                route('laporan-harian.create')
            );
        }
    }
}