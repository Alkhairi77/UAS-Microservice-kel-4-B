<x-layout>
    <x-slot:title>Artikel - EcoCycle</x-slot:title>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-[#042A20] to-[#18B17C] pt-24 pb-16">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Artikel & Berita</h1>
                <p class="text-xl mb-8">Temukan informasi terbaru seputar pengelolaan limbah dan keberlanjutan lingkungan</p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('frontend.artikel.search') }}" method="GET" class="flex">
                        <input type="text" name="q" value="{{ request('search') }}" 
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

    <!-- Featured Articles -->
    @if($featuredArtikels->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Artikel Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredArtikels as $artikel)
                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    @if($artikel->gambar_utama)
                        <img src="{{ Storage::url($artikel->gambar_utama) }}" 
                             alt="{{ $artikel->judul }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-[#18B17C] to-[#00A67C] flex items-center justify-center">
                            <i class="fas fa-leaf text-white text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white" 
                                  style="background-color: {{ $artikel->kategoriArtikel->warna }};">
                                @if($artikel->kategoriArtikel->icon)
                                    <i class="{{ $artikel->kategoriArtikel->icon }} mr-1"></i>
                                @endif
                                {{ $artikel->kategoriArtikel->nama_kategori }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3 line-clamp-2">
                            <a href="{{ route('frontend.artikel.show', $artikel->slug) }}" 
                               class="text-gray-900 hover:text-[#18B17C] transition-colors">
                                {{ $artikel->judul }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-1 line-clamp-3">{{ $artikel->excerpt }}</p>

                        <a href="{{ route('frontend.artikel.show', $artikel->slug) }}"
                            class="text-[#18B17C] font-semibold hover:text-[#15A06B] transition-colors">
                            Baca Selengkapnya →
                        </a>
                        
                        <div class="flex mt-3 items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                {{ $artikel->user->name }}
                            </div>
                            <div class="flex items-center space-x-4">
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
        </div>
    </section>
    @endif

    <!-- Categories Filter -->
    @if($kategoris->count() > 0)
    <section class="py-8 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('frontend.artikel.index') }}" 
                   class="px-6 py-2 rounded-full border-2 transition-colors {{ !request('kategori') ? 'bg-[#18B17C] text-white border-[#18B17C]' : 'border-gray-300 text-gray-700 hover:border-[#18B17C] hover:text-[#18B17C]' }}">
                    Semua Artikel
                </a>
                @foreach($kategoris as $kategori)
                <a href="{{ route('frontend.artikel.index', ['kategori' => $kategori->slug]) }}" 
                   class="px-6 py-2 rounded-full border-2 transition-colors {{ request('kategori') == $kategori->slug ? 'text-white border-transparent' : 'border-gray-300 text-gray-700 hover:border-opacity-50' }}"
                   style="{{ request('kategori') == $kategori->slug ? 'background-color: ' . $kategori->warna . '; border-color: ' . $kategori->warna : '' }}">
                    @if($kategori->icon)
                        <i class="{{ $kategori->icon }} mr-2"></i>
                    @endif
                    {{ $kategori->nama_kategori }}
                    <span class="ml-2 text-xs opacity-75">({{ $kategori->published_artikels_count }})</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Articles Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            @if($artikels->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                                    {{ $artikel->judul }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $artikel->excerpt }}</p>
                            
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
                <div class="mt-12 flex justify-center">
                    {{ $artikels->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Artikel</h3>
                    <p class="text-gray-500">Artikel akan segera hadir. Silakan kembali lagi nanti.</p>
                </div>
            @endif
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
    </style>
</x-layout>