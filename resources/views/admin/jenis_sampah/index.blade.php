<x-app>
    <x-slot:title>
        @if(auth()->user()->isAdmin())
            Manajemen Jenis Sampah
        @else
            Daftar Jenis Sampah
        @endif
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        {{-- Header --}}
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                @if(auth()->user()->isAdmin())
                    Manajemen Jenis Sampah
                @else
                    Daftar Jenis Sampah
                @endif
            </h2>

            {{-- Tombol Tambah Jenis Sampah hanya untuk admin --}}
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.jenis-sampah.create') }}"
                   class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                   Tambah Jenis Sampah
                </a>
            @endif
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter --}}
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="GET" action="{{ route('admin.jenis-sampah.index') }}" class="grid gap-4 md:grid-cols-3">
                {{-- Search --}}
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Cari Nama</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                               placeholder="Nama sampah..." />
                    </label>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Kategori</span>
                        <select name="kategori"
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-gray">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\JenisSampah::getKategoriOptions() as $key => $label)
                                <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                {{-- Tombol Filter & Reset --}}
                <div class="flex items-end space-x-2">
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Filter
                    </button>
                    <a href="{{ route('admin.jenis-sampah.index') }}"
                       class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg active:bg-gray-100 hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray">
                       Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        @if(auth()->user()->isAdmin())
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    @if($data->count())
                        <table class="w-full whitespace-no-wrap">
                            <thead class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Harga / Kg</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($data as $item)
                                    @php
                                        $kategoriLabel = \App\Models\JenisSampah::KATEGORI[$item->kategori] ?? ucfirst($item->kategori);
                                        $kategoriClass = match($item->kategori) {
                                            'organik' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100',
                                            'recyclable' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
                                            'b3' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100',
                                            'anorganik' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
                                            'plastik' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-100',
                                            'kertas' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100',
                                            'kaca' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100',
                                            'elektronik' => 'bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-100',
                                            'logam' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-100',
                                            'textile' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-100',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100',
                                        };
                                    @endphp
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $kategoriClass }}">
                                                {{ $kategoriLabel }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">Rp {{ number_format($item->harga_per_kg, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-4 text-sm">
                                                {{-- Edit --}}
                                                <a href="{{ route('admin.jenis-sampah.edit', $item->id) }}"
                                                   class="flex items-center justify-center px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-green"
                                                   title="Edit">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                    </svg>
                                                </a>
                                                {{-- Hapus --}}
                                                <form action="{{ route('admin.jenis-sampah.destroy', $item->id) }}" method="POST"
                                                      class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="flex items-center justify-center px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-red"
                                                            title="Hapus">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                  d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                  clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-4 py-8 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <p class="text-lg font-medium">Belum ada jenis sampah</p>
                                <p class="text-sm mt-2">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.jenis-sampah.create') }}" class="text-green-600 hover:text-green-800">
                                            Tambah jenis sampah pertama
                                        </a>
                                    @else
                                        Hubungi admin untuk menambahkan data
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                @if($data->hasPages())
                    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                        <span class="flex items-center col-span-3">
                            Showing {{ $data->firstItem() ?? 0 }}-{{ $data->lastItem() ?? 0 }} of {{ $data->total() }}
                        </span>
                        <span class="col-span-2"></span>
                        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                            {{ $data->links() }}
                        </span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app>
