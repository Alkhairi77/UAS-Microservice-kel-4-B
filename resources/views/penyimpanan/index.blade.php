<x-app>
    <x-slot:title>
        Penyimpanan Limbah
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Manajemen Penyimpanan Limbah
            </h2>
            <x-button>
            <a href="{{ route('penyimpanan.create') }}">
                + Tambah Penyimpanan
            </a>
            </x-button>
        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <!-- Filter Section -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('penyimpanan.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                
                <div class="flex-1 min-w-48">
                    <x-label>Pencarian</x-label>
                    <x-input name="search" value="{{ request('search') }}" placeholder="Cari nama, lokasi..." />
                </div>
                
                <!-- TAMBAH: Filter Jenis Limbah -->
                <div class="min-w-30">
                    <x-label>Jenis Limbah</x-label>
                    <x-select name="jenis_limbah_id" placeholder="Semua Jenis Limbah">
                        @foreach(\App\Models\JenisLimbah::where('status', 'active')->orderBy('nama')->get() as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_limbah_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                
                <div class="min-w-30">
                    <x-label>Jenis Penyimpanan</x-label>
                    <x-select name="jenis_penyimpanan" :options="$jenisOptions" value="{{ request('jenis_penyimpanan') }}" placeholder="Semua Jenis" />
                </div>
                
                <div class="min-w-30">
                    <x-label>Kondisi</x-label>
                    <x-select name="kondisi" :options="$kondisiOptions" value="{{ request('kondisi') }}" placeholder="Semua Kondisi" />
                </div>
                
                <div >
                    <x-label>Status</x-label>
                    <x-select name="status" :options="$statusOptions" value="{{ request('status') }}" placeholder="Semua Status" />
                </div>
                
                <div class="flex items-end space-x-2">
                    <x-button type="submit" size="sm">Filter</x-button>
                    <x-button type="button" variant="secondary" size="sm" onclick="window.location.href='{{ route('penyimpanan.index') }}'">Reset</x-button>
                </div>
            </form>
        </x-card>

        <!-- Data Table -->
        <x-card :padding="false">
            @if ($penyimpanan->count())
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3">Nama Penyimpanan</th>
                                <th class="px-4 py-3">Lokasi</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Kapasitas</th>
                                <th class="px-4 py-3">Status Kapasitas</th>
                                <th class="px-4 py-3">Kondisi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($penyimpanan as $item)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $item->nama_penyimpanan }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->jenis_penyimpanan }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $item->lokasi }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $jenisOptions[$item->jenis_penyimpanan] ?? $item->jenis_penyimpanan }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex flex-col">
                                            <span class="font-medium">
                                                {{ number_format($item->kapasitas_terpakai, 2) }} / {{ number_format($item->kapasitas_maksimal, 2) }} {{ $item->satuan }}
                                            </span>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-{{ $item->status_kapasitas_color }}-600 h-2 rounded-full" 
                                                     style="width: {{ $item->persentase_kapasitas }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $item->persentase_kapasitas }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $item->status_kapasitas_color }}-700 bg-{{ $item->status_kapasitas_color }}-100 rounded-full">
                                            {{ $item->status_kapasitas_text }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight 
                                            {{ $item->kondisi === 'baik' ? 'text-green-700 bg-green-100' : 
                                               ($item->kondisi === 'maintenance' ? 'text-yellow-700 bg-yellow-100' : 'text-red-700 bg-red-100') }} 
                                            rounded-full">
                                            {{ $kondisiOptions[$item->kondisi] ?? $item->kondisi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight 
                                            {{ $item->status === 'aktif' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }} 
                                            rounded-full">
                                            {{ $statusOptions[$item->status] ?? $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <a href="{{ route('penyimpanan.show', $item->id) }}"
                                                class="flex items-center px-2 py-1 text-blue-600 rounded hover:bg-blue-50"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                            
                                            <a href="{{ route('penyimpanan.edit', $item->id) }}"
                                                class="flex items-center px-2 py-1 text-green-600 rounded hover:bg-green-50"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                            
                                            <form action="{{ route('penyimpanan.destroy', $item->id) }}" method="POST" 
                                                  class="inline" onsubmit="return confirm('Yakin ingin menghapus penyimpanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="flex items-center px-2 py-1 text-red-600 rounded hover:bg-red-50"
                                                        title="Hapus">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $penyimpanan->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada data penyimpanan.</p>
                    <x-button class="mt-4">
                        <a href="{{ route('penyimpanan.create') }}">
                        Tambah Penyimpanan Pertama
                    </a>
                    </x-button>
                </div>
            @endif
        </x-card>
    </div>
</x-app>

