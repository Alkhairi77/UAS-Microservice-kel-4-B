<x-app>
    <x-slot:title>
        Detail Penyimpanan - {{ $penyimpanan->nama_penyimpanan }}
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Penyimpanan Limbah
            </h2>
            <div class="flex space-x-2">
                <x-button variant="secondary" href="{{ route('penyimpanan.edit', $penyimpanan) }}">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                    Edit
                </x-button>
                <x-button variant="secondary" href="{{ route('penyimpanan.index') }}">
                    Kembali
                </x-button>
            </div>
        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Utama -->
            <div class="lg:col-span-2">
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Informasi Penyimpanan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label>Nama Penyimpanan</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">{{ $penyimpanan->nama_penyimpanan }}</div>
                        </div>
                        
                        <div>
                            <x-label>Lokasi</x-label>
                            <div class="text-gray-700 dark:text-gray-200">{{ $penyimpanan->lokasi }}</div>
                        </div>
                        
                        <div>
                            <x-label>Jenis Penyimpanan</x-label>
                            <div class="text-gray-700 dark:text-gray-200">
                                {{ \App\Models\Penyimpanan::getJenisPenyimpananOptions()[$penyimpanan->jenis_penyimpanan] ?? $penyimpanan->jenis_penyimpanan }}
                            </div>
                        </div>
                        
                        <div>
                            <x-label>Tanggal Pembuatan</x-label>
                            <div class="text-gray-700 dark:text-gray-200">{{ $penyimpanan->tanggal_pembuatan->format('d/m/Y') }}</div>
                                @if($penyimpanan->getPersentaseKapasitas() >= 75)
                                    <div class="mt-4 p-4 {{ $penyimpanan->getPersentaseKapasitas() >= 90 ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }} border rounded-lg">
                                        <h3 class="text-sm font-medium {{ $penyimpanan->getPersentaseKapasitas() >= 90 ? 'text-red-800 dark:text-red-200' : 'text-yellow-800 dark:text-yellow-200' }}">
                                            {{ $penyimpanan->getPersentaseKapasitas() >= 90 ? 'Penyimpanan Penuh!' : 'Penyimpanan Hampir Penuh!' }}
                                        </h3>
                                        <div class="mt-2 text-sm {{ $penyimpanan->getPersentaseKapasitas() >= 90 ? 'text-red-700 dark:text-red-300' : 'text-yellow-700 dark:text-yellow-300' }}">
                                            <p>
                                                Kapasitas terpakai: {{ number_format($penyimpanan->kapasitas_terpakai, 2) }} {{ $penyimpanan->satuan }} 
                                                ({{ number_format($penyimpanan->getPersentaseKapasitas(), 1) }}%)
                                            </p>
                                            <p>
                                                {{ $penyimpanan->getPersentaseKapasitas() >= 90 ? 'Penyimpanan sudah mencapai kapasitas penuh. Segera lakukan pengelolaan limbah.' : 'Penyimpanan hampir penuh. Pertimbangkan untuk melakukan pengelolaan limbah segera.' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Info Tambahan -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Tambahan</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $penyimpanan->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Diupdate:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $penyimpanan->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Umur:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $penyimpanan->tanggal_pembuatan->diffForHumans() }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Riwayat Laporan Harian (jika ada) -->
        @if($penyimpanan->laporanHarian->count() > 0)
            <x-card class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Riwayat Laporan Harian (5 Terakhir)</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Jenis Limbah</th>
                                <th class="px-4 py-3">Jumlah</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($penyimpanan->laporanHarian->take(5) as $laporan)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3 text-sm">{{ $laporan->tanggal_laporan->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $laporan->jenisLimbah->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ number_format($laporan->jumlah, 2) }} {{ $laporan->satuan }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                            Tersimpan
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($penyimpanan->laporanHarian->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('laporan-harian.index', ['penyimpanan_id' => $penyimpanan->id]) }}" 
                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                            Lihat Semua Laporan →
                        </a>
                    </div>
                @endif
            </x-card>
        @endif
    </div>
</x-app>   