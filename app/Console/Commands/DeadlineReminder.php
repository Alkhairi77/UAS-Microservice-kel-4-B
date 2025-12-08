<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PengelolaanLimbah;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class DeadlineReminder extends Command
{
    protected $signature = 'deadline:remind';
    protected $description = 'Send deadline reminders';

    public function handle()
    {
        $this->remindPengelolaanDeadline();
        $this->remindLaporanDeadline();
        $this->remindCertificationExpiry();
        
        $this->info('Deadline reminders sent');
    }

    private function remindPengelolaanDeadline()
    {
        // Reminder 7 hari sebelum deadline
        $upcoming = PengelolaanLimbah::where('status', '!=', 'selesai')
            ->where('target_selesai', '<=', Carbon::now()->addDays(7))
            ->where('target_selesai', '>', Carbon::now())
            ->with('perusahaan.user', 'jenisLimbah')
            ->get();

        foreach ($upcoming as $pengelolaan) {
            $daysLeft = Carbon::now()->diffInDays($pengelolaan->target_selesai);
            
            NotificationHelper::notifyUser(
                $pengelolaan->perusahaan->user,
                'Deadline Pengelolaan Mendekat',
                "Pengelolaan {$pengelolaan->jenisLimbah->nama} akan berakhir dalam {$daysLeft} hari",
                'warning',
                route('pengelolaan-limbah.show', $pengelolaan)
            );
        }
    }

    private function remindLaporanDeadline()
    {
        // Reminder laporan bulanan (tanggal 25)
        if (Carbon::now()->day === 25) {
            $perusahaans = \App\Models\Perusahaan::with('user')->get();
            
            foreach ($perusahaans as $perusahaan) {
                NotificationHelper::notifyUser(
                    $perusahaan->user,
                    'Deadline Laporan Bulanan',
                    'Jangan lupa untuk melengkapi laporan bulanan sebelum akhir bulan',
                    'warning',
                    route('laporan-harian.index')
                );
            }
        }
    }

    private function remindCertificationExpiry()
    {
        // Reminder sertifikasi vendor yang akan expired
        $vendors = \App\Models\Vendor::where('tanggal_expired_sertifikat', '<=', Carbon::now()->addDays(30))
            ->where('tanggal_expired_sertifikat', '>', Carbon::now())
            ->get();

        foreach ($vendors as $vendor) {
            $daysLeft = Carbon::now()->diffInDays($vendor->tanggal_expired_sertifikat);
            
            NotificationHelper::notifyAdmins(
                'Sertifikat Vendor Akan Expired',
                "Sertifikat vendor {$vendor->nama_perusahaan} akan expired dalam {$daysLeft} hari",
                'warning',
                route('admin.vendor.show', $vendor)
            );
        }
    }
}