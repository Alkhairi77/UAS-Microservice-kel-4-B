<x-layout>
    <x-slot:title>Pencarian: {{ $search }} - EcoCycle</x-slot:title>

    <!-- Search Header -->
    <section class="bg-gradient-to-r from-[#042A20] to-[#18B17C] py-16">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Hasil Pencarian</h1>
                @if($search)
                    <p class="text-xl mb-8">
                        Menampilkan hasil untuk: <strong>"{{ $search }}"</strong>
                    </p>
                @endif
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('frontend.artikel.search') }}" method="GET" class="flex">
                        <input type="text" name="q" value="{{ $search }}" 
                               placeholder="Cari artikel..." 
                               class="flex-1 px-6 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                        <button type="submit" class="bg-[#00A67C] hover:bg-[#008F6B] px-8 py-3 rounded-r-lg transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="frontend.welcome" class="hover:text-[#18B17C]">Beranda</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('frontend.artikel.index') }}" class="hover:text-[#18B17C]">Artikel</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900">Pencarian</span>
            </nav>
        </div>
    </section>

    <!-- Search Results -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            @if($search && strlen($search) >= 3)
                @if($artikels->count() > 0)
                    <!-- Results Info -->
                    <div class="mb-8">
                        <p class="text-gray-600">
                            Ditemukan <strong>{{ $artikels->total() }}</strong> artikel untuk pencarian 
                            <strong>"{{ $search }}"</strong>
                        </p>
                    </div>

                    <!-- Articles Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                        @foreach($artikels as $artikel)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            @if($artikel->gambar_utama)
                                <img src="{{ Storage::url($artikel->gambar_utama) }}" 
                                     alt="{{ $artikel->judul }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white" 
                                          style="background-color: {{ $artikel->kategoriArtikel->warna }};">
                                        @if($artikel->kategoriArtikel->icon)
                                            <i class="{{ $artikel->kategoriArtikel->icon }} mr-1"></i>
                                        @endif
                                        {{ $artikel->kategoriArtikel->nama_kategori }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $artikel->reading_time }} min baca</span>
                                </div>
                                
                                <h3 class="text-lg font-bold mb-3 line-clamp-2">
                                    <a href="{{ route('frontend.artikel.show', $artikel->slug) }}" 
                                       class="text-gray-900 hover:text-[#18B17C] transition-colors">
                                        {!! str_ireplace($search, '<mark class="bg-yellow-200">' . $search . '</mark>', $artikel->judul) !!}
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {!! str_ireplace($search, '<mark class="bg-yellow-200">' . $search . '</mark>', $artikel->excerpt) !!}
                                </p>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $artikel->user->name }}
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $artikel->tanggal_publikasi->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ number_format($artikel->views_count) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $artikels->appends(['q' => $search])->links() }}
                    </div>
                @else
                    <!-- No Results -->
                    <div class="text-center py-16">
                        <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-700 mb-4">Tidak Ada Hasil Ditemukan</h3>
                        <p class="text-gray-500 mb-8">
                            Maaf, tidak ada artikel yang cocok dengan pencarian <strong>"{{ $search }}"</strong>
                        </p>
                        
                        <!-- Search Suggestions -->
                        <div class="max-w-md mx-auto">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Saran Pencarian:</h4>
                            <ul class="text-left text-gray-600 space-y-2">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#18B17C] mr-2"></i>
                                    Periksa ejaan kata kunci
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#18B17C] mr-2"></i>
                                    Gunakan kata kunci yang lebih umum
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#18B17C] mr-2"></i>
                                    Coba kata kunci yang berbeda
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#18B17C] mr-2"></i>
                                    Kurangi jumlah kata kunci
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Popular Keywords -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Kata Kunci Populer:</h4>
                            <div class="flex flex-wrap justify-center gap-2">
                                <a href="{{ route('frontend.artikel.search', ['q' => 'limbah']) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-[#18B17C] hover:text-white transition-colors">
                                    limbah
                                </a>
                                <a href="{{ route('frontend.artikel.search', ['q' => 'daur ulang']) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-[#18B17C] hover:text-white transition-colors">
                                    daur ulang
                                </a>
                                <a href="{{ route('frontend.artikel.search', ['q' => 'lingkungan']) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-[#18B17C] hover:text-white transition-colors">
                                    lingkungan
                                </a>
                                <a href="{{ route('frontend.artikel.search', ['q' => 'pengelolaan']) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-[#18B17C] hover:text-white transition-colors">
                                    pengelolaan
                                </a>
                                <a href="{{ route('frontend.artikel.search', ['q' => 'keberlanjutan']) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-[#18B17C] hover:text-white transition-colors">
                                    keberlanjutan
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif($search && strlen($search) < 3)
                <!-- Search Too Short -->
                <div class="text-center py-16">
                    <i class="fas fa-exclamation-triangle text-6xl text-yellow-400 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-4">Kata Kunci Terlalu Pendek</h3>
                    <p class="text-gray-500 mb-8">
                        Silakan masukkan minimal 3 karakter untuk melakukan pencarian.
                    </p>
                </div>
            @else
                <!-- Empty Search -->
                <div class="text-center py-16">
                    <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-4">Mulai Pencarian</h3>
                    <p class="text-gray-500 mb-8">
                        Masukkan kata kunci di atas untuk mencari artikel yang Anda inginkan.
                    </p>
                    
                    <!-- Browse by Category -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Atau jelajahi berdasarkan kategori:</h4>
                        <div class="flex flex-wrap justify-center gap-4">
                            @php
                                $sampleKategoris = \App\Models\KategoriArtikel::aktif()
                                    ->withCount('publishedArtikels')
                                    ->having('published_artikels_count', '>', 0)
                                    ->byUrutan()
                                    ->take(6)
                                    ->get();
                            @endphp
                            
                            @foreach($sampleKategoris as $kategori)
                            <a href="{{ route('frontend.artikel.kategori', $kategori->slug) }}" 
                               class="inline-flex items-center px-4 py-2 rounded-full text-white transition-colors hover:opacity-90"
                               style="background-color: {{ $kategori->warna }};">
                                @if($kategori->icon)
                                    <i class="{{ $kategori->icon }} mr-2"></i>
                                @endif
                                {{ $kategori->nama_kategori }}
                                <span class="ml-2 text-xs opacity-75">({{ $kategori->published_artikels_count }})</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Back to Articles -->
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <a href="{{ route('frontend.artikel.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-[#18B17C] text-white rounded-lg font-semibold hover:bg-[#16A085] transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Lihat Semua Artikel
            </a>
        </div>
    </section>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        mark {
            background-color: #fef08a;
            padding: 0 2px;
            border-radius: 2px;
        }
    </style>
</x-layout>