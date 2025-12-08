<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Models\LaporanHarian;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportLaporanHarian(Request $request)
    {
        try {
            // Logic export...
            
            NotificationHelper::notifyUser(
                auth()->user(),
                'Export Berhasil',
                'Data laporan harian berhasil diekspor. File dapat diunduh dari email Anda.',
                'success'
            );
            
            // Notifikasi admin tentang aktivitas export
            NotificationHelper::notifyAdmins(
                'Aktivitas Export',
                auth()->user()->name . ' telah mengekspor data laporan harian',
                'info'
            );
            
            return response()->download($filePath);
            
        } catch (\Exception $e) {
            NotificationHelper::notifyUser(
                auth()->user(),
                'Export Gagal',
                'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.',
                'danger'
            );
            
            return back()->with('error', 'Export gagal: ' . $e->getMessage());
        }
    }
}