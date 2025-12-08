<x-app>
    <x-slot:title>
        Laporan Hasil Pengelolaan
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Laporan Hasil Pengelolaan
                @if(auth()->user()->isAdmin())
                    <span class="text-sm font-normal text-gray-500">(Semua Perusahaan)</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->isPerusahaan())
                    <x-button href="{{ route('laporan-hasil-pengelolaan.create') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Laporan
                    </x-button>
                @endif
                <!-- Export Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                        <div class="py-1">
                            <a href="{{ route('laporan-hasil-pengelolaan.export') }}" 
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </a>
                            
                            <a href="{{ route('laporan-hasil-pengelolaan.export.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Export PDF
                            </a>
                            
                            @if(auth()->user()->isPerusahaan())
                            <button onclick="showSummaryModal()" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Ringkasan PDF
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
   
                <!-- Modal untuk Summary PDF -->
                @if(auth()->user()->isPerusahaan())
                <div id="summaryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Export Ringkasan PDF</h3>
                            <form action="{{ route('laporan-hasil-pengelolaan.summary.pdf') }}" method="GET">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                    <input type="date" name="start_date" required 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                                    <input type="date" name="end_date" required 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="hideSummaryModal()" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Export PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <!-- Filter dan Search -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('laporan-hasil-pengelolaan.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <x-label>Pencarian</x-label>
                    <x-input name="search" value="{{ request('search') }}" placeholder="Cari jenis limbah, vendor..." />
                </div>
                
                @if(auth()->user()->isAdmin())
                    <div>
                        <x-label>Perusahaan</x-label>
                        <x-select name="perusahaan_id" :options="$perusahaanOptions" value="{{ request('perusahaan_id') }}" placeholder="Semua Perusahaan" />
                    </div>
                @endif
                
                <div>
                    <x-label>Jenis Limbah</x-label>
                    <x-select name="jenis_limbah_id" :options="$jenisLimbahOptions" value="{{ request('jenis_limbah_id') }}" placeholder="Semua Jenis" />
                </div>
                
                <div>
                    <x-label>Vendor</x-label>
                    <x-select name="vendor_id" :options="$vendorOptions" value="{{ request('vendor_id') }}" placeholder="Semua Vendor" />
                </div>
                
                <div>
                    <x-label>Status Hasil</x-label>
                    <x-select name="status_hasil" :options="$statusOptions" value="{{ request('status_hasil') }}" placeholder="Semua Status" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </x-button>
                    
                    @if(request()->hasAny(['search', 'perusahaan_id', 'jenis_limbah_id', 'vendor_id', 'status_hasil', 'tanggal_dari', 'tanggal_sampai']))
                        <x-button variant="outline" href="{{ route('laporan-hasil-pengelolaan.index') }}">
                            Reset
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Bulk Actions untuk Perusahaan -->
        @if(auth()->user()->isPerusahaan() && $laporanHasil->count() > 0)
            <x-card class="mb-6">
                <form action="{{ route('laporan-hasil-pengelolaan.bulk-action') }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin melakukan aksi ini pada item yang dipilih?')">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600">
                            <label for="select-all" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pilih Semua</label>
                        </div>
                        <x-select name="action" placeholder="Pilih Aksi">
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
            @if($laporanHasil->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                @if(auth()->user()->isPerusahaan())
                                    <th class="px-4 py-3">
                                        <input type="checkbox" id="select-all-header" class="rounded border-gray-300 text-green-600">
                                    </th>
                                @endif
                                <th class="px-4 py-3">Tanggal Selesai</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="px-4 py-3">Perusahaan</th>
                                @endif
                                <th class="px-4 py-3">Jenis Limbah</th>
                                <th class="px-4 py-3">Vendor</th>
                                <th class="px-4 py-3">Jumlah Berhasil</th>
                                <th class="px-4 py-3">Efisiensi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($laporanHasil as $item)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    @if(auth()->user()->isPerusahaan())
                                        <td class="px-4 py-3">
                                            @if($item->perusahaan_id === auth()->user()->perusahaan->id)
                                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                                       class="item-checkbox rounded border-gray-300 text-green-600">
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $item->tanggal_selesai->format('d/m/Y') }}</div>
                                            <div class="text-gray-500">{{ $item->tanggal_selesai->format('H:i') }}</div>
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
                                            <div class="font-semibold">{{ $item->pengelolaanLimbah->jenisLimbah->nama }}</div>
                                            <div class="text-gray-500">{{ $item->pengelolaanLimbah->jenisLimbah->kode_limbah }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $item->pengelolaanLimbah->vendor->nama_perusahaan ?? '-' }}</div>
                                            @if($item->pengelolaanLimbah->vendor)
                                                <div class="text-gray-500">{{ $item->pengelolaanLimbah->vendor->email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ number_format($item->jumlah_berhasil_dikelola, 2) }} {{ $item->satuan }}</div>
                                            @if($item->jumlah_residu > 0)
                                                <div class="text-gray-500">Residu: {{ number_format($item->jumlah_residu, 2) }} {{ $item->satuan }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $totalJumlah = $item->jumlah_berhasil_dikelola + ($item->jumlah_residu ?? 0);
                                            $efisiensi = $totalJumlah > 0 ? ($item->jumlah_berhasil_dikelola / $totalJumlah) * 100 : 0;
                                            $colorClass = $efisiensi >= 80 ? 'green' : ($efisiensi >= 60 ? 'yellow' : 'red');
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-{{ $colorClass }}-600 h-2 rounded-full" 
                                                     style="width: {{ min($efisiensi, 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ number_format($efisiensi, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusClass = match($item->status_hasil) {
                                                'berhasil' => 'green',
                                                'sebagian_berhasil' => 'yellow',
                                                'gagal' => 'red',
                                                default => 'gray'
                                            };
                                            $statusName = match($item->status_hasil) {
                                                'berhasil' => 'Berhasil',
                                                'sebagian_berhasil' => 'Sebagian Berhasil',
                                                'gagal' => 'Gagal',
                                                default => ucfirst($item->status_hasil)
                                            };
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $statusClass }}-700 bg-{{ $statusClass }}-100 rounded-full">
                                            {{ $statusName }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <!-- Detail Button - Semua role bisa lihat -->
                                            <a href="{{ route('laporan-hasil-pengelolaan.show', $item) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            
                                            <!-- Edit Button - Hanya perusahaan pemilik -->
                                            @if(auth()->user()->isPerusahaan() && $item->perusahaan_id === auth()->user()->perusahaan->id)
                                                <a href="{{ route('laporan-hasil-pengelolaan.edit', $item) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Delete Button - Hanya perusahaan pemilik -->
                                                <form action="{{ route('laporan-hasil-pengelolaan.destroy', $item) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
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

                                            <!-- Admin Badge - Hanya untuk admin -->
                                            @if(auth()->user()->isAdmin())
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
                <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                    {{ $laporanHasil->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Belum Ada Laporan Hasil Pengelolaan
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                        @if(auth()->user()->isPerusahaan())
                            Laporan hasil pengelolaan akan muncul setelah Anda menyelesaikan pengelolaan limbah dan membuat laporannya.
                        @else
                            Laporan hasil pengelolaan dari perusahaan akan muncul di sini setelah mereka menyelesaikan pengelolaan limbah.
                        @endif
                    </p>
                    @if(auth()->user()->isPerusahaan())
                        <div class="mt-6">
                            <x-button href="{{ route('laporan-hasil-pengelolaan.create') }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Laporan Pertama
                            </x-button>
                        </div>
                    @endif
                </div>
            @endif
        </x-card>

        <!-- Info Box untuk Admin -->
        @if(auth()->user()->isAdmin())
            <x-card class="mt-6">
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                                Informasi untuk Admin
                            </h4>
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Anda dapat melihat semua laporan hasil pengelolaan dari seluruh perusahaan</li>
                                    <li>Gunakan filter perusahaan untuk melihat laporan spesifik</li>
                                    <li>Laporan bersifat readonly - hanya perusahaan yang dapat mengedit</li>
                                    <li>Klik "Detail" untuk melihat informasi lengkap dan dokumentasi</li>
                                    <li>Export CSV tersedia untuk analisis data</li>
                                    <li>Efisiensi dihitung dari perbandingan jumlah berhasil dikelola dengan total jumlah</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif

        <!-- Statistik untuk Admin -->
        @if(auth()->user()->isAdmin())
            <x-card class="mt-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">
                    Statistik Laporan Hasil Pengelolaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-blue-600">Total Laporan</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                    {{ \App\Models\LaporanHasilPengelolaan::count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-green-600">Berhasil</div>
                                <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                                    {{ \App\Models\LaporanHasilPengelolaan::where('status_hasil', 'berhasil')->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-yellow-600">Sebagian Berhasil</div>
                                <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ \App\Models\LaporanHasilPengelolaan::where('status_hasil', 'sebagian_berhasil')->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-red-600">Gagal</div>
                                <div class="text-2xl font-bold text-red-900 dark:text-red-100">
                                    {{ \App\Models\LaporanHasilPengelolaan::where('status_hasil', 'gagal')->count() }}
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
            document.addEventListener('DOMContentLoaded', function() {
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
                    selectAllCheckbox.addEventListener('change', function() {
                        handleSelectAll(this);
                    });
                }

                if (selectAllHeaderCheckbox) {
                    selectAllHeaderCheckbox.addEventListener('change', function() {
                        handleSelectAll(this);
                    });
                }

                // Handle individual checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                        const allChecked = checkedItems.length === itemCheckboxes.length;
                        
                        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                        if (selectAllHeaderCheckbox) selectAllHeaderCheckbox.checked = allChecked;
                    });
                });
            });


            function showSummaryModal() {
                document.getElementById('summaryModal').classList.remove('hidden');
            }

            function hideSummaryModal() {
                document.getElementById('summaryModal').classList.add('hidden');
            }

            // Set default dates (last 30 days)
            document.addEventListener('DOMContentLoaded', function() {
                const endDate = new Date();
                const startDate = new Date();
                startDate.setDate(startDate.getDate() - 30);
                
                const startInput = document.querySelector('input[name="start_date"]');
                const endInput = document.querySelector('input[name="end_date"]');
                
                if (startInput) startInput.value = startDate.toISOString().split('T')[0];
                if (endInput) endInput.value = endDate.toISOString().split('T')[0];
            });
        </script>
    @endif
</x-app>



