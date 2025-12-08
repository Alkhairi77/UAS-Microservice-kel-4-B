<x-app>
    <x-slot:title>
        Detail Artikel
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Artikel
            </h2>
            <div class="flex space-x-2">
                @if(Auth::user()->isAdmin() || $artikel->user_id === Auth::id())
                    <x-button href="{{ route('admin.artikel.edit', $artikel) }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </x-button>
                @endif
                <x-button variant="secondary" href="{{ route('admin.artikel.index') }}">
                    Kembali
                </x-button>
            </div>
        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <div class="grid gap-6 mb-8 md:grid-cols-3">
            <!-- Main Content -->
            <div class="md:col-span-2">
                <x-card>
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" 
                                  style="background-color: {{ $artikel->kategoriArtikel->warna ?? '#3B82F6' }};">
                                @if($artikel->kategoriArtikel && $artikel->kategoriArtikel->icon)
                                    <i class="{{ $artikel->kategoriArtikel->icon }} mr-2"></i>
                                @endif
                                {{ $artikel->kategoriArtikel->nama_kategori ?? 'Tanpa Kategori' }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $artikel->status_badge_class }}-700 bg-{{ $artikel->status_badge_class }}-100 rounded-full">
                                {{ $artikel->status_name }}
                            </span>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                            {{ $artikel->judul }}
                        </h1>
                        
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 space-x-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $artikel->user->name }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $artikel->created_at->format('d M Y H:i') }}
                            </div>
                            @if($artikel->tanggal_publikasi)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Publikasi: {{ $artikel->tanggal_publikasi->format('d M Y H:i') }}
                                </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ number_format($artikel->views_count) }} views
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $artikel->reading_time }} menit baca
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($artikel->gambar_utama)
                        <div class="mb-6">
                            <img src="{{ Storage::url($artikel->gambar_utama) }}" 
                                 alt="{{ $artikel->judul }}" 
                                 class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Excerpt -->
                    @if($artikel->excerpt)
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-blue-500">
                            <p class="text-gray-700 dark:text-gray-300 italic">{{ $artikel->excerpt }}</p>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none dark:prose-invert">
                        {!! nl2br(e($artikel->konten)) !!}
                    </div>
                    <!-- SEO Info (for admin) -->
                    @if(Auth::user()->isAdmin() && ($artikel->meta_title || $artikel->meta_description))
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">SEO Information</h3>
                            @if($artikel->meta_title)
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $artikel->meta_title }}</p>
                                </div>
                            @endif
                            @if($artikel->meta_description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $artikel->meta_description }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </x-card>
            </div>
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Article Info -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Artikel</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <code class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">{{ $artikel->slug }}</code>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $artikel->status_badge_class }}-700 bg-{{ $artikel->status_badge_class }}-100 rounded-full">
                                    {{ $artikel->status_name }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $artikel->kategoriArtikel->nama_kategori ?? 'Tanpa Kategori' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Penulis</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $artikel->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $artikel->created_at->format('d M Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diupdate</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $artikel->updated_at->format('d M Y H:i') }}
                            </dd>
                        </div>
                        @if($artikel->tanggal_publikasi)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Publikasi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $artikel->tanggal_publikasi->format('d M Y H:i') }}
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Views</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format($artikel->views_count) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Baca</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $artikel->reading_time }} menit
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <!-- Actions -->
                @if(Auth::user()->isAdmin() || $artikel->user_id === Auth::id())
                    <x-card>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Aksi</h3>
                        <div class="space-y-3">
                            <x-button href="{{ route('admin.artikel.edit', $artikel) }}" class="w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Artikel
                            </x-button>
                            
                            @if($artikel->status === 'published')
                                <a href="{{ route('frontend.artikel.show', $artikel->slug) }}" 
                                   target="_blank"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7h3m0 0V4m0 3l-3-3"></path>
                                    </svg>
                                    Lihat di Frontend
                                </a>
                            @endif

                            <form action="{{ route('admin.artikel.destroy', $artikel) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Artikel
                                </button>
                            </form>
                        </div>
                    </x-card>
                @endif
            </div>
        </div>
    </div>
</x-app>