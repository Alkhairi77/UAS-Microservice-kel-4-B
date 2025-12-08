<x-app>
    <x-slot:title>
        Detail Pengelolaan Limbah
    </x-slot:title>
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Pengelolaan Limbah
            </h2>
            <div class="flex space-x-2">
                @if($pengelolaanLimbah->status === 'draft')
                    <x-button href="{{ route('pengelolaan-limbah.edit', $pengelolaanLimbah) }}">
                        Edit
                    </x-button>
                @endif
                <x-button variant="secondary" href="{{ route('pengelolaan-limbah.index') }}">
                    Kembali
                </x-button>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Utama -->
            <div class="lg:col-span-2">
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Pengelolaan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <x-label>Tanggal Pengelolaan</x-label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $pengelolaanLimbah->tanggal_mulai->format('d F Y') }}
                                </div>
                            </div>

                            <div>
                                <x-label>Jenis Limbah</x-label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $pengelolaanLimbah->jenisLimbah->nama }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Kode: {{ $pengelolaanLimbah->jenisLimbah->kode_limbah }}
                                </div>
                            </div>

                            <div>
                                <x-label>Jumlah Dikelola</x-label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium text-lg">
                                    {{ number_format($pengelolaanLimbah->jumlah_dikelola, 2) }} {{ $pengelolaanLimbah->satuan }}
                                </div>
                            </div>

                            <div>
                                <x-label>Metode Pengelolaan</x-label>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $pengelolaanLimbah->metode_pengelolaan_name }}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-label>Status</x-label>
                                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                                    @if($pengelolaanLimbah->status === 'draft') text-gray-700 bg-gray-100
                                    @elseif($pengelolaanLimbah->status === 'dalam_proses') text-blue-700 bg-blue-100
                                    @elseif($pengelolaanLimbah->status === 'selesai') text-green-700 bg-green-100
                                    @else text-red-700 bg-red-100
                                    @endif">
                                    {{ $pengelolaanLimbah->status_name }}
                                </span>
                            </div>

                            @if($pengelolaanLimbah->vendor)
                                <div>
                                    <x-label>Vendor</x-label>
                                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $pengelolaanLimbah->vendor->nama_perusahaan }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $pengelolaanLimbah->vendor->jenis_layanan_name }}
                                    </div>
                                </div>
                            @endif

                            @if($pengelolaanLimbah->biaya_pengelolaan)
                                <div>
                                    <x-label>Biaya Pengelolaan</x-label>
                                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                                        Rp {{ number_format($pengelolaanLimbah->biaya_pengelolaan, 2, ',', '.') }}
                                    </div>
                                </div>
                            @endif

                            <div>
                                <x-label>Dibuat</x-label>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $pengelolaanLimbah->created_at->format('d F Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pengelolaanLimbah->keterangan)
                        <div class="mt-6">
                            <x-label>Keterangan</x-label>
                            <div class="text-gray-700 dark:text-gray-200 mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                {{ $pengelolaanLimbah->keterangan }}
                            </div>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Info Penyimpanan -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Penyimpanan Sumber</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $pengelolaanLimbah->penyimpanan->nama_penyimpanan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Lokasi:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $pengelolaanLimbah->penyimpanan->lokasi }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kapasitas:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ number_format($pengelolaanLimbah->penyimpanan->kapasitas_maksimal, 2) }} {{ $pengelolaanLimbah->penyimpanan->satuan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Stok Saat Ini:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ number_format($pengelolaanLimbah->penyimpanan->kapasitas_terpakai, 2) }} {{ $pengelolaanLimbah->penyimpanan->satuan }}</span>
                        </div>
                    </div>
                </x-card>

                <!-- Timeline Status -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Riwayat Pengelolaan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                            <div class="text-sm">
                                <div class="font-medium">Dibuat</div>
                                <div class="text-gray-500">{{ $pengelolaanLimbah->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        
                        @if($pengelolaanLimbah->updated_at != $pengelolaanLimbah->created_at)
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <div class="font-medium">Terakhir Diupdate</div>
                                    <div class="text-gray-500">{{ $pengelolaanLimbah->updated_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($pengelolaanLimbah->status === 'selesai')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <div class="font-medium">Selesai</div>
                                    <div class="text-gray-500">{{ $pengelolaanLimbah->updated_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Actions -->
                @if($pengelolaanLimbah->status === 'draft')
                    <x-card>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Aksi</h3>
                        <div class="space-y-2">
                            <x-button href="{{ route('pengelolaan-limbah.edit', $pengelolaanLimbah) }}" class="w-full">
                                Edit Pengelolaan
                            </x-button>
                            
                            <form action="{{ route('pengelolaan-limbah.destroy', $pengelolaanLimbah) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus pengelolaan limbah ini?');">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger" class="w-full">
                                    Hapus Pengelolaan
                                </x-button>
                            </form>
                        </div>
                    </x-card>
                @endif
            </div>
        </div>
    </div>
</x-app>