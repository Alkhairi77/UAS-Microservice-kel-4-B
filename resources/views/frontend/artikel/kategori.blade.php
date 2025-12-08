<x-layout>
    <x-slot:title>{{ $kategori->nama_kategori }} - Artikel EcoCycle</x-slot:title>

    <!-- Hero Section -->
    <section class="py-16" style="background: linear-gradient(135deg, {{ $kategori->warna }}22 0%, {{ $kategori->warna }}44 100%);">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-white mb-4" 
                     style="background-color: {{ $kategori->warna }};">
                    @if($kategori->icon)
                        <i class="{{ $kategori->icon }} mr-2 text-xl"></i>
                    @endif
                    <span class="font-semibold">{{ $kategori->nama_kategori }}</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Artikel {{ $kategori->nama_kategori }}
                </h1>
                
                @if($kategori->deskripsi)
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">{{ $kategori->deskripsi }}</p>
                @endif
                
                <div class="text-gray-600">
                    <i class="fas fa-newspaper mr-2"></i>
                    {{ $artikels->total() }} artikel tersedia
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
                <span class="text-gray-900">{{ $kategori->nama_kategori }}</span>
            </nav>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4">
                <!-- Main Content -->
                <div class="w-full lg:w-3/4 px-4">
                    @if($artikels->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
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
                                    
                                    <h3 class="text-xl font-bold mb-3 line-clamp-2">
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
                        <div class="flex justify-center">
                            {{ $artikels->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Artikel</h3>
                            <p class="text-gray-500">Artikel untuk kategori ini belum tersedia.</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-1/4 px-4 mt-8 lg:mt-0">
                    <!-- Category Info -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl" 
                                 style="background-color: {{ $kategori->warna }};">
                                @if($kategori->icon)
                                    <i class="{{ $kategori->icon }}"></i>
                                @else
                                    <i class="fas fa-tag"></i>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $kategori->nama_kategori }}</h3>
                            @if($kategori->deskripsi)
                                <p class="text-gray-600 text-sm">{{ $kategori->deskripsi }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Other Categories -->
                    @if($kategoris->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori Lainnya</h3>
                        <div class="space-y-3">
                            @foreach($kategoris as $otherKategori)
                            <a href="{{ route('frontend.artikel.kategori', $otherKategori->slug) }}" 
                               class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm mr-3" 
                                     style="background-color: {{ $otherKategori->warna }};">
                                    @if($otherKategori->icon)
                                        <i class="{{ $otherKategori->icon }}"></i>
                                    @else
                                        <i class="fas fa-tag"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 group-hover:text-[#18B17C] transition-colors">
                                        {{ $otherKategori->nama_kategori }}
                                    </h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $otherKategori->published_artikels_count }} artikel
                                    </p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quick Links -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Menu Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('frontend.artikel.index') }}" 
                               class="flex items-center p-2 text-gray-700 hover:text-[#18B17C] hover:bg-gray-50 rounded transition-colors">
                                <i class="fas fa-newspaper mr-3"></i>
                                Semua Artikel
                            </a>
                            <a href="#" 
                               class="flex items-center p-2 text-gray-700 hover:text-[#18B17C] hover:bg-gray-50 rounded transition-colors">
                                <i class="fas fa-home mr-3"></i>
                                Beranda
                            </a>
                            <a href="#about" 
                               class="flex items-center p-2 text-gray-700 hover:text-[#18B17C] hover:bg-gray-50 rounded transition-colors">
                                <i class="fas fa-info-circle mr-3"></i>
                                Tentang Kami
                            </a>
                            <a href="#layanan" 
                               class="flex items-center p-2 text-gray-700 hover:text-[#18B17C] hover:bg-gray-50 rounded transition-colors">
                                <i class="fas fa-cogs mr-3"></i>
                                Layanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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