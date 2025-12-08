<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\JenisLimbah;
use App\Models\Penyimpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Helpers\NotificationHelper;


class PenyimpananController extends Controller
{
    /**
     * Display a listing of penyimpanan
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        // Pastikan user memiliki perusahaan
        if (!$user->hasValidPerusahaan()) {
            return redirect()->route('perusahaan.create')
                ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        $query = Penyimpanan::with(['perusahaan', 'jenisLimbah']) // TAMBAH jenisLimbah
            ->where('perusahaan_id', $user->perusahaan->id);

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_penyimpanan', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('jenisLimbah', function($subQ) use ($request) {
                      $subQ->where('nama', 'like', '%' . $request->search . '%')
                           ->orWhere('kode_limbah', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // TAMBAH: Filter by jenis limbah
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        // Filter by jenis penyimpanan
        if ($request->filled('jenis_penyimpanan')) {
            $query->where('jenis_penyimpanan', $request->jenis_penyimpanan);
        }

        // Filter by kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by status kapasitas
        if ($request->filled('status_kapasitas')) {
            $statusKapasitas = $request->status_kapasitas;
            $query->whereRaw('
                CASE 
                    WHEN (kapasitas_terpakai / kapasitas_maksimal * 100) >= 90 THEN "penuh"
                    WHEN (kapasitas_terpakai / kapasitas_maksimal * 100) >= 75 THEN "peringatan"
                    ELSE "aman"
                END = ?
            ', [$statusKapasitas]);
        }

        $penyimpanan = $query->latest()->paginate(10)->withQueryString();

        // Data untuk filter
        $jenisOptions = Penyimpanan::getJenisPenyimpananOptions();
        $kondisiOptions = Penyimpanan::getKondisiOptions();
        $statusOptions = Penyimpanan::getStatusOptions();

        return view('penyimpanan.index', compact(
            'penyimpanan', 
            'jenisOptions', 
            'kondisiOptions', 
            'statusOptions'
        ));
    }

    /**
     * Show the form for creating a new penyimpanan
     */
    public function create(): View
    {
        $perusahaan = auth()->user()->perusahaan;
        
        if (!$perusahaan) {
            return redirect()->route('perusahaan.create')
                ->with('error', 'Silakan lengkapi profil perusahaan terlebih dahulu.');
        }

        // DEBUG: Tambahkan logging untuk debug
        \Log::info('Creating penyimpanan form');
        
        // Ambil jenis limbah untuk dropdown
        $jenisLimbahs = JenisLimbah::where('status', 'active')
            ->orderBy('nama')
            ->get();
        
        // DEBUG: Cek apakah data ada
        \Log::info('Jenis Limbah count: ' . $jenisLimbahs->count());
        \Log::info('Jenis Limbah data: ', $jenisLimbahs->toArray());

        $jenisOptions = Penyimpanan::getJenisPenyimpananOptions();
        $kondisiOptions = Penyimpanan::getKondisiOptions();
        $statusOptions = Penyimpanan::getStatusOptions();

        return view('penyimpanan.create', compact(
            'jenisLimbahs',
            'jenisOptions', 
            'kondisiOptions', 
            'statusOptions'
        ));
    }

    /**
     * Store a newly created penyimpanan
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jenis_limbah_id' => 'required|exists:jenis_limbahs,id',
            'nama_penyimpanan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'jenis_penyimpanan' => 'required|string',
            'kapasitas_maksimal' => 'required|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,maintenance',
            'tanggal_pembuatan' => 'required|date',
            'status' => 'required|in:aktif,tidak_aktif,maintenance',
            'catatan' => 'nullable|string'
        ]);

        try {
            $penyimpanan = new Penyimpanan($request->all());
            $penyimpanan->perusahaan_id = auth()->user()->perusahaan->id;
            $penyimpanan->save();

          

            return redirect()->route('penyimpanan.index')
                ->with('success', 'Penyimpanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified penyimpanan
     */
    public function show(Penyimpanan $penyimpanan): View
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa melihat penyimpanan milik perusahaannya
        if ($penyimpanan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        // TAMBAH: Load relasi jenisLimbah
        $penyimpanan->load(['perusahaan', 'jenisLimbah', 'laporanHarian' => function($query) {
            $query->latest()->take(5);
        }]);
        
        return view('penyimpanan.show', compact('penyimpanan'));
    }

    /**
     * Show the form for editing the specified penyimpanan
     */
    public function edit(Penyimpanan $penyimpanan): View
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa edit penyimpanan milik perusahaannya
        if ($penyimpanan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        // TAMBAH: Load relasi jenisLimbah
        $penyimpanan->load('jenisLimbah');

        return view('penyimpanan.edit', compact('penyimpanan'));
    }

