<x-app>
    <x-slot:title>
        Pengelolaan Limbah
    </x-slot:title>
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Manajemen Pengelolaan Limbah
            </h2>
            <div class="flex space-x-2">
                <x-button href="{{ route('pengelolaan-limbah.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pengelolaan
                </x-button>
            </div>
        </div>
        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <!-- Filter Section -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('pengelolaan-limbah.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="flex-1 min-w-48">
                    <x-label>Pencarian</x-label>
                    <x-input name="search" value="{{ request('search') }}" placeholder="Cari nomor manifest, keterangan..." />
                </div>
                
                <div>
                    <x-label>Jenis Limbah</x-label>
                    <x-select name="jenis_limbah_id" :options="$jenisLimbahOptions" value="{{ request('jenis_limbah_id') }}" placeholder="Semua Jenis" />
                </div>
                
                <div>
                    <x-label>Jenis Pengelolaan</x-label>
                    <x-select name="jenis_pengelolaan" :options="$jenisOptions" value="{{ request('jenis_pengelolaan') }}" placeholder="Semua Jenis" />
                </div>
                
                <div>
                    <x-label>Status</x-label>
                    <x-select name="status" :options="$statusOptions" value="{{ request('status') }}" placeholder="Semua Status" />
                </div>
                
                <div>
                    <x-label>Vendor</x-label>
                    <x-select name="vendor_id" :options="$vendorOptions" value="{{ request('vendor_id') }}" placeholder="Semua Vendor" />
                </div>
                
                <div>
                    <x-label>Tanggal Dari</x-label>
                    <x-input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" />
                </div>
                
                <div>
                    <x-label>Tanggal Sampai</x-label>
                    <x-input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" />
                </div>
                
                <div class="md:col-span-6 flex items-end space-x-2">
                    <x-button type="submit" variant="secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </x-button>
                    
                    @if(request()->hasAny(['search', 'jenis_limbah_id', 'jenis_pengelolaan', 'status', 'vendor_id', 'tanggal_dari', 'tanggal_sampai']))
                        <x-button href="{{ route('pengelolaan-limbah.index') }}" variant="secondary">
                            Reset
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Data Table -->
        <x-card>
            @if($pengelolaanLimbah->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Jenis Limbah</th>
                                <th class="px-4 py-3">Jumlah</th>
                                <th class="px-4 py-3">Jenis Pengelolaan</th>
                                <th class="px-4 py-3">Metode</th>
                                <th class="px-4 py-3">Vendor</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($pengelolaanLimbah as $item)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $item->tanggal_mulai->format('d/m/Y') }}</div>
                                        @if($item->tanggal_selesai)
                                            <div class="text-xs text-gray-500">Selesai: {{ $item->tanggal_selesai->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        {{-- PERBAIKAN: Langsung akses jenisLimbah --}}
                                        <div class="font-medium">{{ $item->jenisLimbah->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->jenisLimbah->kode_limbah }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ number_format($item->jumlah_dikelola, 2) }} {{ $item->satuan }}</div>
                                        @if($item->nomor_manifest)
                                            <div class="text-xs text-gray-500">{{ $item->nomor_manifest }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm">{{ $item->jenis_pengelolaan_name }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm">{{ $item->metode_pengelolaan_name }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($item->vendor)
                                            <div class="text-sm">{{ $item->vendor->nama_perusahaan }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                                            @if($item->status === 'diproses') text-yellow-700 bg-yellow-100
                                            @elseif($item->status === 'dalam_pengangkutan') text-blue-700 bg-blue-100
                                            @elseif($item->status === 'selesai') text-green-700 bg-green-100
                                            @else text-red-700 bg-red-100
                                            @endif">
                                            {{ $item->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <a href="{{ route('pengelolaan-limbah.show', $item) }}"
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
                                            
                                            @if($item->canEdit())
                                                <a href="{{ route('pengelolaan-limbah.edit', $item) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif
                                            
                                            @if($item->canEdit())
                                                <form action="{{ route('pengelolaan-limbah.destroy', $item) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengelolaan limbah ini?');">
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $pengelolaanLimbah->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada data pengelolaan limbah.</p>
                    <x-button href="{{ route('pengelolaan-limbah.create') }}" class="mt-4">
                        Tambah Pengelolaan Pertama
                    </x-button>
                </div>
            @endif
        </x-card>
    </div>
</x-app>