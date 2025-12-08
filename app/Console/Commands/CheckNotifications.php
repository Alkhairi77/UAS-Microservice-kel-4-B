<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PengelolaanLimbah;
use App\Models\Penyimpanan;
use App\Models\Perusahaan;
use App\Models\LaporanHarian;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for system notifications and send alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting notification checks...');
        
        $this->checkOverduePengelolaan();
        $this->checkFullStorage();
        $this->checkDailyReportReminder();
        $this->checkMonthlyReportReminder();
        $this->checkComplianceReminders();
        
        $this->info('Notification check completed successfully');
    }

    private function checkOverduePengelolaan()
    {
        $this->info('Checking overdue pengelolaan...');
        
        $overdue = PengelolaanLimbah::where('status', '!=', 'selesai')
            ->where('tanggal_mulai', '<', Carbon::now()->subDays(30))
            ->whereDoesntHave('notifications', function($query) {
                $query->where('title', 'Pengelolaan Limbah Terlambat')
                      ->where('created_at', '>', Carbon::now()->subDays(7)); // Jangan spam notifikasi
            })
            ->with('perusahaan.user', 'jenisLimbah')
            ->get();

        foreach ($overdue as $pengelolaan) {
            NotificationHelper::pengelolaanOverdue($pengelolaan);
            $this->info("Sent overdue notification for pengelolaan ID: {$pengelolaan->id}");
        }
        
        $this->info("Found {$overdue->count()} overdue pengelolaan");
    }

    private function checkFullStorage()
    {
        $this->info('Checking storage capacity...');
        
        $fullStorages = Penyimpanan::whereRaw('(kapasitas_terpakai / kapasitas_maksimal) >= 0.9')
            ->whereDoesntHave('notifications', function($query) {
                $query->where('title', 'LIKE', '%Penyimpanan%Penuh%')
                      ->where('created_at', '>', Carbon::now()->subDays(3)); // Notifikasi setiap 3 hari
            })
            ->with('perusahaan.user')
            ->get();

        foreach ($fullStorages as $storage) {
            NotificationHelper::penyimpananFull($storage);
            $this->info("Sent storage full notification for: {$storage->nama_penyimpanan}");
        }
        
        $this->info("Found {$fullStorages->count()} storages near capacity");
    }

    private function checkDailyReportReminder()
    {
        $this->info('Checking daily report reminders...');
        
        // Cek perusahaan yang belum laporan hari ini (jam 16:00)
        if (Carbon::now()->hour >= 16) {
            $perusahaans = Perusahaan::whereDoesntHave('laporanHarian', function($query) {
                $query->whereDate('tanggal_laporan', Carbon::today());
            })
            ->whereDoesntHave('user.notifications', function($query) {
                $query->where('title', 'Pengingat Laporan Harian')
                      ->whereDate('created_at', Carbon::today());
            })
            ->with('user')
            ->get();

            foreach ($perusahaans as $perusahaan) {
                NotificationHelper::laporanHarianOverdue($perusahaan);
                $this->info("Sent daily report reminder to: {$perusahaan->nama_perusahaan}");
            }
            
            $this->info("Sent daily report reminders to {$perusahaans->count()} companies");
        }
    }

    private function checkMonthlyReportReminder()
    {
        $this->info('Checking monthly report reminders...');
        
        // Kirim reminder tanggal 25 setiap bulan
        if (Carbon::now()->day === 25) {
            $perusahaans = Perusahaan::whereDoesntHave('user.notifications', function($query) {
                $query->where('title', 'Pengingat Laporan Bulanan')
                      ->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            })
            ->with('user')
            ->get();

            foreach ($perusahaans as $perusahaan) {
                NotificationHelper::monthlyReportReminder($perusahaan);
                $this->info("Sent monthly report reminder to: {$perusahaan->nama_perusahaan}");
            }
            
            $this->info("Sent monthly report reminders to {$perusahaans->count()} companies");
        }
    }

    private function checkComplianceReminders()
    {
        $this->info('Checking compliance reminders...');
        
        $reminders = [
            'Pastikan semua limbah B3 telah dikelola sesuai regulasi',
            'Periksa kembali manifest limbah yang belum diselesaikan',
            'Update data penyimpanan limbah secara berkala',
            'Lakukan audit internal pengelolaan limbah'
        ];

        // Kirim reminder compliance setiap minggu (hari Senin)
        if (Carbon::now()->dayOfWeek === Carbon::MONDAY) {
            $perusahaans = Perusahaan::with('user')->get();
            $randomReminder = $reminders[array_rand($reminders)];

            foreach ($perusahaans as $perusahaan) {
                // Cek apakah sudah dapat reminder minggu ini
                $hasReminderThisWeek = $perusahaan->user->notifications()
                    ->where('title', 'Pengingat Kepatuhan')
                    ->where('created_at', '>', Carbon::now()->startOfWeek())
                    ->exists();

                if (!$hasReminderThisWeek) {
                    NotificationHelper::complianceReminder($perusahaan, $randomReminder);
                    $this->info("Sent compliance reminder to: {$perusahaan->nama_perusahaan}");
                }
            }
        }
    }
}
