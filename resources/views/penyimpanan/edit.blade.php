<x-app>
    <x-slot:title>
        Edit Penyimpanan Limbah
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Penyimpanan Limbah
            </h2>
            <div class="flex space-x-2">
                <x-button variant="secondary" href="{{ route('penyimpanan.show', $penyimpanan) }}">
                    Detail
                </x-button>
                <x-button variant="secondary" href="{{ route('penyimpanan.index') }}">
                    Kembali
                </x-button>
            </div>
        </div>

        @if ($errors->any())
            <x-alert type="error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card>
            <form action="{{ route('penyimpanan.update', $penyimpanan) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- TAMBAH: Jenis Limbah (readonly di edit) -->
                        <x-form-group label="Jenis Limbah" name="jenis_limbah_id">
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $penyimpanan->jenisLimbah->nama ?? 'Tidak ada' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Kode: {{ $penyimpanan->jenisLimbah->kode_limbah ?? '-' }} | 
                                    Satuan: {{ $penyimpanan->satuan }}
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Jenis limbah tidak dapat diubah setelah penyimpanan dibuat</p>
                        </x-form-group>

                        <x-form-group label="Nama Penyimpanan" name="nama_penyimpanan" required>
                            <x-input name="nama_penyimpanan" :value="$penyimpanan->nama_penyimpanan" placeholder="Contoh: Tangki Limbah B3 - 001" required />
                        </x-form-group>

                        <x-form-group label="Lokasi Penyimpanan" name="lokasi" required>
                            <x-input name="lokasi" :value="$penyimpanan->lokasi" placeholder="Contoh: Gudang A, Lantai 2" required />
                        </x-form-group>

                        <x-form-group label="Jenis Penyimpanan" name="jenis_penyimpanan" required>
                            <x-select name="jenis_penyimpanan" :options="\App\Models\Penyimpanan::getJenisPenyimpananOptions()" :value="$penyimpanan->jenis_penyimpanan" required />
                        </x-form-group>

                        <div class="grid grid-cols-2 gap-4">
                            <x-form-group label="Kapasitas Maksimal" name="kapasitas_maksimal" required>
                                <div class="relative">
                                    <x-input type="number" name="kapasitas_maksimal" step="0.01" min="0" :value="$penyimpanan->kapasitas_maksimal" required />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">{{ $penyimpanan->satuan }}</span>
                                    </div>
                                </div>
                            </x-form-group>

                            <x-form-group label="Kapasitas Terpakai" name="kapasitas_terpakai">
                                <div class="relative">
                                    <x-input type="number" name="kapasitas_terpakai" step="0.01" min="0" :value="$penyimpanan->kapasitas_terpakai" readonly class="bg-gray-50" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">{{ $penyimpanan->satuan }}</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Kapasitas terpakai diupdate otomatis dari laporan harian</p>
                            </x-form-group>
                        </div>

                        <!-- UPDATE: Info kapasitas dengan satuan yang benar -->
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <div class="flex justify-between text-sm">
                                <span>Persentase Terpakai:</span>
                                <span class="font-medium">{{ number_format($penyimpanan->getPersentaseKapasitas(), 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                @php
                                    $persentase = $penyimpanan->getPersentaseKapasitas();
                                    $color = $persentase >= 90 ? 'red' : ($persentase >= 75 ? 'yellow' : 'green');
                                @endphp
                                <div class="bg-{{ $color }}-600 h-2 rounded-full" 
                                     style="width: {{ min($persentase, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600 mt-1">
                                <span>Sisa: {{ number_format($penyimpanan->kapasitas_maksimal - $penyimpanan->kapasitas_terpakai, 2) }} {{ $penyimpanan->satuan }}</span>
                                <span class="px-2 py-1 text-{{ $color }}-700 bg-{{ $color }}-100 rounded">
                                    @if($persentase >= 90)
                                        Penuh
                                    @elseif($persentase >= 75)
                                        Hampir Penuh
                                    @else
                                        Aman
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Kondisi Penyimpanan" name="kondisi" required>
                            <x-select name="kondisi" :options="\App\Models\Penyimpanan::getKondisiOptions()" :value="$penyimpanan->kondisi" required />
                        </x-form-group>

                        <x-form-group label="Status" name="status" required>
                            <x-select name="status" :options="\App\Models\Penyimpanan::getStatusOptions()" :value="$penyimpanan->status" required />
                        </x-form-group>

                        <x-form-group label="Tanggal Pembuatan/Instalasi" name="tanggal_pembuatan" required>
                            <x-input type="date" name="tanggal_pembuatan" :value="$penyimpanan->tanggal_pembuatan->format('Y-m-d')" max="{{ date('Y-m-d') }}" required />
                        </x-form-group>

                        <x-form-group label="Catatan" name="catatan">
                            <x-textarea name="catatan" rows="4" :value="$penyimpanan->catatan" placeholder="Catatan tambahan tentang penyimpanan ini..." />
                        </x-form-group>

                        <!-- TAMBAH: Info jenis limbah -->
                        <div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                            <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">Informasi Jenis Limbah</h4>
                            <div class="text-sm text-green-700 dark:text-green-300 space-y-1">
                                <p><strong>Nama:</strong> {{ $penyimpanan->jenisLimbah->nama ?? '-' }}</p>
                                <p><strong>Kategori:</strong> {{ $penyimpanan->jenisLimbah->kategori_name ?? '-' }}</p>
                                <p><strong>Tingkat Bahaya:</strong> {{ $penyimpanan->jenisLimbah->tingkat_bahaya_name ?? '-' }}</p>
                                <p><strong>Satuan:</strong> {{ $penyimpanan->satuan }}</p>
                            </div>
                        </div>

                        <!-- Info Tambahan -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2">Informasi Sistem</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Dibuat:</span>
                                    <span>{{ $penyimpanan->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Terakhir Update:</span>
                                    <span>{{ $penyimpanan->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Laporan:</span>
                                    <span>{{ $penyimpanan->laporanHarian()->count() }} laporan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAMBAH: Warning jika ada laporan harian -->
                @if($penyimpanan->laporanHarian()->count() > 0)
                    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Penyimpanan ini sudah memiliki {{ $penyimpanan->laporanHarian()->count() }} laporan harian. Perubahan pada kapasitas maksimal dapat mempengaruhi perhitungan yang sudah ada.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end mt-8 space-x-3">
                    <x-button variant="secondary" href="{{ route('penyimpanan.index') }}">
                        Batal
                    </x-button>
                    <x-button type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Perbarui Penyimpanan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app>
