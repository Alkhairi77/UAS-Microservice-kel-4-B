<x-app>
    <x-slot:title>
        Artikel
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Artikel
            </h2>
            <x-button href="{{ route('admin.artikel.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Artikel
            </x-button>
        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif
        <!-- Filter dan Search -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.artikel.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-label>Cari Artikel</x-label>
                    <x-input type="text" name="search" value="{{ request('search') }}" 
                             placeholder="Judul artikel..." />
                </div>
                <div>
                    <x-label>Kategori</x-label>
                    <x-select name="kategori_artikel_id">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $id => $nama)
                            <option value="{{ $id }}" {{ request('kategori_artikel_id') == $id ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                
                <div>
                    <x-label>Status</x-label>
                    <x-select name="status">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                
                <div class="flex items-end space-x-2">
                    <x-button type="submit" variant="secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </x-button>
                    
                    @if(request()->hasAny(['search', 'kategori_artikel_id', 'status']))
                        <x-button variant="outline" href="{{ route('admin.artikel.index') }}">
                            Reset
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Artikel -->
        <x-card>
            @if($artikels->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Artikel</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Penulis</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Tanggal Publikasi</th>
                                <th class="px-4 py-3">Views</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($artikels as $artikel)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            @if($artikel->gambar_utama)
                                                <div class="relative hidden w-16 h-16 mr-3 rounded-lg md:block">
                                                    <img class="object-cover w-full h-full rounded-lg" 
                                                         src="{{ Storage::url($artikel->gambar_utama) }}" 
                                                         alt="{{ $artikel->judul }}" loading="lazy" />
                                                </div>
                                            @else
                                                <div class="relative hidden w-16 h-16 mr-3 rounded-lg md:block bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold">{{ Str::limit($artikel->judul, 50) }}</p>
                                                @if($artikel->excerpt)
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        {{ Str::limit($artikel->excerpt, 80) }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-500">
                                                    {{ $artikel->reading_time }} menit baca
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($artikel->kategoriArtikel)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white" 
                                                  style="background-color: {{ $artikel->kategoriArtikel->warna }};">
                                                @if($artikel->kategoriArtikel->icon)
                                                    <i class="{{ $artikel->kategoriArtikel->icon }} mr-1"></i>
                                                @endif
                                                {{ $artikel->kategoriArtikel->nama_kategori }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $artikel->user->name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $artikel->status_badge_class }}-700 bg-{{ $artikel->status_badge_class }}-100 rounded-full">
                                            {{ $artikel->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($artikel->tanggal_publikasi)
                                            {{ $artikel->tanggal_publikasi->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ number_format($artikel->views_count) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <a href="{{ route('admin.artikel.show', $artikel) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            
                                            @if(Auth::user()->isAdmin() || $artikel->user_id === Auth::id())
                                                <a href="{{ route('admin.artikel.edit', $artikel) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                
                                                <form action="{{ route('admin.artikel.destroy', $artikel) }}" method="POST" 
                                                      class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                            title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $artikels->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Tidak Ada Artikel</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Belum ada artikel yang dibuat. Mulai dengan membuat artikel pertama Anda.
                    </p>
                    <x-button href="{{ route('admin.artikel.create') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Artikel Pertama
                    </x-button>
                </div>
            @endif
        </x-card>
    </div>
</x-app>