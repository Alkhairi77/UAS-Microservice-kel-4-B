<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\NotificationHelper;
use App\Models\LaporanHarian;
use App\Models\PengelolaanLimbah;
use Carbon\Carbon;

class PerformanceMonitor extends Command
{
    protected $signature = 'performance:monitor';
    protected $description = 'Monitor system performance and send alerts';

    public function handle()
    {
        $this->checkReportingPerformance();
        $this->checkProcessingPerformance();
        $this->checkSystemHealth();
        
        $this->info('Performance monitoring completed');
    }

    private function checkReportingPerformance()
    {
        // Cek perusahaan dengan performa pelaporan rendah
        $perusahaans = \App\Models\Perusahaan::withCount([
            'laporanHarian' => function($query) {
                $query->where('created_at', '>', Carbon::now()->subMonth());
            }
        ])->having('laporan_harian_count', '<', 20) // Kurang dari 20 laporan per bulan
        ->with('user')
        ->get();

        foreach ($perusahaans as $perusahaan) {
            NotificationHelper::notifyUser(
                $perusahaan->user,
                'Performa Pelaporan Rendah',
                'Aktivitas pelaporan Anda menurun bulan ini. Pastikan untuk melaporkan semua aktivitas pengelolaan limbah.',
                'warning',
                route('laporan-harian.create')
            );
        }
    }

    private function checkProcessingPerformance()
    {
        // Cek rata-rata waktu pengelolaan
        $avgProcessingTime = PengelolaanLimbah::where('status', 'selesai')
            ->where('updated_at', '>', Carbon::now()->subMonth())
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');

        if ($avgProcessingTime > 21) { // Lebih dari 3 minggu
            NotificationHelper::notifyAdmins(
                'Performa Pengelolaan Menurun',
                "Rata-rata waktu pengelolaan limbah adalah {$avgProcessingTime} hari. Pertimbangkan untuk mengevaluasi proses.",
                'warning',
                route('admin.pengelolaan-limbah.index')
            );
        }
    }

    private function checkSystemHealth()
    {
        // Cek kesehatan sistem secara umum
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('last_login_at', '>', Carbon::now()->subDays(7))->count();
        $activePercentage = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;

        if ($activePercentage < 50) {
            NotificationHelper::notifyAdmins(
                'Aktivitas User Rendah',
                "Hanya {$activePercentage}% user yang aktif dalam 7 hari terakhir",
                'warning',
                route('admin.users.index')
            );
        }
    }
}