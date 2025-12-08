<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\View\View;
use App\Models\JenisLimbah;
use App\Models\Penyimpanan;
use Illuminate\Http\Request;
use App\Models\PengelolaanLimbah;
use App\Services\PengelolaanLimbahService;
use App\Http\Requests\StorePengelolaanLimbahRequest;
use App\Traits\HasBulkActions;
use App\Traits\HasExport;
use App\Traits\HasFilters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class PengelolaanLimbahController extends Controller
{
    use HasBulkActions, HasExport, HasFilters;

    protected $pengelolaanService;

    public function __construct(PengelolaanLimbahService $pengelolaanService)
    {
        $this->pengelolaanService = $pengelolaanService;
    }

    /**
     * Display a listing of pengelolaan limbah
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        $query = PengelolaanLimbah::with(['jenisLimbah', 'penyimpanan', 'vendor'])
            ->where('perusahaan_id', $user->perusahaan->id);

        // Apply filters using trait
        $filters = [
            'search' => ['nomor_manifest', 'catatan', 'jenisLimbah.nama', 'vendor.nama_perusahaan'],
            'date_field' => 'tanggal_mulai',
            'status_field' => 'status'
        ];

        $query = $this->applyFilters($query, $request, $filters);

        // Additional specific filters
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        if ($request->filled('jenis_pengelolaan')) {
            $query->where('jenis_pengelolaan', $request->jenis_pengelolaan);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $pengelolaanLimbah = $query->latest('tanggal_mulai')->paginate(15)->withQueryString();

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        return view('pengelolaan-limbah.index', compact('pengelolaanLimbah') + $filterOptions);
    }

    /**
     * Show the form for creating a new pengelolaan
     */
    public function create(): View
    {
        $user = Auth::user();

        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('error', 'Silakan lengkapi profil perusahaan terlebih dahulu.');
        }

        // Get jenis limbah yang memiliki stok di penyimpanan
        $jenisLimbahs = JenisLimbah::whereHas('penyimpanans', function ($query) use ($user) {
            $query->where('perusahaan_id', $user->perusahaan->id)
                ->where('status', 'aktif')
                ->where('kapasitas_terpakai', '>', 0);
        })->with(['penyimpanans' => function ($query) use ($user) {
            $query->where('perusahaan_id', $user->perusahaan->id)
                ->where('status', 'aktif')
                ->where('kapasitas_terpakai', '>', 0);
        }])->get();

        // Add total stok for each jenis limbah
        $jenisLimbahs->each(function ($jenisLimbah) use ($user) {
            $jenisLimbah->total_stok_tersedia = $jenisLimbah->penyimpanans
                ->where('perusahaan_id', $user->perusahaan->id)
                ->sum('kapasitas_terpakai');
        });

        $vendors = Vendor::where('status', 'aktif')->get();

        return view('pengelolaan-limbah.create', compact('jenisLimbahs', 'vendors'));
    }

    /**
     * Store a newly created pengelolaan
     */
    public function store(StorePengelolaanLimbahRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('error', 'Silakan lengkapi profil perusahaan terlebih dahulu.');
        }

        try {
            $validated = $request->validated();

            // Debug log
            \Log::info('Store pengelolaan limbah request', [
                'validated_data' => $validated,
                'user_id' => $user->id,
                'perusahaan_id' => $user->perusahaan->id
            ]);

            $pengelolaan = $this->pengelolaanService->create($validated, $user->perusahaan->id);

            return redirect()->route('pengelolaan-limbah.index')
                ->with('success', 'Pengelolaan limbah berhasil dibuat dengan nomor manifest: ' . $pengelolaan->nomor_manifest);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in store pengelolaan', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating pengelolaan limbah', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Gagal membuat pengelolaan limbah: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified pengelolaan
     */
    public function show(PengelolaanLimbah $pengelolaanLimbah): View
    {
        $user = Auth::user();

        if ($pengelolaanLimbah->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        $pengelolaanLimbah->load(['jenisLimbah', 'penyimpanan', 'vendor', 'perusahaan']);

        return view('pengelolaan-limbah.show', compact('pengelolaanLimbah'));
    }

    /**
     * Show the form for editing the specified pengelolaan
     */
    public function edit(PengelolaanLimbah $pengelolaanLimbah): View
    {
        $user = Auth::user();

        // Check ownership
        if ($pengelolaanLimbah->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if can edit
        if (!$pengelolaanLimbah->canEdit()) {
            return redirect()->route('pengelolaan-limbah.show', $pengelolaanLimbah)
                ->with('error', 'Pengelolaan limbah ini tidak dapat diedit karena sudah dalam proses atau selesai.');
        }

        // Get penyimpanan yang sesuai dengan jenis limbah
        $penyimpanans = Penyimpanan::where('perusahaan_id', $user->perusahaan->id)
            ->where('jenis_limbah_id', $pengelolaanLimbah->jenis_limbah_id)
            ->where('status', 'aktif')
            ->get();

        $vendors = Vendor::where('status', 'aktif')->get();

        return view('pengelolaan-limbah.edit', compact(
            'pengelolaanLimbah',
            'penyimpanans',
            'vendors'
        ));
    }

    /**
     * Update the specified pengelolaan
     */
    public function update(StorePengelolaanLimbahRequest $request, PengelolaanLimbah $pengelolaanLimbah): RedirectResponse
    {
        $user = Auth::user();

        // Check ownership
        if ($pengelolaanLimbah->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$pengelolaanLimbah->canEdit()) {
            return redirect()->route('pengelolaan-limbah.show', $pengelolaanLimbah)
                ->with('error', 'Pengelolaan limbah ini tidak dapat diedit.');
        }

        try {
            $validated = $request->validated();
            $this->pengelolaanService->update($pengelolaanLimbah, $validated);

            return redirect()->route('pengelolaan-limbah.index')
                ->with('success', 'Pengelolaan limbah berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update status pengelolaan
     */
    public function updateStatus(Request $request, PengelolaanLimbah $pengelolaanLimbah): RedirectResponse
    {
        $user = Auth::user();

        if ($pengelolaanLimbah->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:diproses,dalam_pengangkutan,selesai,dibatalkan'
        ]);

        try {
            $this->pengelolaanService->updateStatus($pengelolaanLimbah, $request->status);

            return redirect()->route('pengelolaan-limbah.show', $pengelolaanLimbah)
                ->with('success', 'Status pengelolaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pengelolaan
     */
    public function destroy(PengelolaanLimbah $pengelolaanLimbah): RedirectResponse
    {
        $user = Auth::user();

        // Check ownership
        if ($pengelolaanLimbah->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$pengelolaanLimbah->canEdit()) {
            return redirect()->route('pengelolaan-limbah.show', $pengelolaanLimbah)
                ->with('error', 'Pengelolaan limbah ini tidak dapat dihapus.');
        }

        try {
            // Return stock to penyimpanan before delete
            $penyimpanan = $pengelolaanLimbah->penyimpanan;
            $penyimpanan->kapasitas_terpakai += $pengelolaanLimbah->jumlah_dikelola;
            $penyimpanan->save();

            $pengelolaanLimbah->delete();

            return redirect()->route('pengelolaan-limbah.index')
                ->with('success', 'Pengelolaan limbah berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengelolaan: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        return $this->handleBulkAction($request, PengelolaanLimbah::class, ['delete', 'update_status']);
    }

    /**
     * Execute bulk action for specific item
     */
    protected function executeBulkAction($action, $item)
    {
        switch ($action) {
            case 'delete':
                if ($item->canEdit()) {
                    // Return stock to penyimpanan before delete
                    $penyimpanan = $item->penyimpanan;
                    $penyimpanan->kapasitas_terpakai += $item->jumlah_dikelola;
                    $penyimpanan->save();

                    $item->delete();
                } else {
                    throw new \Exception('Item tidak dapat dihapus');
                }
                break;
            case 'update_status':
                $this->pengelolaanService->updateStatus($item, request('new_status'));
                break;
        }
    }

    /**
     * Export data pengelolaan limbah
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = PengelolaanLimbah::with(['jenisLimbah', 'penyimpanan', 'vendor'])
            ->where('perusahaan_id', $user->perusahaan->id);

        // Apply same filters as index
        $filters = [
            'search' => ['nomor_manifest', 'catatan', 'jenisLimbah.nama'],
            'date_field' => 'tanggal_mulai',
            'status_field' => 'status'
        ];

        $query = $this->applyFilters($query, $request, $filters);

        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        if ($request->filled('jenis_pengelolaan')) {
            $query->where('jenis_pengelolaan', $request->jenis_pengelolaan);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $data = $query->latest('tanggal_mulai')->get();
        $period = $this->generatePeriodText($request);
        $filename = 'pengelolaan-limbah-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return $this->exportToCsv($data, $filename, ['period' => $period]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $user = Auth::user();

        if (!$user->hasValidPerusahaan()) {
            return response()->json(['error' => 'Perusahaan tidak valid'], 400);
        }

        $statistics = $this->pengelolaanService->getStatistics($user->perusahaan->id);

        return response()->json($statistics);
    }

    /**
     * API method untuk mendapatkan info stok saat edit
     */
    public function getStokInfo(PengelolaanLimbah $pengelolaanLimbah)
    {
        if ($pengelolaanLimbah->perusahaan_id !== auth()->user()->perusahaan->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $totalStok = Penyimpanan::where('perusahaan_id', auth()->user()->perusahaan->id)
            ->where('jenis_limbah_id', $pengelolaanLimbah->jenis_limbah_id)
            ->where('status', 'aktif')
            ->sum('kapasitas_terpakai');

        return response()->json([
            'total_stok' => $totalStok,
            'satuan' => $pengelolaanLimbah->satuan,
            'jenis_limbah' => $pengelolaanLimbah->jenisLimbah->nama
        ]);
    }

    /**
     * API untuk mendapatkan detail jenis limbah
     */
    public function getJenisLimbahDetails(Request $request)
    {
        try {
            $jenisLimbahId = $request->get('jenis_limbah_id');
            $perusahaanId = auth()->user()->perusahaan->id;

            $jenisLimbah = JenisLimbah::with(['penyimpanans' => function ($query) use ($perusahaanId) {
                $query->where('perusahaan_id', $perusahaanId)
                    ->where('status', 'aktif')
                    ->where('kapasitas_terpakai', '>', 0);
            }])->find($jenisLimbahId);

            if (!$jenisLimbah) {
                return response()->json(['error' => 'Jenis limbah tidak ditemukan'], 404);
            }

            $totalStok = $jenisLimbah->penyimpanans->sum('kapasitas_terpakai');

            return response()->json([
                'id' => $jenisLimbah->id,
                'nama' => $jenisLimbah->nama,
                'kode_limbah' => $jenisLimbah->kode_limbah,
                'satuan_default' => $jenisLimbah->satuan_default,
                'total_stok' => $totalStok,
                'penyimpanans' => $jenisLimbah->penyimpanans->map(function ($penyimpanan) {
                    return [
                        'id' => $penyimpanan->id,
                        'nama_penyimpanan' => $penyimpanan->nama_penyimpanan,
                        'lokasi' => $penyimpanan->lokasi,
                        'kapasitas_terpakai' => $penyimpanan->kapasitas_terpakai,
                        'satuan' => $penyimpanan->satuan
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get penyimpanan by jenis limbah
     */
    public function getPenyimpananByJenisLimbah(Request $request)
    {
        try {
            $jenisLimbahId = $request->get('jenis_limbah_id');
            $perusahaanId = auth()->user()->perusahaan->id;

            $penyimpanans = Penyimpanan::with('jenisLimbah')
                ->where('perusahaan_id', $perusahaanId)
                ->where('jenis_limbah_id', $jenisLimbahId)
                ->where('status', 'aktif')
                ->where('kapasitas_terpakai', '>', 0)
                ->get();

            $result = $penyimpanans->map(function ($penyimpanan) {
                return [
                    'id' => $penyimpanan->id,
                    'nama_penyimpanan' => $penyimpanan->nama_penyimpanan,
                    'lokasi' => $penyimpanan->lokasi,
                    'kapasitas_terpakai' => $penyimpanan->kapasitas_terpakai,
                    'satuan' => $penyimpanan->jenisLimbah->satuan_default
                ];
            });
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Format row for CSV export (required by trait)
     */
    protected function formatRowForCsv($item): array
    {
        return [
            $item->tanggal_mulai->format('d/m/Y'),
            $item->jenisLimbah->nama,
            $item->jenisLimbah->kode_limbah,
            $item->penyimpanan->nama_penyimpanan,
            $item->penyimpanan->lokasi,
            number_format($item->jumlah_dikelola, 2),
            $item->satuan,
            $item->jenis_pengelolaan_name,
            $item->metode_pengelolaan_name,
            $item->status_name,
            $item->vendor ? $item->vendor->nama_perusahaan : '-',
            $item->biaya ? 'Rp ' . number_format($item->biaya, 2) : '-',
            $item->nomor_manifest ?? '-',
            $item->catatan ?? '-'
        ];
    }

    /**
     * Get CSV headers (required by trait)
     */
    protected function getCsvHeaders(): array
    {
        return [
            'Tanggal Mulai Pengelolaan',
            'Jenis Limbah',
            'Kode Limbah',
            'Penyimpanan',
            'Lokasi',
            'Jumlah Dikelola',
            'Satuan',
            'Jenis Pengelolaan',
            'Metode Pengelolaan',
            'Status',
            'Vendor',
            'Biaya',
            'Nomor Manifest',
            'Catatan'
        ];
    }

    /**
     * Generate period text for export
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
     * Get filter options for view
     */
    private function getFilterOptions(): array
    {
        $jenisLimbahOptions = JenisLimbah::pluck('nama', 'id');
        $jenisOptions = PengelolaanLimbah::getJenisPengelolaanOptions();
        $statusOptions = PengelolaanLimbah::getStatusOptions();
        $vendorOptions = Vendor::where('status', 'aktif')->pluck('nama_perusahaan', 'id');

        return compact('jenisLimbahOptions', 'jenisOptions', 'statusOptions', 'vendorOptions');
    }
}
