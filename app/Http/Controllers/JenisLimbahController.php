<?php

namespace App\Http\Controllers;

use App\Models\JenisLimbah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisLimbahController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisLimbah::query();

        // Jika bukan admin, hanya tampilkan yang aktif
        if (!auth()->user()->isAdmin()) {
            $query->active();
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_limbah', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan status (hanya untuk admin)
        if ($request->filled('status') && auth()->user()->isAdmin()) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tingkat bahaya
        if ($request->filled('tingkat_bahaya')) {
            $query->where('tingkat_bahaya', $request->tingkat_bahaya);
        }

        $jenisLimbah = $query->latest()->paginate(auth()->user()->isAdmin() ? 10 : 12)->withQueryString();
        
        return view('jenis-limbah.index', compact('jenisLimbah'));
    }

    public function create()
    {
        // Hanya admin yang bisa create
        abort_unless(auth()->user()->isAdmin(), 403);
        
        return view('jenis-limbah.create');
    }

    public function store(Request $request)
    {
        // Hanya admin yang bisa store
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(JenisLimbah::KATEGORI)),
            'kode_limbah' => 'required|string|max:20|unique:jenis_limbahs,kode_limbah',
            'satuan_default' => 'required|in:' . implode(',', array_keys(JenisLimbah::SATUAN)),
            'tingkat_bahaya' => 'nullable|in:' . implode(',', array_keys(JenisLimbah::TINGKAT_BAHAYA)),
            'metode_pengelolaan_rekomendasi' => 'nullable|array',
            'metode_pengelolaan_rekomendasi.*' => 'in:' . implode(',', array_keys(JenisLimbah::METODE_PENGELOLAAN)),
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        JenisLimbah::create($validated);

        return redirect()->route('jenis-limbah.index')
            ->with('success', 'Jenis limbah berhasil ditambahkan.');
    }

    public function show(JenisLimbah $jenisLimbah)
    {
        // Jika bukan admin, pastikan jenis limbah aktif
        if (!auth()->user()->isAdmin() && $jenisLimbah->status !== 'active') {
            abort(404);
        }

        // Load relationships dengan count
        $jenisLimbah->loadCount([
            'laporanHarian',
            'pengelolaanLimbahs',
            'penyimpanans'
        ]);

        // Load recent activities
        $recentLaporan = $jenisLimbah->laporanHarian()
            ->with(['perusahaan', 'penyimpanan'])
            ->latest()
            ->take(5)
            ->get();

        $recentPengelolaan = $jenisLimbah->pengelolaanLimbahs()
            ->with(['perusahaan', 'vendor'])
            ->latest()
            ->take(5)
            ->get();

        $activePenyimpanan = $jenisLimbah->penyimpanans()
            ->with('perusahaan')
            ->where('status', 'aktif')
            ->get();

        // Statistics
        $totalVolume = $jenisLimbah->laporanHarian()
            ->where('status', 'submitted')
            ->sum('jumlah');

        $totalPengelolaanVolume = $jenisLimbah->pengelolaanLimbahs()
            ->sum('jumlah_dikelola');

        $avgEfisiensi = $jenisLimbah->pengelolaanLimbahs()
            ->whereHas('laporanHasilPengelolaan')
            ->with('laporanHasilPengelolaan')
            ->get()
            ->pluck('laporanHasilPengelolaan.efisiensi_pengelolaan')
            ->flatten()
            ->avg();

        return view('jenis-limbah.show', compact(
            'jenisLimbah',
            'recentLaporan',
            'recentPengelolaan',
            'activePenyimpanan',
            'totalVolume',
            'totalPengelolaanVolume',
            'avgEfisiensi'
        ));
    }

    public function edit(JenisLimbah $jenisLimbah)
    {
        // Hanya admin yang bisa edit
        abort_unless(auth()->user()->isAdmin(), 403);
        
        return view('jenis-limbah.edit', compact('jenisLimbah'));
    }

    public function update(Request $request, JenisLimbah $jenisLimbah)
    {
        // Hanya admin yang bisa update
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(JenisLimbah::KATEGORI)),
            'kode_limbah' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_limbahs', 'kode_limbah')->ignore($jenisLimbah->id)
            ],
            'satuan_default' => 'required|in:' . implode(',', array_keys(JenisLimbah::SATUAN)),
            'tingkat_bahaya' => 'nullable|in:' . implode(',', array_keys(JenisLimbah::TINGKAT_BAHAYA)),
            'metode_pengelolaan_rekomendasi' => 'nullable|array',
            'metode_pengelolaan_rekomendasi.*' => 'in:' . implode(',', array_keys(JenisLimbah::METODE_PENGELOLAAN)),
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $jenisLimbah->update($validated);

        return redirect()->route('jenis-limbah.index')
            ->with('success', 'Jenis limbah berhasil diperbarui.');
    }

    public function destroy(JenisLimbah $jenisLimbah)
    {
        // Hanya admin yang bisa delete
        abort_unless(auth()->user()->isAdmin(), 403);

        // Cek apakah jenis limbah sedang digunakan
        if ($jenisLimbah->laporanHarian()->exists() || $jenisLimbah->penyimpanans()->exists()) {
            return redirect()->route('jenis-limbah.index')
                ->with('error', 'Jenis limbah tidak dapat dihapus karena sedang digunakan.');
        }

        $jenisLimbah->delete();

        return redirect()->route('jenis-limbah.index')
            ->with('success', 'Jenis limbah berhasil dihapus.');
    }

    // API untuk AJAX requests
    public function apiIndex(Request $request)
    {
        $query = JenisLimbah::active();

        if ($request->has('kategori')) {
            $query->byKategori($request->kategori);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_limbah', 'like', '%' . $request->search . '%');
            });
        }

        $jenisLimbah = $query->select('id', 'nama', 'kode_limbah', 'kategori', 'satuan_default', 'tingkat_bahaya')
                            ->get()
                            ->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'nama' => $item->nama,
                                    'kode_limbah' => $item->kode_limbah,
                                    'kategori' => $item->kategori,
                                    'kategori_name' => $item->kategori_name,
                                    'satuan_default' => $item->satuan_default,
                                    'satuan_name' => $item->satuan_name,
                                    'tingkat_bahaya' => $item->tingkat_bahaya,
                                    'tingkat_bahaya_name' => $item->tingkat_bahaya_name,
                                ];
                            });

        return response()->json($jenisLimbah);
    }

    public function apiShow(JenisLimbah $jenisLimbah)
    {
        if ($jenisLimbah->status !== 'active') {
            return response()->json(['error' => 'Jenis limbah tidak aktif'], 404);
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
            'metode_pengelolaan_rekomendasi' => $jenisLimbah->metode_pengelolaan_rekomendasi,
            'deskripsi' => $jenisLimbah->deskripsi,
        ]);
    }
}