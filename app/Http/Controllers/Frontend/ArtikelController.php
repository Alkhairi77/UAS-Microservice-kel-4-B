<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    /**
     * Display a listing of articles (Frontend)
     */
    public function index(Request $request): View
    {
        $query = Artikel::with(['kategoriArtikel', 'user'])
                        ->published()
                        ->latest('tanggal_publikasi');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $kategori = KategoriArtikel::where('slug', $request->kategori)->first();
            if ($kategori) {
                $query->byKategori($kategori->id);
            }
        }

        $artikels = $query->paginate(12)->withQueryString();
        
        // Get categories for filter
        $kategoris = KategoriArtikel::aktif()
                                   ->withCount('publishedArtikels')
                                   ->having('published_artikels_count', '>', 0)
                                   ->byUrutan()
                                   ->get();

        // Get featured articles (latest 3)
        $featuredArtikels = Artikel::with(['kategoriArtikel', 'user'])
                                  ->published()
                                  ->latest('tanggal_publikasi')
                                  ->take(3)
                                  ->get();

        return view('frontend.artikel.index', compact('artikels', 'kategoris', 'featuredArtikels'));
    }

    /**
     * Display the specified article
     */
    public function show(string $slug): View
    {
        $artikel = Artikel::with(['kategoriArtikel', 'user'])
                         ->where('slug', $slug)
                         ->published()
                         ->firstOrFail();

        // Increment views
        $artikel->incrementViews();

        // Get related articles
        $relatedArtikels = Artikel::with(['kategoriArtikel', 'user'])
                                 ->published()
                                 ->where('kategori_artikel_id', $artikel->kategori_artikel_id)
                                 ->where('id', '!=', $artikel->id)
                                 ->latest('tanggal_publikasi')
                                 ->take(4)
                                 ->get();

        // Get popular articles
        $popularArtikels = Artikel::with(['kategoriArtikel', 'user'])
                                 ->published()
                                 ->where('id', '!=', $artikel->id)
                                 ->orderBy('views_count', 'desc')
                                 ->take(5)
                                 ->get();

        return view('frontend.artikel.show', compact('artikel', 'relatedArtikels', 'popularArtikels'));
    }

    /**
     * Display articles by category
     */
    public function byKategori(string $slug): View
    {
        $kategori = KategoriArtikel::where('slug', $slug)
                                  ->aktif()
                                  ->firstOrFail();

        $artikels = Artikel::with(['kategoriArtikel', 'user'])
                          ->published()
                          ->byKategori($kategori->id)
                          ->latest('tanggal_publikasi')
                          ->paginate(12);

        // Get other categories
        $kategoris = KategoriArtikel::aktif()
                                   ->withCount('publishedArtikels')
                                   ->having('published_artikels_count', '>', 0)
                                   ->where('id', '!=', $kategori->id)
                                   ->byUrutan()
                                   ->get();

        return view('frontend.artikel.kategori', compact('kategori', 'artikels', 'kategoris'));
    }

    /**
     * Search articles
     */
    public function search(Request $request): View
    {
        $search = $request->get('q', '');
        
        $artikels = collect();
        if (strlen($search) >= 3) {
            $artikels = Artikel::with(['kategoriArtikel', 'user'])
                              ->published()
                              ->search($search)
                              ->latest('tanggal_publikasi')
                              ->paginate(12)
                              ->withQueryString();
        }

        return view('frontend.artikel.search', compact('artikels', 'search'));
    }
}