<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasilPengelolaan;
use App\Models\PengelolaanLimbah;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanHasilPengelolaanController extends Controller
{
    /**
     * Display a listing of laporan hasil pengelolaan
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin dapat melihat semua laporan
            $query = LaporanHasilPengelolaan::with([
                'perusahaan', 
                'pengelolaanLimbah.jenisLimbah', 
                'pengelolaanLimbah.penyimpanan',
                'pengelolaanLimbah.vendor'
            ]);
        } else {
            // Perusahaan hanya melihat laporan miliknya
            $query = LaporanHasilPengelolaan::with([
                'pengelolaanLimbah.jenisLimbah',
                'pengelolaanLimbah.penyimpanan', 
                'pengelolaanLimbah.vendor'
            ])->where('perusahaan_id', $user->perusahaan->id);
        }

        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pengelolaanLimbah.jenisLimbah', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_limbah', 'like', "%{$search}%");
                })
                ->orWhereHas('pengelolaanLimbah.vendor', function ($subQ) use ($search) {
                    $subQ->where('nama_perusahaan', 'like', "%{$search}%");
                })
                ->orWhereHas('perusahaan', function ($subQ) use ($search) {
                    $subQ->where('nama_perusahaan', 'like', "%{$search}%");
                })
                ->orWhere('catatan_hasil', 'like', "%{$search}%");

                if (Auth::user()->isAdmin()) {
                    $q->orWhereHas('perusahaan', function ($subQ) use ($search) {
                        $subQ->where('nama_perusahaan', 'like', "%{$search}%");
                    });
                }
            });
        }

        // Filter by perusahaan (hanya untuk admin)
        if ($user->isAdmin() && $request->filled('perusahaan_id')) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        // Filter by jenis limbah
        if ($request->filled('jenis_limbah_id')) {
            $query->whereHas('pengelolaanLimbah', function ($q) use ($request) {
                $q->where('jenis_limbah_id', $request->jenis_limbah_id);
            });
        }

        // Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->whereHas('pengelolaanLimbah', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            });
        }

        // Filter by status hasil
        if ($request->filled('status_hasil')) {
            $query->where('status_hasil', $request->status_hasil);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_selesai', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        $laporanHasil = $query->latest('tanggal_selesai')->paginate(15)->withQueryString();

        // Data untuk filter
        $jenisLimbahOptions = \App\Models\JenisLimbah::pluck('nama', 'id');
        $vendorOptions = \App\Models\Vendor::pluck('nama_perusahaan', 'id');
        $statusOptions = LaporanHasilPengelolaan::getStatusHasilOptions();
        
        // Filter options berdasarkan role
        if ($user->isAdmin()) {
            $perusahaanOptions = Perusahaan::pluck('nama_perusahaan', 'id');
        } else {
            $perusahaanOptions = collect(); // Kosong untuk perusahaan
        }

        return view('laporan-hasil-pengelolaan.index', compact(
            'laporanHasil',
            'jenisLimbahOptions',
            'vendorOptions',
            'statusOptions',
            'perusahaanOptions'
        ));
    }

    /**
     * Show the form for creating a new laporan (Perusahaan only)
     */
    public function create(): View
    {
        $user = Auth::user();
        
        // Hanya perusahaan yang bisa create
        if (!$user->isPerusahaan()) {
            abort(403, 'Hanya perusahaan yang dapat membuat laporan hasil pengelolaan.');
        }

        // Get pengelolaan limbah yang sudah selesai tapi belum ada laporannya
        $pengelolaanLimbah = PengelolaanLimbah::with(['jenisLimbah', 'penyimpanan', 'vendor'])
            ->where('perusahaan_id', $user->perusahaan->id)
            ->where('status', 'selesai')
            ->whereDoesntHave('laporanHasilPengelolaan')
            ->get();

        return view('laporan-hasil-pengelolaan.create', compact('pengelolaanLimbah'));
    }

    /**
     * Store a newly created laporan
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Hanya perusahaan yang bisa store
        if (!$user->isPerusahaan()) {
            abort(403, 'Hanya perusahaan yang dapat membuat laporan hasil pengelolaan.');
        }

        // Validasi dengan perbaikan untuk dokumentasi
        $request->validate([
            'pengelolaan_limbah_id' => 'required|exists:pengelolaan_limbahs,id',
            'tanggal_selesai' => 'required|date|before_or_equal:today',
            'status_hasil' => 'required|in:berhasil,gagal,partial',
            'jumlah_berhasil_dikelola' => 'required|numeric|min:0',
            'jumlah_residu' => 'nullable|numeric|min:0',
            'metode_disposal_akhir' => 'nullable|string|max:255',
            'biaya_aktual' => 'nullable|numeric|min:0',
            'nomor_sertifikat' => 'nullable|string|max:255',
            'catatan_hasil' => 'nullable|string|max:2000',
            'dokumentasi' => 'nullable|array|max:5', // Maksimal 5 file
            'dokumentasi.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB per file
        ], [
            'dokumentasi.*.file' => 'Setiap dokumentasi harus berupa file.',
            'dokumentasi.*.mimes' => 'Format file dokumentasi harus PDF, JPG, JPEG, atau PNG.',
            'dokumentasi.*.max' => 'Ukuran file dokumentasi maksimal 5MB.',
            'dokumentasi.max' => 'Maksimal 5 file dokumentasi dapat diupload.',
        ]);

        try {
            DB::beginTransaction();

            // Validasi pengelolaan limbah milik perusahaan
            $pengelolaanLimbah = PengelolaanLimbah::where('id', $request->pengelolaan_limbah_id)
                ->where('perusahaan_id', $user->perusahaan->id)
                ->where('status', 'selesai')
                ->first();

            if (!$pengelolaanLimbah) {
                return back()->withInput()
                    ->with('error', 'Pengelolaan limbah tidak valid atau belum selesai.');
            }

            // Check if laporan already exists
            if ($pengelolaanLimbah->laporanHasilPengelolaan) {
                return back()->withInput()
                    ->with('error', 'Laporan hasil pengelolaan untuk pengelolaan ini sudah ada.');
            }

            $data = [
                'perusahaan_id' => $user->perusahaan->id,
                'pengelolaan_limbah_id' => $request->pengelolaan_limbah_id,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_hasil' => $request->status_hasil,
                'jumlah_berhasil_dikelola' => $request->jumlah_berhasil_dikelola,
                'jumlah_residu' => $request->jumlah_residu,
                'satuan' => $pengelolaanLimbah->satuan,
                'metode_disposal_akhir' => $request->metode_disposal_akhir,
                'biaya_aktual' => $request->biaya_aktual,
                'nomor_sertifikat' => $request->nomor_sertifikat,
                'catatan_hasil' => $request->catatan_hasil,
            ];

            // Handle file uploads dengan perbaikan
            $dokumentasi = [];
            if ($request->hasFile('dokumentasi')) {
                $files = $request->file('dokumentasi');
                
                // Pastikan $files adalah array
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                foreach ($files as $index => $file) {
                    // Cek apakah file valid dan tidak null
                    if ($file && $file->isValid()) {
                        try {
                            // Generate nama file yang unik
                            $originalName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = time() . '_' . $index . '_' . uniqid() . '.' . $extension;
                            
                            // Simpan file
                            $path = $file->storeAs('laporan-hasil-pengelolaan', $filename, 'public');
                            
                            if ($path) {
                                $dokumentasi[] = $path;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error uploading file: ' . $e->getMessage());
                            // Lanjutkan dengan file lainnya
                            continue;
                        }
                    }
                }
            }
            
            // Simpan path dokumentasi sebagai JSON jika ada file yang berhasil diupload
            $data['dokumentasi'] = !empty($dokumentasi) ? json_encode($dokumentasi) : null;

            // efisiensi_pengelolaan will be auto-calculated in model boot
            LaporanHasilPengelolaan::create($data);

            DB::commit();

            return redirect()->route('laporan-hasil-pengelolaan.index')
                ->with('success', 'Laporan hasil pengelolaan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika terjadi error
            if (!empty($dokumentasi)) {
                foreach ($dokumentasi as $filePath) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            
            \Log::error('Error creating laporan hasil pengelolaan: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menambahkan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified laporan
     */
    public function show(LaporanHasilPengelolaan $laporanHasilPengelolaan): View
    {
        $user = Auth::user();
        
        // Debug: log akses
        \Log::info('User accessing laporan hasil pengelolaan show:', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'laporan_id' => $laporanHasilPengelolaan->id,
            'laporan_perusahaan_id' => $laporanHasilPengelolaan->perusahaan_id
        ]);
        
        // Check access permission
        if ($user->isPerusahaan() && $laporanHasilPengelolaan->perusahaan_id !== $user->perusahaan->id) {
            \Log::warning('Unauthorized access attempt to laporan hasil pengelolaan', [
                'user_id' => $user->id,
                'laporan_id' => $laporanHasilPengelolaan->id
            ]);
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $laporanHasilPengelolaan->load([
            'pengelolaanLimbah.jenisLimbah',
            'pengelolaanLimbah.penyimpanan',
            'pengelolaanLimbah.vendor',
            'perusahaan'
        ]);
        
        return view('laporan-hasil-pengelolaan.show', compact('laporanHasilPengelolaan'));
    }

    /**
     * Show the form for editing (Perusahaan only)
     */
    public function edit(LaporanHasilPengelolaan $laporanHasilPengelolaan): View
    {
        $user = Auth::user();
        
        // Hanya perusahaan yang bisa edit dan hanya laporan mereka sendiri
        if (!$user->isPerusahaan() || $laporanHasilPengelolaan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        $laporanHasilPengelolaan->load([
            'pengelolaanLimbah.jenisLimbah',
            'pengelolaanLimbah.penyimpanan',
            'pengelolaanLimbah.vendor'
        ]);

        return view('laporan-hasil-pengelolaan.edit', compact('laporanHasilPengelolaan'));
    }

    /**
     * Update the specified laporan (Perusahaan only)
     */
    public function update(Request $request, LaporanHasilPengelolaan $laporanHasilPengelolaan): RedirectResponse
    {
        $user = Auth::user();
        
        // Hanya perusahaan yang bisa update dan hanya laporan mereka sendiri
        if (!$user->isPerusahaan() || $laporanHasilPengelolaan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate laporan ini.');
        }

        // Validasi dengan perbaikan untuk dokumentasi
        $request->validate([
            'tanggal_selesai' => 'required|date|before_or_equal:today',
            'status_hasil' => 'required|in:berhasil,gagal,partial',
            'jumlah_berhasil_dikelola' => 'required|numeric|min:0',
            'jumlah_residu' => 'nullable|numeric|min:0',
            'metode_disposal_akhir' => 'nullable|string|max:255',
            'biaya_aktual' => 'nullable|numeric|min:0',
            'nomor_sertifikat' => 'nullable|string|max:255',
            'catatan_hasil' => 'nullable|string|max:2000',
            'dokumentasi' => 'nullable|array|max:5', // Maksimal 5 file
            'dokumentasi.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB per file
            'hapus_dokumentasi' => 'nullable|boolean' // Untuk menghapus dokumentasi lama
        ], [
            'dokumentasi.*.file' => 'Setiap dokumentasi harus berupa file.',
            'dokumentasi.*.mimes' => 'Format file dokumentasi harus PDF, JPG, JPEG, atau PNG.',
            'dokumentasi.*.max' => 'Ukuran file dokumentasi maksimal 5MB.',
            'dokumentasi.max' => 'Maksimal 5 file dokumentasi dapat diupload.',
        ]);

        try {
            // Always update satuan if pengelolaan_limbah_id changes
            $pengelolaanLimbah = PengelolaanLimbah::where('id', $laporanHasilPengelolaan->pengelolaan_limbah_id)
                ->where('perusahaan_id', $user->perusahaan->id)
                ->where('status', 'selesai')
                ->first();
                
            if (!$pengelolaanLimbah) {
                return back()->withInput()->with('error', 'Pengelolaan limbah tidak valid atau belum selesai.');
            }

            $data = [
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_hasil' => $request->status_hasil,
                'jumlah_berhasil_dikelola' => $request->jumlah_berhasil_dikelola,
                'jumlah_residu' => $request->jumlah_residu,
                'satuan' => $pengelolaanLimbah->satuan,
                'metode_disposal_akhir' => $request->metode_disposal_akhir,
                'biaya_aktual' => $request->biaya_aktual,
                'nomor_sertifikat' => $request->nomor_sertifikat,
                'catatan_hasil' => $request->catatan_hasil,
            ];

            // Handle file uploads (replace old files atau tambah baru)
            $oldFiles = [];
            if ($laporanHasilPengelolaan->dokumentasi) {
                $oldFiles = json_decode($laporanHasilPengelolaan->dokumentasi, true) ?: [];
            }

            // Jika ada request untuk menghapus dokumentasi lama
            if ($request->has('hapus_dokumentasi') && $request->hapus_dokumentasi) {
                // Hapus file lama dari storage
                foreach ($oldFiles as $file) {
                    Storage::disk('public')->delete($file);
                }
                $oldFiles = [];
            }

            // Handle upload file baru
            if ($request->hasFile('dokumentasi')) {
                $newFiles = [];
                $files = $request->file('dokumentasi');
                
                // Pastikan $files adalah array
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                foreach ($files as $index => $file) {
                    // Cek apakah file valid dan tidak null
                    if ($file && $file->isValid()) {
                        try {
                            // Generate nama file yang unik
                            $originalName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = time() . '_' . $index . '_' . uniqid() . '.' . $extension;
                            
                            // Simpan file
                            $path = $file->storeAs('laporan-hasil-pengelolaan', $filename, 'public');
                            
                            if ($path) {
                                $newFiles[] = $path;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error uploading file: ' . $e->getMessage());
                            // Lanjutkan dengan file lainnya
                            continue;
                        }
                    }
                }
                
                // Jika mode replace (hapus dokumentasi lama dicentang), gunakan file baru saja
                if ($request->has('hapus_dokumentasi') && $request->hapus_dokumentasi) {
                    $data['dokumentasi'] = !empty($newFiles) ? json_encode($newFiles) : null;
                } else {
                    // Jika mode tambah, gabungkan dengan file lama
                    $allFiles = array_merge($oldFiles, $newFiles);
                    $data['dokumentasi'] = !empty($allFiles) ? json_encode($allFiles) : null;
                }
            }

            $laporanHasilPengelolaan->update($data);

            return redirect()->route('laporan-hasil-pengelolaan.index', $laporanHasilPengelolaan)
                ->with('success', 'Laporan hasil pengelolaan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Error updating laporan hasil pengelolaan: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified laporan (Perusahaan only)
     */
    public function destroy(LaporanHasilPengelolaan $laporanHasilPengelolaan): RedirectResponse
    {
        $user = Auth::user();
        
                // Hanya perusahaan yang bisa delete dan hanya laporan mereka sendiri
        if (!$user->isPerusahaan() || $laporanHasilPengelolaan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        try {
            // Delete uploaded files
            if ($laporanHasilPengelolaan->dokumentasi) {
                $files = $laporanHasilPengelolaan->dokumentasi;
                if (is_string($files)) {
                    $files = json_decode($files, true);
                }
                if (is_array($files)) {
                    foreach ($files as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }

            $laporanHasilPengelolaan->delete();

            return redirect()->route('laporan-hasil-pengelolaan.index')
                ->with('success', 'Laporan hasil pengelolaan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('laporan-hasil-pengelolaan.index')
                ->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumentasi file
     */
    public function downloadDokumentasi(LaporanHasilPengelolaan $laporanHasilPengelolaan, $index)
    {
        $user = Auth::user();

        // Check authorization - admin dan perusahaan pemilik bisa download
        if ($user->isPerusahaan() && $laporanHasilPengelolaan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$laporanHasilPengelolaan->dokumentasi) {
            abort(404, 'Tidak ada dokumentasi.');
        }

        $files = json_decode($laporanHasilPengelolaan->dokumentasi, true);
        
        if (!is_array($files) || !isset($files[$index])) {
            abort(404, 'File tidak ditemukan.');
        }

        $filePath = $files[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        // Get original filename untuk download
        $originalName = basename($filePath);
        
        return Storage::disk('public')->download($filePath, $originalName);
    }

    /**
     * Export laporan hasil to CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        // Base query
        $query = LaporanHasilPengelolaan::with([
            'pengelolaanLimbah.jenisLimbah',
            'pengelolaanLimbah.vendor',
            'perusahaan'
        ]);

        // Filter berdasarkan role
        if ($user->isPerusahaan()) {
            $query->where('perusahaan_id', $user->perusahaan->id);
        }

        // Apply same filters as index
        if ($request->filled('perusahaan_id') && $user->isAdmin()) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        if ($request->filled('status_hasil')) {
            $query->where('status_hasil', $request->status_hasil);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_selesai', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        $laporanHasil = $query->latest('tanggal_selesai')->get();

        $filename = 'laporan-hasil-pengelolaan-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($laporanHasil, $user) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            $csvHeaders = [
                'Tanggal Selesai',
                'Jenis Limbah',
                'Jumlah Berhasil Dikelola',
                'Jumlah Residu',
                'Status Hasil',
                'Vendor',
                'Catatan Hasil'
            ];

            // Tambah kolom perusahaan untuk admin
            if ($user->isAdmin()) {
                array_splice($csvHeaders, 1, 0, 'Perusahaan');
            }

            fputcsv($file, $csvHeaders);

            // Data
            foreach ($laporanHasil as $item) {
                $row = [
                    $item->tanggal_selesai->format('d/m/Y'),
                    $item->pengelolaanLimbah->jenisLimbah->nama,
                    number_format($item->jumlah_berhasil_dikelola, 2) . ' ' . $item->satuan,
                    number_format($item->jumlah_residu ?? 0, 2) . ' ' . $item->satuan,
                    ucfirst(str_replace('_', ' ', $item->status_hasil)),
                    $item->pengelolaanLimbah->vendor->nama_perusahaan ?? '-',
                    $item->catatan_hasil ?? '-'
                ];

                // Tambah data perusahaan untuk admin
                if ($user->isAdmin()) {
                    array_splice($row, 1, 0, $item->perusahaan->nama_perusahaan);
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dashboard/Summary data
     */
    

    /**
     * Get pengelolaan limbah yang bisa dibuat laporan hasil (AJAX)
     */
    public function getPengelolaanSelesai(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isPerusahaan()) {
            return response()->json([]);
        }
        
        $pengelolaanLimbah = PengelolaanLimbah::with(['jenisLimbah', 'penyimpanan'])
            ->where('perusahaan_id', $user->perusahaan->id)
            ->where('status', 'selesai')
            ->whereDoesntHave('laporanHasilPengelolaan')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'tanggal_mulai' => $item->tanggal_mulai->format('d/m/Y'),
                    'jenis_limbah' => $item->jenisLimbah->nama,
                    'jumlah_dikelola' => $item->jumlah_dikelola,
                    'satuan' => $item->satuan,
                    'penyimpanan' => $item->penyimpanan->nama_penyimpanan,
                    'jenis_pengelolaan' => $item->jenis_pengelolaan_name ?? 'Pengelolaan Limbah'
                ];
            });

        return response()->json($pengelolaanLimbah);
    }

    /**
     * Bulk actions for laporan hasil (Perusahaan only)
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user->isPerusahaan()) {
            abort(403, 'Hanya perusahaan yang dapat melakukan bulk action.');
        }

        $request->validate([
            'action' => 'required|in:delete',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:laporan_hasil_pengelolaans,id'
        ]);

        try {
            $selectedItems = LaporanHasilPengelolaan::whereIn('id', $request->selected_items)
                ->where('perusahaan_id', $user->perusahaan->id)
                ->get();

            if ($selectedItems->count() !== count($request->selected_items)) {
                return back()->with('error', 'Beberapa laporan tidak ditemukan atau bukan milik Anda.');
            }

            switch ($request->action) {
                case 'delete':
                    foreach ($selectedItems as $item) {
                        // Delete uploaded files
                        if ($item->dokumentasi) {
                            $files = $item->dokumentasi;
                            if (is_string($files)) {
                                $files = json_decode($files, true);
                            }
                            if (is_array($files)) {
                                foreach ($files as $file) {
                                    Storage::disk('public')->delete($file);
                                }
                            }
                        }
                        $item->delete();
                    }
                    
                    return redirect()->route('laporan-hasil-pengelolaan.index')
                        ->with('success', count($selectedItems) . ' laporan berhasil dihapus.');
                    
                default:
                    return back()->with('error', 'Aksi tidak valid.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan bulk action: ' . $e->getMessage());
        }
    }
   
    /**
     * Export laporan hasil to PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        // Base query
        $query = LaporanHasilPengelolaan::with([
            'pengelolaanLimbah.jenisLimbah',
            'pengelolaanLimbah.vendor',
            'pengelolaanLimbah.penyimpanan',
            'perusahaan'
        ]);

        // Filter berdasarkan role
        if ($user->isPerusahaan()) {
            $query->where('perusahaan_id', $user->perusahaan->id);
        }

        // Apply same filters as index
        if ($request->filled('perusahaan_id') && $user->isAdmin()) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        if ($request->filled('jenis_limbah_id')) {
            $query->whereHas('pengelolaanLimbah', function ($q) use ($request) {
                $q->where('jenis_limbah_id', $request->jenis_limbah_id);
            });
        }

        if ($request->filled('vendor_id')) {
            $query->whereHas('pengelolaanLimbah', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            });
        }

        if ($request->filled('status_hasil')) {
            $query->where('status_hasil', $request->status_hasil);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_selesai', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        $laporanHasil = $query->latest('tanggal_selesai')->get();

        // Prepare data for PDF
        $data = [
            'laporanHasil' => $laporanHasil,
            'user' => $user,
            'filters' => [
                'tanggal_dari' => $request->tanggal_dari,
                'tanggal_sampai' => $request->tanggal_sampai,
                'status_hasil' => $request->status_hasil,
                'perusahaan_id' => $request->perusahaan_id,
            ],
            'generated_at' => now(),
            'total_laporan' => $laporanHasil->count(),
            'total_berhasil' => $laporanHasil->where('status_hasil', 'berhasil')->count(),
            'total_partial' => $laporanHasil->where('status_hasil', 'partial')->count(),
            'total_gagal' => $laporanHasil->where('status_hasil', 'gagal')->count(),
            'total_limbah_dikelola' => $laporanHasil->sum('jumlah_berhasil_dikelola'),
            'total_residu' => $laporanHasil->sum('jumlah_residu'),
        ];

        // Generate filename
        $filename = 'laporan-hasil-pengelolaan-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        // Generate PDF
        $pdf = Pdf::loadView('laporan-hasil-pengelolaan.pdf.export', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        
        // Set options
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'sans-serif',
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
        ]);

        return $pdf->download($filename);
    }

    /**
     * Export single laporan to PDF
     */
    public function exportSinglePdf(LaporanHasilPengelolaan $laporanHasilPengelolaan)
    {
        $user = Auth::user();

        // Check access permission
        if ($user->isPerusahaan() && $laporanHasilPengelolaan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $laporanHasilPengelolaan->load([
            'pengelolaanLimbah.jenisLimbah',
            'pengelolaanLimbah.penyimpanan',
            'pengelolaanLimbah.vendor',
            'perusahaan'
        ]);

        // Calculate efficiency
        $totalJumlah = $laporanHasilPengelolaan->jumlah_berhasil_dikelola + ($laporanHasilPengelolaan->jumlah_residu ?? 0);
        $efisiensi = $totalJumlah > 0 ? ($laporanHasilPengelolaan->jumlah_berhasil_dikelola / $totalJumlah) * 100 : 0;

        $data = [
            'laporan' => $laporanHasilPengelolaan,
            'efisiensi' => $efisiensi,
            'generated_at' => now(),
            'user' => $user,
        ];

        $filename = 'laporan-hasil-' . $laporanHasilPengelolaan->id . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('laporan-hasil-pengelolaan.pdf.single', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Generate summary report PDF
     */
    public function exportSummaryPdf(Request $request)
    {
        $user = Auth::user();

        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Base query
        $query = LaporanHasilPengelolaan::with([
            'pengelolaanLimbah.jenisLimbah',
            'pengelolaanLimbah.vendor',
            'perusahaan'
        ])->whereBetween('tanggal_selesai', [$startDate, $endDate]);

        // Filter berdasarkan role
        if ($user->isPerusahaan()) {
            $query->where('perusahaan_id', $user->perusahaan->id);
        }

        $laporanHasil = $query->get();

        // Generate statistics
        $statistics = [
            'total_laporan' => $laporanHasil->count(),
            'berhasil' => $laporanHasil->where('status_hasil', 'berhasil')->count(),
            'partial' => $laporanHasil->where('status_hasil', 'partial')->count(),
            'gagal' => $laporanHasil->where('status_hasil', 'gagal')->count(),
            'total_limbah_dikelola' => $laporanHasil->sum('jumlah_berhasil_dikelola'),
            'total_residu' => $laporanHasil->sum('jumlah_residu'),
            'rata_rata_efisiensi' => $laporanHasil->avg('efisiensi_pengelolaan') ?? 0,
        ];

        // Group by jenis limbah
        $byJenisLimbah = $laporanHasil->groupBy('pengelolaanLimbah.jenisLimbah.nama')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_dikelola' => $group->sum('jumlah_berhasil_dikelola'),
                    'total_residu' => $group->sum('jumlah_residu'),
                ];
            });

        // Group by vendor
        $byVendor = $laporanHasil->groupBy('pengelolaanLimbah.vendor.nama_perusahaan')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_dikelola' => $group->sum('jumlah_berhasil_dikelola'),
                ];
            });

        // Monthly trend
        $monthlyTrend = $laporanHasil->groupBy(function ($item) {
            return $item->tanggal_selesai->format('Y-m');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_dikelola' => $group->sum('jumlah_berhasil_dikelola'),
            ];
        });

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $statistics,
            'by_jenis_limbah' => $byJenisLimbah,
            'by_vendor' => $byVendor,
            'monthly_trend' => $monthlyTrend,
            'generated_at' => now(),
            'user' => $user,
        ];

        $filename = 'ringkasan-laporan-hasil-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('laporan-hasil-pengelolaan.pdf.summary', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

}
