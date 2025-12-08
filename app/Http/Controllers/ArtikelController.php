<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;

class ArtikelController extends Controller
{
    /**
     * Display a listing of articles (Backend)
     */
    public function index(Request $request): View
    {
        $query = Artikel::with(['kategoriArtikel', 'user']);
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        // Filter by category
        if ($request->filled('kategori_artikel_id')) {
            $query->byKategori($request->kategori_artikel_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by author (for non-admin users)
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $artikels = $query->latest()->paginate(15)->withQueryString();

        // Data for filters
        $kategoris = KategoriArtikel::aktif()->byUrutan()->pluck('nama_kategori', 'id');
        $statusOptions = Artikel::getStatusOptions();

        return view('artikel.index', compact('artikels', 'kategoris', 'statusOptions'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create(): View
    {
        $kategoris = KategoriArtikel::aktif()->byUrutan()->pluck('nama_kategori', 'id');
        return view('artikel.create', compact('kategoris'));
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug 1: Cek data yang masuk
        \Log::info('Data request:', $request->all());
        
        // Debug 2: Cek validasi
        try {
            $request->validate(
                Artikel::validationRules(),
                Artikel::validationMessages()
            );
            \Log::info('Validasi berhasil');
        } catch (\Exception $e) {
            \Log::error('Validasi gagal: ' . $e->getMessage());
            return back()->withErrors($e->getMessage())->withInput();
        }

        // Debug 3: Cek proses create
        try {
            $data = $request->only([
                'judul', 'excerpt', 'konten', 'kategori_artikel_id', 
                'status', 'tanggal_publikasi', 'meta_title', 'meta_description'
            ]);

            $data['user_id'] = Auth::id();

            if ($request->hasFile('gambar_utama')) {
                $data['gambar_utama'] = $request->file('gambar_utama')->store('artikel', 'public');
            }

            if ($request->input('action') === 'publish') {
                $data['status'] = 'published';
            } elseif ($request->input('action') === 'draft') {
                $data['status'] = 'draft';
            }

            if ($data['status'] === 'published' && !$request->filled('tanggal_publikasi')) {
                $data['tanggal_publikasi'] = now();
            }

            \Log::info('Data yang akan disimpan:', $data);
            
            $artikel = Artikel::create($data);
            
            \Log::info('Artikel berhasil dibuat dengan ID: ' . $artikel->id);

            return redirect()->route('admin.artikel.index')
                ->with('success', 'Artikel berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan artikel: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        
            return back()->withInput()
                ->with('error', 'Gagal menambahkan artikel: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified article
     */
    public function show(Artikel $artikel): View
    {
        $artikel->load(['kategoriArtikel', 'user']);
        return view('artikel.show', compact('artikel'));
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(Artikel $artikel): View
    {
        // Check if user can edit this article
        if (!Auth::user()->isAdmin() && $artikel->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $kategoris = KategoriArtikel::aktif()->byUrutan()->pluck('nama_kategori', 'id');
        return view('artikel.edit', compact('artikel', 'kategoris'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Artikel $artikel): RedirectResponse
    {
        // Check if user can edit this article
        if (!Auth::user()->isAdmin() && $artikel->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(
            Artikel::validationRules($artikel->id),
            Artikel::validationMessages()
        );

        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('gambar_utama')) {
                // Delete old image
                if ($artikel->gambar_utama) {
                    Storage::disk('public')->delete($artikel->gambar_utama);
                }
                $data['gambar_utama'] = $request->file('gambar_utama')->store('artikel', 'public');
            }

            // Handle tags
            if ($request->filled('tags')) {
                $data['tags'] = array_filter(array_map('trim', explode(',', $request->tags)));
            }

            // Set publication date if status changed to published
            if ($data['status'] === 'published' && $artikel->status !== 'published' && !$request->filled('tanggal_publikasi')) {
                $data['tanggal_publikasi'] = now();
            }

            $artikel->update($data);

            return redirect()->route('admin.artikel.show', $artikel)
                ->with('success', 'Artikel berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui artikel: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified article
     */
    public function destroy(Artikel $artikel): RedirectResponse
    {
        // Check if user can delete this article
        if (!Auth::user()->isAdmin() && $artikel->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete image if exists
            if ($artikel->gambar_utama) {
                Storage::disk('public')->delete($artikel->gambar_utama);
            }

            $artikel->delete();

            return redirect()->route('admin.artikel.index')
                ->with('success', 'Artikel berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.artikel.index')
                ->with('error', 'Gagal menghapus artikel: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,publish,draft,archive',
            'artikel_ids' => 'required|array',
            'artikel_ids.*' => 'exists:artikels,id'
        ]);

        try {
            $query = Artikel::whereIn('id', $request->artikel_ids);

            // For non-admin users, only allow actions on their own articles
            if (!Auth::user()->isAdmin()) {
                $query->where('user_id', Auth::id());
            }

            $artikels = $query->get();

            switch ($request->action) {
                case 'delete':
                    foreach ($artikels as $artikel) {
                        if ($artikel->gambar_utama) {
                            Storage::disk('public')->delete($artikel->gambar_utama);
                        }
                        $artikel->delete();
                    }
                    $message = 'Artikel terpilih berhasil dihapus.';
                    break;

                case 'publish':
                    $artikels->each(function ($artikel) {
                        $artikel->update([
                            'status' => 'published',
                            'tanggal_publikasi' => $artikel->tanggal_publikasi ?? now()
                        ]);
                    });
                    $message = 'Artikel terpilih berhasil dipublikasikan.';
                    break;

                case 'draft':
                    $artikels->each->update(['status' => 'draft']);
                    $message = 'Artikel terpilih berhasil dijadikan draft.';
                    break;

                case 'archive':
                    $artikels->each->update(['status' => 'archived']);
                    $message = 'Artikel terpilih berhasil diarsipkan.';
                    break;
            }

            return redirect()->route('admin.artikel.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }
    }
}
