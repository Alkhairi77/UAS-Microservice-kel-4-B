<x-app>
    <x-slot:title>
        @if(auth()->user()->isAdmin())
            Manajemen Jenis Limbah
        @else
            Daftar Jenis Limbah
        @endif
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                @if(auth()->user()->isAdmin())
                    Manajemen Jenis Limbah
                @else
                    Daftar Jenis Limbah
                @endif
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('jenis-limbah.create') }}" class="btn-green">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Jenis Limbah
                </a>
            @endif
        </div>

        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <!-- Filter -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('jenis-limbah.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Cari Nama/Kode
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama atau kode limbah..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                </div>
                <div class="min-w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Kategori
                    </label>
                    <select name="kategori"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Models\JenisLimbah::KATEGORI as $key => $value)
                            <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->isAdmin())
                    <div class="min-w-32">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status
                        </label>
                        <select name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                @else
                    <div class="min-w-40">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tingkat Bahaya
                        </label>
                        <select name="tingkat_bahaya"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Semua Tingkat</option>
                            @foreach(\App\Models\JenisLimbah::TINGKAT_BAHAYA as $key => $value)
                                <option value="{{ $key }}" {{ request('tingkat_bahaya') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex space-x-2">
                    <x-button type="submit" variant="green">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </x-button>
                    <a href="{{ route('jenis-limbah.index') }}" class="btn-gray">
                        Reset
                    </a>
                </div>
            </form>
        </x-card>

        @if(auth()->user()->isAdmin())
            <!-- Admin View - Table Layout -->
            <x-card class="p-0">
                <div class="overflow-x-auto">
                    @if ($jenisLimbah->count())
                        <table class="w-full whitespace-no-wrap">
                            <thead
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3">Kode</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Satuan</th>
                                    <th class="px-4 py-3">Tingkat Bahaya</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($jenisLimbah as $jenis)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="font-mono text-sm font-semibold">{{ $jenis->kode_limbah }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $jenis->nama }}</div>
                                            @if($jenis->deskripsi)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ Str::limit($jenis->deskripsi, 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                            @if($jenis->kategori == 'hazardous') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                                            @elseif($jenis->kategori == 'recyclable') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                                            @elseif($jenis->kategori == 'organic') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                                                            @endif">
                                                {{ $jenis->kategori_name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $jenis->satuan_name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($jenis->tingkat_bahaya)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                    @if($jenis->tingkat_bahaya == 'sangat_tinggi') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                                                    @elseif($jenis->tingkat_bahaya == 'tinggi') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-100
                                                                    @elseif($jenis->tingkat_bahaya == 'sedang') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                                                        @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                                                    @endif">
                                                    {{ $jenis->tingkat_bahaya_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $jenis->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' }}">
                                                {{ $jenis->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2 text-sm">
                                                <a href="{{ route('jenis-limbah.show', $jenis) }}"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('jenis-limbah.edit', $jenis) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('jenis-limbah.destroy', $jenis) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis limbah ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
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
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada jenis limbah</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan jenis limbah
                                pertama.</p>
                            <div class="mt-6">
                                <a href="{{ route('jenis-limbah.create') }}" class="btn-green">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Jenis Limbah
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                @if($jenisLimbah->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $jenisLimbah->links() }}
                    </div>
                @endif
            </x-card>
        @else
            <!-- Perusahaan View - Card Grid Layout -->
            @if ($jenisLimbah->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($jenisLimbah as $jenis)
                        <x-card class="hover:shadow-lg transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="font-mono text-sm font-semibold text-gray-600 dark:text-gray-400">
                                    {{ $jenis->kode_limbah }}
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($jenis->kategori == 'hazardous') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                                @elseif($jenis->kategori == 'recyclable') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                                @elseif($jenis->kategori == 'organic') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                                                @endif">
                                    {{ $jenis->kategori_name }}
                                </span>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ $jenis->nama }}
                            </h3>

                            @if($jenis->deskripsi)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    {{ Str::limit($jenis->deskripsi, 100) }}
                                </p>
                            @endif

                            <div class="flex justify-between items-center text-sm mb-3">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Satuan:</span>
                                    <span class="font-medium">{{ $jenis->satuan_name }}</span>
                                </div>
                                @if($jenis->tingkat_bahaya)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                        @if($jenis->tingkat_bahaya == 'sangat_tinggi') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                                        @elseif($jenis->tingkat_bahaya == 'tinggi') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-100
                                                        @elseif($jenis->tingkat_bahaya == 'sedang') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                                        @endif">
                                        {{ $jenis->tingkat_bahaya_name }}
                                    </span>
                                @endif
                            </div>

                            @if($jenis->metode_pengelolaan_rekomendasi && count($jenis->metode_pengelolaan_rekomendasi) > 0)
                                <div class="mb-3">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Metode Pengelolaan:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($jenis->metode_pengelolaan_rekomendasi, 0, 2) as $metode)
                                            <span
                                                class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100 rounded-full">
                                                {{ \App\Models\JenisLimbah::METODE_PENGELOLAAN[$metode] ?? $metode }}
                                            </span>
                                        @endforeach
                                        @if(count($jenis->metode_pengelolaan_rekomendasi) > 2)
                                            <span class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">
                                                +{{ count($jenis->metode_pengelolaan_rekomendasi) - 2 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('jenis-limbah.show', $jenis) }}"
                                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                        </x-card>
                    @endforeach
                </div>

                @if($jenisLimbah->hasPages())
                    <div class="mt-6">
                        {{ $jenisLimbah->links() }}
                    </div>
                @endif
            @else
                <x-card class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada jenis limbah</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if(request()->hasAny(['search', 'kategori', 'tingkat_bahaya']))
                            Tidak ada jenis limbah yang sesuai dengan filter.
                        @else
                            Belum ada jenis limbah yang tersedia.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'kategori', 'tingkat_bahaya']))
                        <div class="mt-6">
                            <a href="{{ route('jenis-limbah.index') }}" class="btn-green">
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </x-card>
            @endif
        @endif
    </div>
</x-app>