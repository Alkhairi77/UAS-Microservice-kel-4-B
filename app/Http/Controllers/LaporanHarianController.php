<?php

namespace App\Http\Controllers;

use App\Models\LaporanHarian;
use App\Models\JenisLimbah;
use App\Models\Penyimpanan;
use App\Services\LaporanHarianService;
use App\Traits\HasBulkActions;
use App\Traits\HasExport;
use App\Traits\HasFilters;
use App\Http\Requests\StoreLaporanHarianRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LaporanHarianController extends Controller
{
    use HasBulkActions, HasExport, HasFilters;

    protected $laporanService;

    public function __construct(LaporanHarianService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    /**
     * Display a listing of laporan harian
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = LaporanHarian::with(['jenisLimbah', 'penyimpanan', 'perusahaan']);

        // Filter by company for non-admin users
        if ($user->isPerusahaan()) {
            $query->where('perusahaan_id', $user->perusahaan->id);
        }

        // Apply filters using trait
        $filters = [
            'search' => ['jenisLimbah.nama', 'jenisLimbah.kode_limbah', 'penyimpanan.nama_penyimpanan', 'keterangan'],
            'date_field' => 'tanggal',
            'status_field' => 'status'
        ];

        if ($user->isAdmin()) {
            $filters['search'][] = 'perusahaan.nama_perusahaan';
        }

        $query = $this->applyFilters($query, $request, $filters);

        // Additional specific filters
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        if ($request->filled('penyimpanan_id')) {
            $query->where('penyimpanan_id', $request->penyimpanan_id);
        }

        $laporan = $query->latest('tanggal')->paginate(15)->withQueryString();

        // Get filter options
        $filterOptions = $this->getFilterOptions($user);

        return view('laporan-harian.index', compact('laporan') + $filterOptions);
    }

    /**
     * Show the form for creating a new laporan
     */
    public function create(): View
    {
        $user = Auth::user();

        // Manual authorization check
        if (!$user->isPerusahaan()) {
            abort(403, 'Hanya perusahaan yang dapat membuat laporan harian.');
        }

        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        $jenisLimbahs = JenisLimbah::where('status', 'active')->orderBy('nama')->get();
        $penyimpanans = Penyimpanan::where('perusahaan_id', auth()->user()->perusahaan->id)
            ->where('status', 'aktif')
            ->with('jenisLimbah')
            ->orderBy('nama_penyimpanan')
            ->get();

        return view('laporan-harian.create', compact('jenisLimbahs', 'penyimpanans'));
    }

    /**
     * Store a newly created laporan
     */
    public function store(StoreLaporanHarianRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isPerusahaan()) {
            abort(403, 'Hanya perusahaan yang dapat membuat laporan harian.');
        }

        try {
            $validated = $request->validated();
            
            // Determine status based on action
            $status = $this->determineStatus($request);
            $validated['status'] = $status;
            
            $laporan = $this->laporanService->create($validated, auth()->user()->perusahaan->id);

            $message = $status === 'submitted' ?
                'Laporan harian berhasil dibuat dan disubmit.' :
                'Laporan harian berhasil disimpan sebagai draft.';

            return redirect()->route('laporan-harian.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified laporan
     */
    public function show(LaporanHarian $laporanHarian): View
    {
        $user = Auth::user();

        // Manual authorization check
        if ($user->isPerusahaan() && $laporanHarian->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $laporanHarian->load(['jenisLimbah', 'penyimpanan', 'perusahaan']);

        return view('laporan-harian.show', compact('laporanHarian'));
    }

    /**
     * Show the form for editing the specified laporan
     */
    public function edit(LaporanHarian $laporanHarian): View
    {
        $$user = Auth::user();

        // Manual authorization check
        if (!$user->isPerusahaan() || $laporanHarian->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        if ($laporanHarian->status !== 'draft') {
            return redirect()->route('laporan-harian.show', $laporanHarian)
                ->with('error', 'Hanya laporan dengan status draft yang dapat diedit.');
        }

        $jenisLimbahs = JenisLimbah::where('status', 'active')->orderBy('nama')->get();
        $penyimpanans = Penyimpanan::where('perusahaan_id', auth()->user()->perusahaan->id)
            ->where('status', 'aktif')
            ->with('jenisLimbah')
            ->orderBy('nama_penyimpanan')
            ->get();

        return view('laporan-harian.edit', compact('laporanHarian', 'jenisLimbahs', 'penyimpanans'));
    }

    /**
     * Update the specified laporan
     */
    public function update(StoreLaporanHarianRequest $request, LaporanHarian $laporanHarian): RedirectResponse
    {
        $$user = Auth::user();

        // Manual authorization check
        if (!$user->isPerusahaan() || $laporanHarian->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        if ($laporanHarian->status !== 'draft') {
            return redirect()->route('laporan-harian.show', $laporanHarian)
                ->with('error', 'Hanya laporan dengan status draft yang dapat diedit.');
        }

        try {
            $validated = $request->validated();
            
            // Determine status based on action
            $status = $this->determineStatus($request);
            $validated['status'] = $status;
            
            $this->laporanService->update($laporanHarian, $validated);

            $message = $status === 'submitted' ?
                'Laporan harian berhasil diperbarui dan disubmit.' :
                'Laporan harian berhasil diperbarui.';

            return redirect()->route('laporan-harian.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified laporan
     */
    public function destroy(LaporanHarian $laporanHarian): RedirectResponse
    {
        $user = Auth::user();

        // Manual authorization check
        if ($laporanHarian->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$laporanHarian->canDelete()) {
            return redirect()->route('laporan-harian.index')
                ->with('error', 'Laporan yang sudah disubmit tidak dapat dihapus.');
        }

        try {
            $laporanHarian->delete();
            return redirect()->route('laporan-harian.index')
                ->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('laporan-harian.index')
                ->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Submit laporan
     */
    public function submit(LaporanHarian $laporanHarian): RedirectResponse
    {
        $user = Auth::user();

        // Manual authorization check
        if ($laporanHarian->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $this->laporanService->submit($laporanHarian);
            return redirect()->route('laporan-harian.index')
                ->with('success', 'Laporan berhasil disubmit.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        return $this->handleBulkAction($request, LaporanHarian::class, ['delete', 'submit']);
    }

    /**
     * Execute bulk action for specific item
     */
    protected function executeBulkAction($action, $item)
    {
        switch ($action) {
            case 'delete':
                if ($item->canDelete()) {
                    $item->delete();
                } else {
                    throw new \Exception('Item tidak dapat dihapus');
                }
                break;
            case 'submit':
                if ($item->canSubmit()) {
                    $this->laporanService->submit($item);
                } else {
                    throw new \Exception('Item tidak dapat disubmit');
                }
                break;
        }
    }

    /**
     * Export laporan to PDF
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = LaporanHarian::with(['jenisLimbah', 'penyimpanan', 'perusahaan']);

        if ($user->isPerusahaan()) {
            $query->where('perusahaan_id', $user->perusahaan->id);
        }

        // Apply same filters as index
        $filters = ['date_field' => 'tanggal', 'status_field' => 'status'];
        $query = $this->applyFilters($query, $request, $filters);

        // Additional filters
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        if ($request->filled('penyimpanan_id')) {
            $query->where('penyimpanan_id', $request->penyimpanan_id);
        }

        $laporan = $query->latest('tanggal')->get();
        $period = $this->generatePeriodText($request);
        $filename = 'laporan-harian-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $this->exportToPdf($laporan, 'exports.laporan-harian-pdf', $filename, ['period' => $period]);
    }

    /**
     * API untuk mendapatkan penyimpanan berdasarkan jenis limbah
     */
    public function getPenyimpananByJenisLimbah(Request $request)
    {
        try {
            $jenisLimbahId = $request->get('jenis_limbah_id');
            $user = auth()->user();

            if (!$user->perusahaan) {
                return response()->json(['error' => 'Perusahaan tidak ditemukan'], 400);
            }

            $penyimpanans = Penyimpanan::with('jenisLimbah')
                ->where('perusahaan_id', $user->perusahaan->id)
                ->where('jenis_limbah_id', $jenisLimbahId)
                ->where('status', 'aktif')
                ->get();

            $result = $penyimpanans->map(function ($penyimpanan) {
                $sisaKapasitas = $penyimpanan->kapasitas_maksimal - $penyimpanan->kapasitas_terpakai;
                $persentaseKapasitas = $penyimpanan->kapasitas_maksimal > 0 ?
                    ($penyimpanan->kapasitas_terpakai / $penyimpanan->kapasitas_maksimal * 100) : 0;

                return [
                    'id' => $penyimpanan->id,
                    'nama_penyimpanan' => $penyimpanan->nama_penyimpanan,
                    'lokasi' => $penyimpanan->lokasi,
                    'kapasitas_maksimal' => (float) $penyimpanan->kapasitas_maksimal,
                    'kapasitas_terpakai' => (float) $penyimpanan->kapasitas_terpakai,
                    'sisa_kapasitas' => (float) $sisaKapasitas,
                    'satuan' => $penyimpanan->jenisLimbah->satuan_default,
                    'persentase_kapasitas' => round($persentaseKapasitas, 1),
                    'can_accommodate' => $sisaKapasitas > 0
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error in getPenyimpananByJenisLimbah: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get jenis limbah info (AJAX)
     */
    public function getJenisLimbahInfo(Request $request)
    {
        $jenisLimbah = JenisLimbah::find($request->jenis_limbah_id);

        if (!$jenisLimbah) {
            return response()->json(['error' => 'Jenis limbah tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $jenisLimbah->id,
            'nama' => $jenisLimbah->nama,
            'kode_limbah' => $jenisLimbah->kode_limbah,
            'kategori' => $jenisLimbah->kategori,
            'kategori_name' => $jenisLimbah->kategori_name,
            'satuan_default' => $jenisLimbah->satuan_default,
            'satuan_name' => $jenisLimbah->satuan_name,
            'tingkat_bahaya' => $jenisLimbah->tingkat_bahaya,
            'tingkat_bahaya_name' => $jenisLimbah->tingkat_bahaya_name,
            'deskripsi' => $jenisLimbah->deskripsi
        ]);
    }

    /**
     * Get laporan statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        $user = Auth::user();
        $perusahaanId = $user->perusahaan->id;
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $statistics = [
            'total_laporan' => LaporanHarian::where('perusahaan_id', $perusahaanId)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count(),
            'laporan_draft' => LaporanHarian::where('perusahaan_id', $perusahaanId)
                ->where('status', 'draft')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count(),
            'laporan_submitted' => LaporanHarian::where('perusahaan_id', $perusahaanId)
                ->where('status', 'submitted')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count(),
            'total_limbah' => LaporanHarian::where('perusahaan_id', $perusahaanId)
                ->where('status', 'submitted')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('jumlah'),
            'jenis_limbah_terbanyak' => LaporanHarian::where('perusahaan_id', $perusahaanId)
                ->where('status', 'submitted')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->with('jenisLimbah')
                ->get()
                ->groupBy('jenis_limbah_id')
                ->map(function ($group) {
                    return [
                        'jenis_limbah' => $group->first()->jenisLimbah->nama,
                        'total' => $group->sum('jumlah'),
                        'satuan' => $group->first()->satuan
                    ];
                })
                ->sortByDesc('total')
                ->take(5)
                ->values()
        ];

        return response()->json($statistics);
    }

    /**
     * Determine status based on request action
     */
    private function determineStatus(Request $request): string
    {
        if ($request->has('action')) {
            return $request->action === 'submit' ? 'submitted' : 'draft';
        } elseif ($request->has('submit')) {
            return 'submitted';
        }
        return 'draft';
    }

    /**
     * Generate period text for PDF
     */
    private function generatePeriodText(Request $request): string
    {
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $dari = \Carbon\Carbon::parse($request->tanggal_dari)->format('d F Y');
            $sampai = \Carbon\Carbon::parse($request->tanggal_sampai)->format('d F Y');
            return "{$dari} - {$sampai}";
        } elseif ($request->filled('tanggal_dari')) {
            $dari = \Carbon\Carbon::parse($request->tanggal_dari)->format('d F Y');
            return "Mulai {$dari}";
        } elseif ($request->filled('tanggal_sampai')) {
            $sampai = \Carbon\Carbon::parse($request->tanggal_sampai)->format('d F Y');
            return "Sampai {$sampai}";
        }
        return "Semua Data";
    }

    /**
     * Format row for CSV export (required by trait)
     */
    protected function formatRowForCsv($item): array
    {
        $row = [
            $item->tanggal->format('d/m/Y'),
            $item->jenisLimbah->nama,
            $item->jenisLimbah->kode_limbah,
            $item->penyimpanan->nama_penyimpanan,
            $item->penyimpanan->lokasi,
            $item->jumlah,
            $item->satuan,
            $item->status === 'draft' ? 'Draft' : 'Submitted',
            $item->keterangan ?: '-',
            $item->created_at->format('d/m/Y H:i')
        ];

        // Add company name for admin
        if (auth()->user()->isAdmin()) {
            array_splice($row, 1, 0, $item->perusahaan->nama_perusahaan);
        }

        return $row;
    }

    /**
     * Get filter options for view
     */
    private function getFilterOptions($user): array
    {
        $jenisLimbahOptions = JenisLimbah::pluck('nama', 'id');
        $statusOptions = LaporanHarian::getStatusOptions();

        $perusahaanOptions = [];
        if ($user->isAdmin()) {
            $perusahaanOptions = \App\Models\Perusahaan::orderBy('nama_perusahaan')->pluck('nama_perusahaan', 'id');
        }

        $penyimpananOptions = [];
        if ($user->isPerusahaan()) {
            $penyimpananOptions = Penyimpanan::where('perusahaan_id', $user->perusahaan->id)
                ->pluck('nama_penyimpanan', 'id');
        } else {
            $penyimpananOptions = Penyimpanan::with('perusahaan')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->id => $item->nama_penyimpanan . ' (' . $item->perusahaan->nama_perusahaan . ')'];
                });
        }

        return compact('jenisLimbahOptions', 'penyimpananOptions', 'statusOptions', 'perusahaanOptions');
    }
}

