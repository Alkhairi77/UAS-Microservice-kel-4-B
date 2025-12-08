<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perusahaan;
use App\Models\PengelolaanLimbah;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class SendPeriodicNotifications extends Command
{
    protected $signature = 'notifications:periodic';
    protected $description = 'Send periodic notifications to users';

    public function handle()
    {
        $this->info('Sending periodic notifications...');
        
        $this->sendWeeklyDigest();
        $this->sendMaintenanceReminders();
        $this->sendComplianceUpdates();
        
        $this->info('Periodic notifications sent successfully');
    }

    private function sendWeeklyDigest()
    {
        // Kirim digest mingguan setiap hari Senin
        if (Carbon::now()->dayOfWeek === Carbon::MONDAY) {
            $perusahaans = Perusahaan::with('user')->get();
            
            foreach ($perusahaans as $perusahaan) {
                $weeklyStats = $this->getWeeklyStats($perusahaan);
                
                NotificationHelper::notifyUser(
                    $perusahaan->user,
                    'Ringkasan Mingguan',
                    "Minggu lalu: {$weeklyStats['laporan']} laporan, {$weeklyStats['pengelolaan']} pengelolaan. Tetap jaga konsistensi pelaporan!",
                    'info',
                    route('perusahaan.dashboard')
                );
            }
        }
    }

    private function sendMaintenanceReminders()
    {
        // Reminder maintenance penyimpanan setiap 3 bulan
        $penyimpanans = \App\Models\Penyimpanan::where('tanggal_pembuatan', '<', Carbon::now()->subMonths(3))
            ->whereDoesntHave('user.notifications', function($query) {
                $query->where('title', 'LIKE', '%Maintenance%')
                      ->where('created_at', '>', Carbon::now()->subMonths(1));
            })
            ->with('perusahaan.user')
            ->get();

        foreach ($penyimpanans as $penyimpanan) {
            NotificationHelper::notifyUser(
                $penyimpanan->perusahaan->user,
                'Reminder Maintenance Penyimpanan',
                "Penyimpanan {$penyimpanan->nama_penyimpanan} perlu dilakukan maintenance berkala untuk menjaga kondisi optimal.",
                'info',
                route('penyimpanan.show', $penyimpanan)
            );
        }
    }

    private function sendComplianceUpdates()
    {
        // Update regulasi atau compliance (contoh)
        $updates = [
            'Peraturan baru tentang pengelolaan limbah B3 telah berlaku',
            'Update format manifest limbah sesuai regulasi terbaru',
            'Reminder: Lakukan audit internal pengelolaan limbah',
            'Pastikan sertifikasi vendor masih berlaku'
        ];

        // Kirim update setiap bulan tanggal 1
        if (Carbon::now()->day === 1) {
            $perusahaans = Perusahaan::with('user')->get();
            $randomUpdate = $updates[array_rand($updates)];

            foreach ($perusahaans as $perusahaan) {
                NotificationHelper::notifyUser(
                    $perusahaan->user,
                    'Update Compliance',
                    $randomUpdate,
                    'info'
                );
            }
        }
    }

    private function getWeeklyStats($perusahaan)
    {
        $startOfWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfWeek = Carbon::now()->subWeek()->endOfWeek();

        return [
            'laporan' => $perusahaan->laporanHarian()
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count(),
            'pengelolaan' => $perusahaan->pengelolaanLimbah()
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count()
        ];
    }
}
