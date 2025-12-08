<x-layout>
    <x-slot:title>{{ $artikel->meta_title ?? $artikel->judul }} - EcoCycle</x-slot:title>

    <!-- Breadcrumb -->
    <section class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="frontend.welcome" class="hover:text-[#18B17C]">Beranda</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('frontend.artikel.index') }}" class="hover:text-[#18B17C]">Artikel</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('frontend.artikel.kategori', $artikel->kategoriArtikel->slug) }}" 
                   class="hover:text-[#18B17C]">{{ $artikel->kategoriArtikel->nama_kategori }}</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900">{{ Str::limit($artikel->judul, 50) }}</span>
            </nav>
        </div>
    </section>

    <!-- Article Content -->
    <article class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Article Header -->
                <header class="mb-8">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" 
                              style="background-color: {{ $artikel->kategoriArtikel->warna }};">
                            @if($artikel->kategoriArtikel->icon)
                                <i class="{{ $artikel->kategoriArtikel->icon }} mr-2"></i>
                            @endif
                            {{ $artikel->kategoriArtikel->nama_kategori }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $artikel->judul }}</h1>
                    
                    <div class="flex flex-wrap items-center justify-between text-gray-600 mb-6">
                        <div class="flex items-center space-x-6 mb-4 md:mb-0">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <span>{{ $artikel->user->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>{{ $artikel->tanggal_publikasi->format('d F Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                <span>{{ $artikel->reading_time }} menit baca</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                <span>{{ number_format($artikel->views_count) }} views</span>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($artikel->gambar_utama)
                        <div class="mb-8">
                            <img src="{{ Storage::url($artikel->gambar_utama) }}" 
                                 alt="{{ $artikel->judul }}" 
                                 class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                        </div>
                    @endif

                    <!-- Article Excerpt -->
                    @if($artikel->excerpt)
                        <div class="bg-gray-50 border-l-4 border-[#18B17C] p-6 mb-8 rounded-r-lg">
                            <p class="text-lg text-gray-700 italic leading-relaxed">{{ $artikel->excerpt }}</p>
                        </div>
                    @endif
                </header>

                <!-- Article Content -->
                <div class="prose prose-lg max-w-none mb-12">
                    <div class="text-gray-800 leading-relaxed">
                        {!! nl2br(e($artikel->konten)) !!}
                    </div>
                </div>

                <!-- Tags -->
                @if($artikel->tags_array && count($artikel->tags_array) > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Tags:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($artikel->tags_array as $tag)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-[#18B17C] hover:text-white transition-colors cursor-pointer">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share Buttons -->
                <div class="border-t border-b border-gray-200 py-6 mb-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Bagikan Artikel:</h3>
                        <div class="flex items-center space-x-4">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                               target="_blank" 
                               class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f mr-2"></i>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($artikel->judul) }}" 
                               target="_blank" 
                               class="flex items-center px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                                <i class="fab fa-twitter mr-2"></i>
                                Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($artikel->judul . ' - ' . request()->fullUrl()) }}" 
                               target="_blank" 
                               class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fab fa-whatsapp mr-2"></i>
                                WhatsApp
                            </a>
                            <button onclick="copyToClipboard()" 
                                    class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-link mr-2"></i>
                                Copy Link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Author Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-12">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($artikel->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $artikel->user->name }}</h4>
                            <p class="text-gray-600 mb-3">Penulis artikel tentang pengelolaan limbah dan keberlanjutan lingkungan.</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-2"></i>
                                Bergabung sejak {{ $artikel->user->created_at->format('F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- Related Articles -->
    @if($relatedArtikels->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-12">Artikel Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedArtikels as $related)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($related->gambar_utama)
                            <img src="{{ Storage::url($related->gambar_utama) }}" 
                                 alt="{{ $related->judul }}" 
                                 class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-bold mb-2 line-clamp-2">
                                <a href="{{ route('frontend.artikel.show', $related->slug) }}" 
                                   class="text-gray-900 hover:text-[#18B17C] transition-colors">
                                    {{ $related->judul }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $related->excerpt }}</p>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $related->tanggal_publikasi->format('d M Y') }}</span>
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ number_format($related->views_count) }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Popular Articles Sidebar -->
    @if($popularArtikels->count() > 0)
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-12">Artikel Populer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    @foreach($popularArtikels as $popular)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($popular->gambar_utama)
                            <img src="{{ Storage::url($popular->gambar_utama) }}" 
                                 alt="{{ $popular->judul }}" 
                                 class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-3">
                            <h3 class="font-bold text-sm mb-2 line-clamp-2">
                                <a href="{{ route('frontend.artikel.show', $popular->slug) }}" 
                                   class="text-gray-900 hover:text-[#18B17C] transition-colors">
                                    {{ $popular->judul }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $popular->tanggal_publikasi->format('d M') }}</span>
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ number_format($popular->views_count) }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Back to Articles -->
    <section class="py-8 bg-[#18B17C]">
        <div class="container mx-auto px-4 text-center">
            <a href="{{ route('frontend.artikel.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-white text-[#18B17C] rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Artikel
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
        
        .prose {
            color: #374151;
            line-height: 1.75;
        }
        
        .prose p {
            margin-bottom: 1.25em;
        }
        
        .prose h2 {
            font-size: 1.5em;
            font-weight: 700;
            margin-top: 2em;
            margin-bottom: 1em;
            color: #111827;
        }
        
        .prose h3 {
            font-size: 1.25em;
            font-weight: 600;
            margin-top: 1.6em;
            margin-bottom: 0.6em;
            color: #111827;
        }
        
        .prose ul, .prose ol {
            margin: 1.25em 0;
            padding-left: 1.625em;
        }
        
        .prose li {
            margin: 0.5em 0;
        }
        
        .prose blockquote {
            font-style: italic;
            border-left: 4px solid #18B17C;
            padding-left: 1em;
            margin: 1.6em 0;
            color: #6B7280;
        }
    </style>

    <script>
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                // Show success message
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-gray-600', 'hover:bg-gray-700');
                }, 2000);
            });
        }
    </script>
</x-layout>