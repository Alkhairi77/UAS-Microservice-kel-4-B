<?php

namespace App\Http\Controllers;

use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriArtikelController extends Controller
{
    /**
     * Display a listing of kategori artikel
     */
    public function index(Request $request): View
    {
        $query = KategoriArtikel::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kategoriArtikels = $query->withCount('artikels')
            ->byUrutan()
            ->paginate(10)
            ->withQueryString();

        return view('kategori-artikel.index', compact('kategoriArtikels'));
    }

    /**
     * Show the form for creating a new kategori artikel
     */
    public function create(): View
    {
        return view('kategori-artikel.create');
    }

    /**
     * Store a newly created kategori artikel
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            KategoriArtikel::validationRules(),
            KategoriArtikel::validationMessages()
        );

        try {
            KategoriArtikel::create($request->all());

            return redirect()->route('kategori-artikel.index')
                ->with('success', 'Kategori artikel berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan kategori artikel: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified kategori artikel
     */
    public function show(KategoriArtikel $kategoriArtikel): View
    {
        $kategoriArtikel->load(['artikels' => function ($query) {
            $query->latest()->take(5);
        }]);
        
        $kategoriArtikel->loadCount('artikels');

        return view('kategori-artikel.show', compact('kategoriArtikel'));
    }

    /**
     * Show the form for editing the specified kategori artikel
     */
    public function edit(KategoriArtikel $kategoriArtikel): View
    {
        $kategoriArtikel->loadCount('artikels');
        return view('kategori-artikel.edit', compact('kategoriArtikel'));
    }

    /**
     * Update the specified kategori artikel
     */
    public function update(Request $request, KategoriArtikel $kategoriArtikel): RedirectResponse
    {
        $request->validate(
            KategoriArtikel::validationRules($kategoriArtikel->id),
            KategoriArtikel::validationMessages()
        );

        try {
            $kategoriArtikel->update($request->all());

            return redirect()->route('kategori-artikel.index', $kategoriArtikel)
                ->with('success', 'Kategori artikel berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui kategori artikel: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified kategori artikel
     */
    public function destroy(KategoriArtikel $kategoriArtikel): RedirectResponse
    {
        try {
            // Check if kategori has articles
            if ($kategoriArtikel->artikels()->count() > 0) {
                return redirect()->route('kategori-artikel.index')
                    ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki artikel.');
            }

            $kategoriArtikel->delete();

            return redirect()->route('kategori-artikel.index')
                ->with('success', 'Kategori artikel berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kategori-artikel.index')
                ->with('error', 'Gagal menghapus kategori artikel: ' . $e->getMessage());
        }
    }

    /**
     * Update urutan kategori (AJAX)
     */
    public function updateUrutan(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'kategori_ids' => 'required|array',
            'kategori_ids.*' => 'exists:kategori_artikels,id'
        ]);

        try {
            foreach ($request->kategori_ids as $index => $id) {
                KategoriArtikel::where('id', $id)->update(['urutan' => $index + 1]);
            }

            return response()->json(['success' => true, 'message' => 'Urutan berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui urutan.'], 500);
        }
    }
}
