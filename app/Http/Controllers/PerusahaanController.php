<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\Perusahaan;
use App\Models\Penyimpanan;
use Illuminate\Http\Request;
use App\Models\LaporanHarian;
use App\Models\PengelolaanLimbah;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\LaporanHasilPengelolaan;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfilPerusahaanRequest;

class PerusahaanController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();

        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        $perusahaan = $user->perusahaan;
        $perusahaanId = $perusahaan->id;

        // Basic Statistics
        $total_laporan = LaporanHarian::where('perusahaan_id', $perusahaanId)->count();
        $laporan_pending = LaporanHarian::where('perusahaan_id', $perusahaanId)
            ->where('status', 'draft')->count();

        $total_pengelolaan = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)->count();

        $total_laporan_hasil = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)->count();

        // Laporan Hasil Statistics
        $laporan_berhasil = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->where('status_hasil', 'berhasil')->count();

        $laporan_partial = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->where('status_hasil', 'sebagian_berhasil')->count();

        $laporan_gagal = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->where('status_hasil', 'gagal')->count();

        // Average Efficiency
        $avg_efisiensi = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->avg('efisiensi_pengelolaan') ?? 0;

        // Total Cost
        $total_biaya = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->sum('biaya_aktual') ?? 0;

        // Recent Activities
        $recent_laporan_harian = LaporanHarian::with(['jenisLimbah', 'penyimpanan'])
            ->where('perusahaan_id', $perusahaanId)
            ->latest()
            ->take(5)
            ->get();

        $recent_pengelolaan = PengelolaanLimbah::with(['jenisLimbah'])
            ->where('perusahaan_id', $perusahaanId)
            ->latest('tanggal_mulai')
            ->take(5)
            ->get();

        $recent_laporan_hasil = LaporanHasilPengelolaan::with(['pengelolaanLimbah.laporanHarian.jenisLimbah'])
            ->where('perusahaan_id', $perusahaanId)
            ->latest()
            ->take(5)
            ->get();

        // Monthly Trend (6 months)
        $monthly_trend = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->where('tanggal_selesai', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(tanggal_selesai, "%Y-%m") as month, COUNT(*) as total_laporan')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Alerts and Notifications
        $alerts = $this->generateAlerts($perusahaanId);
        // Data untuk chart laporan harian per jenis limbah
        $laporan_harian_by_jenis_limbah = LaporanHarian::selectRaw('jenis_limbah_id, COUNT(*) as total')
            ->where('perusahaan_id', $perusahaanId)
            ->groupBy('jenis_limbah_id')
            ->with('jenisLimbah')
            ->get();

        $laporan_harian_by_jenis_limbah_labels = $laporan_harian_by_jenis_limbah->map(function ($row) {
            return $row->jenisLimbah ? $row->jenisLimbah->nama : 'Tidak diketahui';
        });
        $laporan_harian_by_jenis_limbah_data = $laporan_harian_by_jenis_limbah->pluck('total');

        return view('perusahaan.dashboard', compact(
            'perusahaan',
            'total_laporan',
            'laporan_pending',
            'total_pengelolaan',
            'total_laporan_hasil',
            'laporan_berhasil',
            'laporan_partial',
            'laporan_gagal',
            'avg_efisiensi',
            'total_biaya',
            'recent_laporan_harian',
            'recent_pengelolaan',
            'recent_laporan_hasil',
            'monthly_trend',
            'alerts',
            'laporan_harian_by_jenis_limbah_labels',
            'laporan_harian_by_jenis_limbah_data',
        ));
    }

    private function generateAlerts($perusahaanId): array
    {
        $alerts = [];

        // Check for draft reports
        $draftCount = LaporanHarian::where('perusahaan_id', $perusahaanId)
            ->where('status', 'draft')->count();

        if ($draftCount > 0) {
            $alerts[] = [
                'type' => 'yellow',
                'title' => 'Laporan Draft',
                'message' => "Anda memiliki {$draftCount} laporan harian yang masih dalam status draft.",
                'action' => [
                    'text' => 'Lihat Laporan',
                    'url' => route('laporan-harian.index', ['status' => 'draft'])
                ]
            ];
        }

        // Storage capacity warning
        $fullStorages = Penyimpanan::whereRaw('(kapasitas_terpakai / kapasitas_maksimal) > 0.9')->count();
        if ($fullStorages > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Kapasitas Penyimpanan',
                'message' => "Ada {$fullStorages} penyimpanan yang hampir penuh (>90%).",
                'action' => [
                    'text' => 'Lihat Penyimpanan',
                    'url' => route('penyimpanan.index')
                ]
            ];
        }

        // Check for pending pengelolaan
        $pendingPengelolaan = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
            ->where('status', 'diproses')->count();

        if ($pendingPengelolaan > 0) {
            $alerts[] = [
                'type' => 'blue',
                'title' => 'Pengelolaan Dalam Proses',
                'message' => "Ada {$pendingPengelolaan} pengelolaan limbah yang sedang dalam proses.",
                'action' => [
                    'text' => 'Lihat Pengelolaan',
                    'url' => route('pengelolaan-limbah.index', ['status' => 'diproses'])
                ]
            ];
        }

        // Check for low efficiency
        $lowEfficiency = LaporanHasilPengelolaan::where('perusahaan_id', $perusahaanId)
            ->where('efisiensi_pengelolaan', '<', 60)
            ->where('tanggal_selesai', '>=', Carbon::now()->subMonth())
            ->count();

        if ($lowEfficiency > 0) {
            $alerts[] = [
                'type' => 'red',
                'title' => 'Efisiensi Rendah',
                'message' => "Terdapat {$lowEfficiency} laporan hasil dengan efisiensi di bawah 60% bulan ini.",
                'action' => [
                    'text' => 'Lihat Laporan Hasil',
                    'url' => route('laporan-hasil-pengelolaan.index')
                ]
            ];
        }

        // Check for overdue pengelolaan (more than 30 days)
        $overduePengelolaan = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
            ->where('status', '!=', 'selesai')
            ->where('tanggal_mulai', '<', Carbon::now()->subDays(30))
            ->count();

        if ($overduePengelolaan > 0) {
            $alerts[] = [
                'type' => 'red',
                'title' => 'Pengelolaan Terlambat',
                'message' => "Ada {$overduePengelolaan} pengelolaan limbah yang sudah berjalan lebih dari 30 hari.",
                'action' => [
                    'text' => 'Lihat Pengelolaan',
                    'url' => route('pengelolaan-limbah.index')
                ]
            ];
        }

        return $alerts;
    }

    /**
     * Display a listing of perusahaan (Admin only)
     */
    public function index(Request $request): View
    {
        // Hanya admin yang bisa mengakses daftar semua perusahaan
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melihat daftar perusahaan.');
        }

        $query = Perusahaan::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%")
                    ->orWhere('no_registrasi', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by jenis usaha
        if ($request->filled('jenis_usaha')) {
            $query->where('jenis_usaha', $request->jenis_usaha);
        }

        // Filter by status user
        if ($request->filled('status_user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status_user);
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('created_at', '<=', $request->tanggal_sampai . ' 23:59:59');
        }

        $perusahaans = $query->latest()->paginate(15)->withQueryString();

        // Data untuk filter
        $jenisUsahaOptions = [
            'manufaktur' => 'Manufaktur',
            'jasa' => 'Jasa',
            'dagang' => 'Dagang',
            'pertanian' => 'Pertanian',
            'pertambangan' => 'Pertambangan',
            'konstruksi' => 'Konstruksi',
            'teknologi' => 'Teknologi',
            'lainnya' => 'Lainnya'
        ];

        $statusUserOptions = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif'
        ];

        return view('perusahaan.index', compact(
            'perusahaans',
            'jenisUsahaOptions',
            'statusUserOptions'
        ));
    }

    /**
     * Display the specified perusahaan
     */
    public function show(Perusahaan $perusahaan): View
    {
        $user = Auth::user();

        // Admin bisa melihat semua perusahaan, perusahaan hanya bisa melihat miliknya sendiri
        if (!$user->isAdmin() && (!$user->isPerusahaan() || $perusahaan->user_id !== $user->id)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil perusahaan ini.');
        }

        // Load relationships
        $perusahaan->load(['user']);

        // Get statistics untuk perusahaan ini (hanya untuk admin atau pemilik)
        $statistics = [];
        if ($user->isAdmin() || ($user->isPerusahaan() && $perusahaan->user_id === $user->id)) {
            $statistics = [
                'total_laporan_harian' => LaporanHarian::where('perusahaan_id', $perusahaan->id)->count(),
                'total_pengelolaan' => PengelolaanLimbah::where('perusahaan_id', $perusahaan->id)->count(),
                'total_laporan_hasil' => LaporanHasilPengelolaan::where('perusahaan_id', $perusahaan->id)->count(),
                'total_penyimpanan' => Penyimpanan::where('perusahaan_id', $perusahaan->id)->count(),
                'laporan_draft' => LaporanHarian::where('perusahaan_id', $perusahaan->id)
                    ->where('status', 'draft')->count(),
                'pengelolaan_aktif' => PengelolaanLimbah::where('perusahaan_id', $perusahaan->id)
                    ->whereIn('status', ['diproses', 'berlangsung'])->count(),
            ];
        }

        return view('perusahaan.show', compact('perusahaan', 'statistics'));
    }

    public function create(): View|RedirectResponse
    {
        // Only company users can create profiles
        if (Auth::user()->isAdmin()) {
            abort(403);
        }

        if (Auth::user()->perusahaan) {
            return redirect()->route('perusahaan.dashboard')
                ->with('error', 'Profil perusahaan sudah ada.');
        }
        return view('perusahaan.create');
    }

    /**
     * Store a newly created company profile.
     */
    public function store(ProfilPerusahaanRequest $request): RedirectResponse
    {
        // Only company users can create profiles
        if (Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logo-perusahaan', 'public');
        }

        $validated['user_id'] = auth()->id();

        try {
            $perusahaan = Perusahaan::create($validated);

            // Notifications
            NotificationHelper::perusahaanRegistered($perusahaan);
            NotificationHelper::welcomeNewUser(auth()->user());

            return redirect()->route('perusahaan.dashboard')
                ->with('success', 'Profil perusahaan berhasil dibuat.');
        } catch (\Exception $e) {
            \Log::error('Error creating perusahaan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')->withInput();
        }
    }

    public function edit(Perusahaan $perusahaan): View
    {
        $user = Auth::user();

        // Pastikan user hanya bisa edit perusahaan miliknya sendiri
        if (!$user->isPerusahaan() || $perusahaan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit profil perusahaan ini.');
        }

        return view('perusahaan.edit', compact('perusahaan'));
    }

    public function update(ProfilPerusahaanRequest $request, Perusahaan $perusahaan): RedirectResponse
    {
        $user = Auth::user();

        // Pastikan user hanya bisa update perusahaan miliknya sendiri
        if (!$user->isPerusahaan() || $perusahaan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit profil perusahaan ini.');
        }

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($perusahaan->logo) {
                Storage::disk('public')->delete($perusahaan->logo);
            }

            $validated['logo'] = $request->file('logo')->store('logo-perusahaan', 'public');
        }

        try {
            $perusahaan->update($validated);

            NotificationHelper::perusahaanUpdated($perusahaan);

            return redirect()->route('perusahaan.show', $perusahaan)
                ->with('success', 'Profil perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating perusahaan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.')->withInput();
        }
    }
}
