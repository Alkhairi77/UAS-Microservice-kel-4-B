<x-app>
    <x-slot:title>
        Detail Kategori Artikel
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Kategori Artikel
            </h2>
            <div class="flex space-x-2">
                @can('update', $kategoriArtikel)
                    <x-button href="{{ route('kategori-artikel.edit', $kategoriArtikel) }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </x-button>
                @endcan
                <x-button variant="secondary" href="{{ route('kategori-artikel.index') }}">
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

        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Informasi Kategori -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Informasi Kategori
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-center">
                        @if($kategoriArtikel->warna)
                            <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $kategoriArtikel->warna }};"></div>
                        @endif
                        @if($kategoriArtikel->icon)
                            <i class="{{ $kategoriArtikel->icon }} mr-3 text-gray-600"></i>
                        @endif
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $kategoriArtikel->nama_kategori }}
                            </h4>
                            <p class="text-sm text-gray-500">{{ $kategoriArtikel->slug }}</p>
                        </div>
                    </div>

                    @if($kategoriArtikel->deskripsi)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $kategoriArtikel->deskripsi }}
                            </dd>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $kategoriArtikel->status_badge_class }}-700 bg-{{ $kategoriArtikel->status_badge_class }}-100 rounded-full">
                                    {{ $kategoriArtikel->status_name }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Urutan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $kategoriArtikel->urutan ?? 0 }}
                            </dd>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $kategoriArtikel->created_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $kategoriArtikel->updated_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Statistik -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Statistik
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $kategoriArtikel->artikels_count ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Total Artikel
                        </div>
                    </div>

                    <!-- Preview Kategori -->
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Preview Kategori</h4>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-white text-sm font-medium" style="background-color: {{ $kategoriArtikel->warna ?? '#3B82F6' }};">
                            @if($kategoriArtikel->icon)
                                <i class="{{ $kategoriArtikel->icon }} mr-2"></i>
                            @else
                                <i class="fas fa-tag mr-2"></i>
                            @endif
                            {{ $kategoriArtikel->nama_kategori }}
                        </div>
                    </div>

                    <!-- Aksi -->
                    <div class="mt-6 space-y-2">
                        @can('update', $kategoriArtikel)
                            <x-button href="{{ route('kategori-artikel.edit', $kategoriArtikel) }}" class="w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Kategori
                            </x-button>
                        @endcan

                        @can('delete', $kategoriArtikel)
                            @if(($kategoriArtikel->artikels_count ?? 0) == 0)
                                <form action="{{ route('kategori-artikel.destroy', $kategoriArtikel) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"></path>
                                        </svg>
                                        Hapus Kategori
                                    </button>
                                </form>
                            @else
                                <div class="p-3 bg-yellow-50 dark:bg-yellow-900 rounded-md">
                                    <p class="text-xs text-yellow-700 dark:text-yellow-300">
                                        Kategori tidak dapat dihapus karena masih memiliki {{ $kategoriArtikel->artikels_count }} artikel.
                                    </p>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Daftar Artikel dalam Kategori -->
        @if($kategoriArtikel->artikels_count > 0)
            <x-card class="mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Artikel dalam Kategori Ini
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Judul Artikel</th>
                                <th class="px-4 py-3">Penulis</th>
                                                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Tanggal Dibuat</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @forelse($kategoriArtikel->artikels as $artikel)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <p class="font-semibold">{{ $artikel->judul ?? 'Judul Artikel' }}</p>
                                            <p class="text-xs text-gray-500">{{ $artikel->slug ?? 'slug-artikel' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $artikel->user->name ?? 'Admin' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                            {{ ucfirst($artikel->status ?? 'published') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $artikel->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            @if(Route::has('artikel.show'))
                                                <a href="{{ route('admin.artikel.show', $artikel) }}"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Lihat Artikel">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                            
                                            @if(Route::has('artikel.edit'))
                                                @can('update', $artikel)
                                                    <a href="{{ route('admin.artikel.edit', $artikel) }}"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                        title="Edit Artikel">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada artikel dalam kategori ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($kategoriArtikel->artikels_count > 5)
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500">
                            Menampilkan 5 artikel terbaru dari {{ $kategoriArtikel->artikels_count }} total artikel.
                            @if(Route::has('artikel.index'))
                                <a href="{{ route('admin.artikel.index', ['kategori' => $kategoriArtikel->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    Lihat semua artikel
                                </a>
                            @endif
                        </p>
                    </div>
                @endif
            </x-card>
        @endif
    </div>
</x-app>