    /**
     * Update the specified penyimpanan
     */
    public function update(Request $request, Penyimpanan $penyimpanan): RedirectResponse
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa update penyimpanan milik perusahaannya
        if ($penyimpanan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama_penyimpanan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'jenis_penyimpanan' => 'required|string',
            'kapasitas_maksimal' => 'required|numeric|min:0',
            // HAPUS: kapasitas_terpakai dari validasi manual (diupdate otomatis)
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,maintenance',
            'tanggal_pembuatan' => 'required|date',
            'status' => 'required|in:aktif,tidak_aktif,maintenance',
            'catatan' => 'nullable|string'
        ]);

        // TAMBAH: Validasi kapasitas maksimal tidak boleh kurang dari terpakai
        if ($request->kapasitas_maksimal < $penyimpanan->kapasitas_terpakai) {
            return back()->withErrors([
                'kapasitas_maksimal' => 'Kapasitas maksimal tidak boleh kurang dari kapasitas terpakai (' . 
                                       number_format($penyimpanan->kapasitas_terpakai, 2) . ' ' . 
                                       $penyimpanan->satuan . ').'
            ])->withInput();
        }

        $penyimpanan->update($request->only([
            'nama_penyimpanan',
            'lokasi',
            'jenis_penyimpanan',
            'kapasitas_maksimal',
            // HAPUS: kapasitas_terpakai (tidak diupdate manual)
            // HAPUS: satuan (ambil dari jenis limbah)
            'kondisi',
            'tanggal_pembuatan',
            'catatan',
            'status'
        ]));

        return redirect()->route('penyimpanan.index')
            ->with('success', 'Data penyimpanan berhasil diperbarui.');
    }

    /**
     * Remove the specified penyimpanan
     */
    public function destroy(Penyimpanan $penyimpanan): RedirectResponse
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa hapus penyimpanan milik perusahaannya
        if ($penyimpanan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        try {
            // TAMBAH: Cek apakah masih ada laporan harian
            if ($penyimpanan->laporanHarian()->count() > 0) {
                return redirect()->route('penyimpanan.index')
                    ->with('error', 'Tidak dapat menghapus penyimpanan yang masih memiliki laporan harian.');
            }

            $penyimpanan->delete();
            return redirect()->route('penyimpanan.index')
                ->with('success', 'Data penyimpanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('penyimpanan.index')
                ->with('error', 'Gagal menghapus data penyimpanan: ' . $e->getMessage());
        }
    }

    /**
     * Update kapasitas terpakai
     * UBAH: Method ini sekarang hanya untuk manual adjustment jika diperlukan
     */
    public function updateKapasitas(Request $request, Penyimpanan $penyimpanan): RedirectResponse
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa update penyimpanan milik perusahaannya
        if ($penyimpanan->perusahaan_id !== $user->perusahaan->id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'kapasitas_terpakai' => 'required|numeric|min:0|max:' . $penyimpanan->kapasitas_maksimal
        ], [
            'kapasitas_terpakai.required' => 'Kapasitas terpakai wajib diisi.',
            'kapasitas_terpakai.numeric' => 'Kapasitas terpakai harus berupa angka.',
            'kapasitas_terpakai.min' => 'Kapasitas terpakai tidak boleh kurang dari 0.',
            'kapasitas_terpakai.max' => 'Kapasitas terpakai tidak boleh melebihi kapasitas maksimal (' . 
                                       number_format($penyimpanan->kapasitas_maksimal, 2) . ' ' . 
                                       $penyimpanan->satuan . ').'
        ]);

        $penyimpanan->update([
            'kapasitas_terpakai' => $request->kapasitas_terpakai
        ]);

        return redirect()->back()
            ->with('success', 'Kapasitas terpakai berhasil diperbarui.');
    }

    /**
     * TAMBAH: Method untuk mendapatkan penyimpanan berdasarkan jenis limbah
     */
    public function getPenyimpananByJenisLimbah(Request $request)
    {
        $jenisLimbahId = $request->get('jenis_limbah_id');
        $perusahaanId = auth()->user()->perusahaan->id;

        $penyimpanan = Penyimpanan::where('perusahaan_id', $perusahaanId)
            ->where('jenis_limbah_id', $jenisLimbahId)
            ->where('status', 'aktif')
            ->with('jenisLimbah')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_penyimpanan,
                    'lokasi' => $item->lokasi,
                    'kapasitas_tersisa' => $item->kapasitas_maksimal - $item->kapasitas_terpakai,
                    'satuan' => $item->satuan,
                    'can_accommodate' => ($item->kapasitas_maksimal - $item->kapasitas_terpakai) > 0
                ];
            });

        return response()->json($penyimpanan);
    }
}