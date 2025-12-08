<x-app>
    <x-slot:title>
        Laporan Harian
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Laporan Harian
                @if(auth()->user()->isAdmin())
                    <span class="text-sm font-normal text-gray-500">(Semua Perusahaan)</span>
                @endif
            </h2>
            <!-- Update bagian button export -->
            <div class="flex items-center space-x-2">
                @if(auth()->user()->isPerusahaan())
                    <x-button href="{{ route('laporan-harian.create') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Laporan
                    </x-button>
                @endif

                <!-- Dropdown Export -->
                <div class="relative inline-block text-left">
                    <button type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        id="export-menu-button" aria-expanded="true" aria-haspopup="true">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                        id="export-menu" role="menu" aria-orientation="vertical" aria-labelledby="export-menu-button"
                        tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="{{ route('laporan-harian.export', request()->query()) }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">
                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Export PDF
                            </a>
                            <a href="{{ route('laporan-harian.export-csv', request()->query()) }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">
                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript untuk dropdown -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const button = document.getElementById('export-menu-button');
                    const menu = document.getElementById('export-menu');

                    button.addEventListener('click', function () {
                        menu.classList.toggle('hidden');
                    });

                    // Close menu when clicking outside
                    document.addEventListener('click', function (event) {
                        if (!button.contains(event.target) && !menu.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });
                });
            </script>

        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <!-- Filter dan Search -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('laporan-harian.index') }}"
                class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <x-label>Pencarian</x-label>
                    <x-input name="search" value="{{ request('search') }}"
                        placeholder="Cari jenis limbah, penyimpanan..." />
                </div>

                @if(auth()->user()->isAdmin())
                    <div>
                        <x-label>Perusahaan</x-label>
                        <x-select name="perusahaan_id" :options="$perusahaanOptions" value="{{ request('perusahaan_id') }}"
                            placeholder="Semua Perusahaan" />
                    </div>
                @endif

                <div>
                    <x-label>Jenis Limbah</x-label>
                    <x-select name="jenis_limbah_id" :options="$jenisLimbahOptions"
                        value="{{ request('jenis_limbah_id') }}" placeholder="Semua Jenis" />
                </div>
                <div>
                    <x-label>Penyimpanan</x-label>
                    <x-select name="penyimpanan_id" :options="$penyimpananOptions"
                        value="{{ request('penyimpanan_id') }}" placeholder="Semua Penyimpanan" />
                </div>
                <div>
                    <x-label>Status</x-label>
                    <x-select name="status" :options="$statusOptions" value="{{ request('status') }}"
                        placeholder="Semua Status" />
                </div>
                <div>
                    <x-label>Tanggal Dari</x-label>
                    <x-input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" />
                </div>
                <div>
                    <x-label>Tanggal Sampai</x-label>
                    <x-input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" />
                </div>
                <div class="flex items-end space-x-2">
                    <x-button type="submit" variant="secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </x-button>

                    @if(request()->hasAny(['search', 'perusahaan_id', 'jenis_limbah_id', 'penyimpanan_id', 'status', 'tanggal_dari', 'tanggal_sampai']))
                        <x-button variant="outline" href="{{ route('laporan-harian.index') }}">
                            Reset
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Bulk Actions (hanya untuk perusahaan) -->
        @if(auth()->user()->isPerusahaan() && $laporan->count() > 0)
            <x-card class="mb-6">
                <form method="POST" action="{{ route('laporan-harian.bulk-action') }}">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="select-all"
                                class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <label for="select-all" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pilih
                                Semua</label>
                        </div>
                        <x-select name="action" placeholder="Pilih Aksi">
                            <option value="submit">Submit Laporan</option>
                            <option value="delete">Hapus Laporan</option>
                        </x-select>
                        <x-button type="submit" variant="secondary">
                            Jalankan
                        </x-button>
                    </div>
                </form>
            </x-card>
        @endif
        <!-- Tabel Laporan -->
        <x-card>
            @if($laporan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                @if(auth()->user()->isPerusahaan())
                                    <th class="px-4 py-3">
                                        <input type="checkbox" id="select-all-header"
                                            class="rounded border-gray-300 text-green-600">
                                    </th>
                                @endif
                                <th class="px-4 py-3">Tanggal</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="px-4 py-3">Perusahaan</th>
                                @endif
                                <th class="px-4 py-3">Jenis Limbah</th>
                                <th class="px-4 py-3">Penyimpanan</th>
                                <th class="px-4 py-3">Jumlah</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($laporan as $item)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    @if(auth()->user()->isPerusahaan())
                                        <td class="px-4 py-3">
                                            @if($item->status === 'draft')
                                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                                    class="item-checkbox rounded border-gray-300 text-green-600">
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $item->tanggal->format('d/m/Y') }}</div>
                                            <div class="text-gray-500">{{ $item->tanggal->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-3">
                                            <div class="text-sm">
                                                <div class="font-semibold">{{ $item->perusahaan->nama_perusahaan }}</div>
                                                <div class="text-gray-500">{{ $item->perusahaan->email }}</div>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $item->jenisLimbah->nama }}</div>
                                            <div class="text-gray-500">{{ $item->jenisLimbah->kode_limbah }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $item->penyimpanan->nama_penyimpanan }}</div>
                                            <div class="text-gray-500">{{ $item->penyimpanan->jenis_penyimpanan_name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold">
                                            {{ number_format($item->jumlah, 2) }} {{ $item->satuan }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $item->status_badge_class }}-700 bg-{{ $item->status_badge_class }}-100 rounded-full">
                                            {{ $item->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <!-- Detail Button - Semua role bisa lihat -->
                                            <a href="{{ route('laporan-harian.show', $item) }}"
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

                                            <!-- Edit Button - Hanya perusahaan pemilik dan status draft -->
                                            @if(auth()->user()->isPerusahaan() && $item->perusahaan_id === auth()->user()->perusahaan->id && $item->status === 'draft')
                                                <a href="{{ route('laporan-harian.edit', $item) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif

                                            <!-- Submit Button - Hanya perusahaan pemilik dan status draft -->
                                            @if(auth()->user()->isPerusahaan() && $item->perusahaan_id === auth()->user()->perusahaan->id && $item->status === 'draft')
                                                <form action="{{ route('laporan-harian.submit', $item) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin submit laporan ini? Laporan yang sudah disubmit tidak dapat diedit.')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                                        title="Submit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Delete Button - Hanya perusahaan pemilik dan status draft -->
                                            @if(auth()->user()->isPerusahaan() && $item->perusahaan_id === auth()->user()->perusahaan->id && $item->status === 'draft')
                                                <form action="{{ route('laporan-harian.destroy', $item) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
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
                                            @endif

                                            <!-- Admin Badge - Hanya untuk admin -->
                                            @if(auth()->user()->isAdmin())
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    View Only
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
                    {{ $laporan->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        @if(auth()->user()->isAdmin())
                            Belum Ada Laporan Harian
                        @else
                            Belum Ada Laporan Harian Anda
                        @endif
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        @if(auth()->user()->isAdmin())
                            Belum ada laporan harian yang dibuat oleh perusahaan.
                        @else
                            Anda belum membuat laporan harian. Mulai buat laporan pertama Anda.
                        @endif
                    </p>
                    @if(auth()->user()->isPerusahaan())
                        <x-button href="{{ route('laporan-harian.create') }}">
                            Buat Laporan Pertama
                        </x-button>
                    @endif
                </div>
            @endif
        </x-card>

        <!-- Info Box untuk Admin -->
        @if(auth()->user()->isAdmin())
            <x-card class="mt-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            Informasi untuk Admin
                        </h3>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Anda dapat melihat semua laporan harian dari seluruh perusahaan</li>
                                <li>Gunakan filter perusahaan untuk melihat laporan spesifik</li>
                                <li>Laporan bersifat readonly - hanya perusahaan yang dapat mengedit</li>
                                <li>Klik "Detail" untuk melihat informasi lengkap laporan</li>
                                <li>Export CSV tersedia untuk analisis data</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif

        <!-- Statistik untuk Admin -->
        @if(auth()->user()->isAdmin())
            <x-card class="mt-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">
                    Statistik Laporan Harian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-blue-600">Total Laporan</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                    {{ \App\Models\LaporanHarian::count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-green-600">Submitted</div>
                                <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                                    {{ \App\Models\LaporanHarian::where('status', 'submitted')->count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-yellow-600">Draft</div>
                                <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ \App\Models\LaporanHarian::where('status', 'draft')->count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-purple-600">Perusahaan Aktif</div>
                                <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                    {{ \App\Models\LaporanHarian::distinct('perusahaan_id')->count('perusahaan_id') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif
    </div>

    <!-- JavaScript untuk bulk actions (hanya untuk perusahaan) -->
    @if(auth()->user()->isPerusahaan())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAllCheckbox = document.getElementById('select-all');
                const selectAllHeaderCheckbox = document.getElementById('select-all-header');
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');

                // Handle select all functionality
                function handleSelectAll(checkbox) {
                    itemCheckboxes.forEach(item => {
                        item.checked = checkbox.checked;
                    });

                    // Sync both select all checkboxes
                    if (selectAllCheckbox && selectAllHeaderCheckbox) {
                        selectAllCheckbox.checked = checkbox.checked;
                        selectAllHeaderCheckbox.checked = checkbox.checked;
                    }
                }

                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function () {
                        handleSelectAll(this);
                    });
                }

                if (selectAllHeaderCheckbox) {
                    selectAllHeaderCheckbox.addEventListener('change', function () {
                        handleSelectAll(this);
                    });
                }
                // Handle individual checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                        const allChecked = checkedItems.length === itemCheckboxes.length;

                        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                        if (selectAllHeaderCheckbox) selectAllHeaderCheckbox.checked = allChecked;
                    });
                });
            });
        </script>
    @endif
</x-app>