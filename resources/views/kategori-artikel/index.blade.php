<x-app>
    <x-slot:title>
        Kategori Artikel
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Kategori Artikel
            </h2>
           
                <x-button href="{{ route('kategori-artikel.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kategori
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
            <form method="GET" action="{{ route('kategori-artikel.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-label>Cari Kategori</x-label>
                    <x-input type="text" name="search" value="{{ request('search') }}" 
                             placeholder="Nama kategori..." />
                </div>
                
                <div>
                    <x-label>Status</x-label>
                    <x-select name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </x-select>
                </div>
                
                <div class="flex items-end space-x-2">
                    <x-button type="submit" variant="secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </x-button>
                    
                    @if(request()->hasAny(['search', 'status']))
                        <x-button variant="outline" href="{{ route('kategori-artikel.index') }}">
                            Reset
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Kategori -->
        <x-card>
            @if(isset($kategoriArtikels) && $kategoriArtikels->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Slug</th>
                                <th class="px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Urutan</th>
                                <th class="px-4 py-3">Jumlah Artikel</th>
                                <th class="px-4 py-3">Dibuat</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($kategoriArtikels as $item)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            @if($item->warna)
                                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $item->warna }};"></div>
                                            @endif
                                            <div>
                                                <p class="font-semibold">{{ $item->nama_kategori }}</p>
                                                @if($item->icon)
                                                    <p class="text-xs text-gray-500">
                                                        <i class="{{ $item->icon }}"></i> {{ $item->icon }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <code class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded">
                                            {{ $item->slug }}
                                        </code>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="max-w-xs truncate">
                                            {{ $item->deskripsi ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $item->status_badge_class }}-700 bg-{{ $item->status_badge_class }}-100 rounded-full">
                                            {{ $item->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        {{ $item->urutan ?? 0 }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            {{ $item->artikels_count ?? 0 }} artikel
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <a href="{{ route('kategori-artikel.show', $item) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            
                                            
                                                <a href="{{ route('kategori-artikel.edit', $item) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                           
                                            
                                           
                                                @if(($item->artikels_count ?? 0) == 0)
                                                    <form action="{{ route('kategori-artikel.destroy', $item) }}" method="POST" 
                                                          class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
                                                @else
                                                    <span class="text-gray-400" title="Tidak dapat dihapus karena masih memiliki artikel">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                        </svg>
                                                    </span>
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
                    {{ $kategoriArtikels->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Belum ada kategori artikel</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Mulai dengan menambahkan kategori artikel pertama Anda.</p>
                    @can('create', App\Models\KategoriArtikel::class)
                        <x-button href="{{ route('kategori-artikel.create') }}">
                            Tambah Kategori Pertama
                        </x-button>
                    @endcan
                </div>
            @endif
        </x-card>
    </div>
</x-app>
