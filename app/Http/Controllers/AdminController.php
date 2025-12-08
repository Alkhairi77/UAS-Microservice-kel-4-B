<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\JenisLimbah;
use App\Models\Vendor;
use App\Models\LaporanHarian;
use App\Models\PengelolaanLimbah;
use App\Models\LaporanHasilPengelolaan;
use App\Models\Penyimpanan;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        // Basic Statistics
        $totalUsers = User::count();
        $totalPerusahaan = Perusahaan::count();
        $activeUsers = User::where('status', 'active')->count();
        $pendingUsers = User::where('status', 'pending')->count();
        $totalVendors = Vendor::count();
        $totalJenisLimbah = JenisLimbah::count();
        $totalKategoriArtikel = KategoriArtikel::count();

        // Laporan Statistics
        $totalLaporanHarian = LaporanHarian::count();
        $laporanHarianBulanIni = LaporanHarian::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();
        $totalPengelolaan = PengelolaanLimbah::count();
        $pengelolaanAktif = PengelolaanLimbah::whereIn('status', ['diproses', 'dalam_pengangkutan'])->count();
        $totalLaporanHasil = LaporanHasilPengelolaan::count();

        // Penyimpanan Statistics
        $totalPenyimpanan = Penyimpanan::count();
        $penyimpananAktif = Penyimpanan::where('status', 'aktif')->count();
        $kapasitasTerpakai = Penyimpanan::sum('kapasitas_terpakai');
        $kapasitasMaksimal = Penyimpanan::sum('kapasitas_maksimal');
        $persentaseKapasitas = $kapasitasMaksimal > 0 ? ($kapasitasTerpakai / $kapasitasMaksimal) * 100 : 0;

        // Recent Activities
        $recentUsers = User::latest()->take(5)->get();
        $recentPerusahaan = Perusahaan::with('user')->latest()->take(5)->get();
        $recentLaporan = LaporanHarian::with(['perusahaan', 'jenisLimbah'])
            ->latest('tanggal')->take(5)->get();

        // Monthly Trends (6 months)
        $monthlyLaporanTrend = $this->getMonthlyLaporanTrend();
        $monthlyPengelolaanTrend = $this->getMonthlyPengelolaanTrend();
        $monthlyUserTrend = $this->getMonthlyUserTrend();

        // Top Statistics
        $topPerusahaanByLaporan = $this->getTopPerusahaanByLaporan();
        $topJenisLimbah = $this->getTopJenisLimbah();
        $statusPengelolaanStats = $this->getStatusPengelolaanStats();
        $efisiensiStats = $this->getEfisiensiStats();

        // Alerts
        $alerts = $this->getSystemAlerts();

        // Jenis Usaha Distribution
        $jenisUsahaStats = Perusahaan::select('jenis_usaha', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_usaha')
            ->groupBy('jenis_usaha')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPerusahaan', 
            'activeUsers',
            'pendingUsers',
            'totalVendors',
            'totalJenisLimbah',
            'totalKategoriArtikel',
            'totalLaporanHarian',
            'laporanHarianBulanIni',
            'totalPengelolaan',
            'pengelolaanAktif',
            'totalLaporanHasil',
            'totalPenyimpanan',
            'penyimpananAktif',
            'persentaseKapasitas',
            'recentUsers',
            'recentPerusahaan',
            'recentLaporan',
            'monthlyLaporanTrend',
            'monthlyPengelolaanTrend',
            'monthlyUserTrend',
            'topPerusahaanByLaporan',
            'topJenisLimbah',
            'statusPengelolaanStats',
            'efisiensiStats',
            'alerts',
            'jenisUsahaStats'
        ));
    }

    private function getMonthlyLaporanTrend()
    {
        return LaporanHarian::select(
                DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function getMonthlyPengelolaanTrend()
    {
        return PengelolaanLimbah::select(
                DB::raw('DATE_FORMAT(tanggal_mulai, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal_mulai', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function getMonthlyUserTrend()
    {
        return User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function getTopPerusahaanByLaporan()
    {
        return Perusahaan::select('perusahaans.*', DB::raw('COUNT(laporan_harians.id) as total_laporan'))
            ->leftJoin('laporan_harians', 'perusahaans.id', '=', 'laporan_harians.perusahaan_id')
            ->groupBy('perusahaans.id')
            ->orderBy('total_laporan', 'desc')
            ->take(5)
            ->get();
    }

    private function getTopJenisLimbah()
    {
        return JenisLimbah::select('jenis_limbahs.*', DB::raw('COUNT(laporan_harians.id) as total_laporan'))
            ->leftJoin('laporan_harians', 'jenis_limbahs.id', '=', 'laporan_harians.jenis_limbah_id')
            ->groupBy('jenis_limbahs.id')
            ->orderBy('total_laporan', 'desc')
            ->take(5)
            ->get();
    }

    private function getStatusPengelolaanStats()
    {
        return PengelolaanLimbah::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
    }

    private function getEfisiensiStats()
    {
        return [
            'rata_rata' => LaporanHasilPengelolaan::avg('efisiensi_pengelolaan') ?? 0,
            'tinggi' => LaporanHasilPengelolaan::where('efisiensi_pengelolaan', '>=', 80)->count(),
            'sedang' => LaporanHasilPengelolaan::whereBetween('efisiensi_pengelolaan', [60, 79])->count(),
            'rendah' => LaporanHasilPengelolaan::where('efisiensi_pengelolaan', '<', 60)->count(),
        ];
    }

    private function getSystemAlerts()
    {
        $alerts = [];

        // Pending users
        $pendingUsers = User::where('status', 'pending')->count();
        if ($pendingUsers > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'User Pending',
                'message' => "Ada {$pendingUsers} user yang menunggu persetujuan.",
                'action' => route('admin.users.index')
            ];
        }

        // Overdue pengelolaan
        $overduePengelolaan = PengelolaanLimbah::where('status', '!=', 'selesai')
            ->where('tanggal_mulai', '<', now()->subDays(30))
            ->count();
        if ($overduePengelolaan > 0) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Pengelolaan Terlambat',
                'message' => "Ada {$overduePengelolaan} pengelolaan yang sudah berjalan lebih dari 30 hari.",
                'action' => route('pengelolaan-limbah.index')
            ];
        }
        $inactiveCompanies = Perusahaan::whereDoesntHave('laporanHarian', function($query) {
            $query->where('created_at', '>', Carbon::now()->subDays(7));
        })->count();

        if ($inactiveCompanies > 0) {
            // Cek apakah notifikasi sudah pernah dikirim dalam 1 hari terakhir
            $alreadySent = \App\Models\Notification::where('title', 'Perusahaan Tidak Aktif')
                ->where('type', 'warning')
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if (!$alreadySent) {
                NotificationHelper::notifyAdmins(
                    'Perusahaan Tidak Aktif',
                    "{$inactiveCompanies} perusahaan tidak melaporkan aktivitas selama 7 hari terakhir",
                    'warning',
                    route('admin.perusahaan.index')
                );
            }
        }


        return $alerts;
    }

    public function users(): View
    {
        $users = User::with('perusahaan')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function showUser(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function toggleUserStatus(User $user): RedirectResponse
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'Status user berhasil diubah.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function perusahaan(): View
    {
        $perusahaans = Perusahaan::with('user')->paginate(10);
        return view('perusahaan.index', compact('perusahaans'));
    }

    public function jenisLimbah(): View
    {
        $jenisLimbah = JenisLimbah::paginate(10);
        return view('jenis-limbah.index', compact('jenisLimbah'));
    }

    public function vendor(): View
    {
        $vendors = Vendor::paginate(10);
        return view('vendor.index', compact('vendors'));
    }
}
