<x-app>
    <x-slot:title>
        Detail Laporan Harian - {{ $laporanHarian->jenisLimbah->nama }}
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Laporan Harian Limbah
            </h2>
            <!-- Tambahkan di bagian header untuk menunjukkan mode readonly untuk admin -->
            @if(auth()->user()->isAdmin())
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Mode Admin - Readonly</h4>
                            <p class="text-sm text-blue-600 dark:text-blue-300">Anda melihat laporan ini dalam mode
                                readonly. Hanya perusahaan pemilik yang dapat mengedit.</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="flex space-x-2">
                @if($laporanHarian->canEdit())
                    <x-button variant="secondary" href="{{ route('laporan-harian.edit', $laporanHarian) }}">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                            </path>
                        </svg>
                        Edit
                    </x-button>
                @endif
                <x-button variant="secondary" href="{{ route('laporan-harian.index') }}">
                    Kembali
                </x-button>
            </div>
        </div>
        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Utama -->
            <div class="lg:col-span-2">
                <x-card>
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Laporan</h3>
                        <span
                            class="px-3 py-1 text-sm font-semibold leading-tight text-{{ $laporanHarian->status_badge_class }}-700 bg-{{ $laporanHarian->status_badge_class }}-100 rounded-full">
                            {{ $laporanHarian->status_name }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label>Tanggal Laporan</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $laporanHarian->tanggal->format('d/m/Y') }}</div>
                        </div>

                        <div>
                            <x-label>Waktu Laporan</x-label>
                            <div class="text-gray-700 dark:text-gray-200">
                                {{ $laporanHarian->tanggal_laporan->format('H:i') }}</div>
                        </div>

                        <div>
                            <x-label>Jenis Limbah</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $laporanHarian->jenisLimbah->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $laporanHarian->jenisLimbah->kode_limbah }}</div>
                        </div>

                        <div>
                            <x-label>Kategori</x-label>
                            <div class="text-gray-700 dark:text-gray-200">
                                {{ $laporanHarian->jenisLimbah->kategori_name }}</div>
                        </div>

                        <div>
                            <x-label>Penyimpanan</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $laporanHarian->penyimpanan->nama_penyimpanan }}</div>
                            <div class="text-sm text-gray-500">{{ $laporanHarian->penyimpanan->lokasi }}</div>
                        </div>

                        <div>
                            <x-label>Jumlah</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium text-lg">
                                {{ number_format($laporanHarian->jumlah, 2) }} {{ $laporanHarian->satuan }}
                            </div>
                        </div>
                    </div>

                    @if($laporanHarian->keterangan)
                        <div class="mt-6">
                            <x-label>Keterangan</x-label>
                            <div class="text-gray-700 dark:text-gray-200 mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                {{ $laporanHarian->keterangan }}
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Actions untuk Draft -->
                @if($laporanHarian->canSubmit())
                    <x-card class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Aksi Laporan</h3>
                        <div class="flex space-x-3">
                            <form action="{{ route('laporan-harian.submit', $laporanHarian) }}" method="POST"
                                class="inline">
                                @csrf
                                <x-button type="submit"
                                    onclick="return confirm('Yakin ingin submit laporan ini? Laporan yang sudah disubmit tidak dapat diedit lagi.')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Submit Laporan
                                </x-button>
                            </form>

                            <a href="{{ route('laporan-harian.edit', $laporanHarian) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                    </path>
                                </svg>
                                Edit Laporan
                            </a>
                        </div>
                    </x-card>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Info Jenis Limbah -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Jenis Limbah</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kode:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100 font-medium">{{ $laporanHarian->jenisLimbah->kode_limbah }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kategori:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->jenisLimbah->kategori_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Satuan:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->jenisLimbah->satuan_name }}</span>
                        </div>
                        @if($laporanHarian->jenisLimbah->tingkat_bahaya)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Tingkat Bahaya:</span>
                                                <span class="px-2 py-1 text-xs font-semibold leading-tight 
                                                        {{ $laporanHarian->jenisLimbah->tingkat_bahaya === 'sangat_tinggi' ? 'text-red-700 bg-red-100' :
                            ($laporanHarian->jenisLimbah->tingkat_bahaya === 'tinggi' ? 'text-orange-700 bg-orange-100' :
                                ($laporanHarian->jenisLimbah->tingkat_bahaya === 'sedang' ? 'text-yellow-700 bg-yellow-100' : 'text-green-700 bg-green-100')) }} 
                                                        rounded-full">
                                                    {{ $laporanHarian->jenisLimbah->tingkat_bahaya_name }}
                                                </span>
                                            </div>
                        @endif
                        @if($laporanHarian->jenisLimbah->deskripsi)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400 text-xs">Deskripsi:</span>
                                <p class="text-gray-700 dark:text-gray-300 text-xs mt-1">
                                    {{ $laporanHarian->jenisLimbah->deskripsi }}</p>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Info Penyimpanan -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Penyimpanan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100 font-medium">{{ $laporanHarian->penyimpanan->nama_penyimpanan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Lokasi:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->penyimpanan->lokasi }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jenis:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->penyimpanan->jenis_penyimpanan_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kondisi:</span>
                            <span class="px-2 py-1 text-xs font-semibold leading-tight 
                                {{ $laporanHarian->penyimpanan->kondisi === 'baik' ? 'text-green-700 bg-green-100' :
    ($laporanHarian->penyimpanan->kondisi === 'maintenance' ? 'text-yellow-700 bg-yellow-100' : 'text-red-700 bg-red-100') }} 
                                rounded-full">
                                {{ $laporanHarian->penyimpanan->kondisi_name }}
                            </span>
                        </div>

                        <!-- Kapasitas Info -->
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400 text-xs">Kapasitas:</span>
                                <span class="text-gray-900 dark:text-gray-100 text-xs font-medium">
                                    {{ number_format($laporanHarian->penyimpanan->kapasitas_terpakai, 2) }} /
                                    {{ number_format($laporanHarian->penyimpanan->kapasitas_maksimal, 2) }}
                                    {{ $laporanHarian->penyimpanan->satuan }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $laporanHarian->penyimpanan->status_kapasitas_color }}-600 h-2 rounded-full"
                                    style="width: {{ $laporanHarian->penyimpanan->persentase_kapasitas }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs mt-1">
                                <span
                                    class="text-gray-500">{{ $laporanHarian->penyimpanan->persentase_kapasitas }}%</span>
                                <span
                                    class="px-2 py-1 text-{{ $laporanHarian->penyimpanan->status_kapasitas_color }}-700 bg-{{ $laporanHarian->penyimpanan->status_kapasitas_color }}-100 rounded">
                                    {{ $laporanHarian->penyimpanan->status_kapasitas_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Info Status & Waktu -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Status & Waktu</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span
                                class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $laporanHarian->status_badge_class }}-700 bg-{{ $laporanHarian->status_badge_class }}-100 rounded-full">
                                {{ $laporanHarian->status_name }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Diupdate:</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($laporanHarian->isSubmitted())
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Disubmit:</span>
                                <span
                                    class="text-gray-900 dark:text-gray-100">{{ $laporanHarian->submitted_at?->format('d/m/Y H:i') ?? '-' }}</span>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Quick Actions -->
                <x-card>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h3>
                    <div class="space-y-2">
                        <a href="{{ route('laporan-harian.index', ['penyimpanan_id' => $laporanHarian->penyimpanan_id]) }}"
                            class="block w-full text-center px-3 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            Lihat Laporan Penyimpanan Ini
                        </a>

                        <a href="{{ route('laporan-harian.index', ['jenis_limbah_id' => $laporanHarian->jenis_limbah_id]) }}"
                            class="block w-full text-center px-3 py-2 text-sm text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            Lihat Laporan Jenis Limbah Ini
                        </a>

                        <a href="{{ route('penyimpanan.show', $laporanHarian->penyimpanan) }}"
                            class="block w-full text-center px-3 py-2 text-sm text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            Detail Penyimpanan
                        </a>

                        <a href="{{ route('laporan-harian.create') }}"
                            class="block w-full text-center px-3 py-2 text-sm text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            Buat Laporan Baru
                        </a>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Riwayat Laporan Terkait (Optional) -->
        @if(
                $laporanTerkait = \App\Models\LaporanHarian::where('penyimpanan_id', $laporanHarian->penyimpanan_id)
                    ->where('id', '!=', $laporanHarian->id)
                    ->latest()
                    ->take(5)
                    ->get()
            )
            @if($laporanTerkait->count() > 0)
                <x-card class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Laporan Terkait (Penyimpanan yang
                        Sama)</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Jenis Limbah</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($laporanTerkait as $item)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm">{{ $item->tanggal->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $item->jenisLimbah->nama }}</td>
                                        <td class="px-4 py-3 text-sm">{{ number_format($item->jumlah, 2) }} {{ $item->satuan }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $item->status_badge_class }}-700 bg-{{ $item->status_badge_class }}-100 rounded-full">
                                                {{ $item->status_name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('laporan-harian.show', $item) }}"
                                                class="flex items-center px-2 py-1 text-blue-600 rounded hover:bg-blue-50"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('laporan-harian.index', ['penyimpanan_id' => $laporanHarian->penyimpanan_id]) }}"
                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                            Lihat Semua Laporan Penyimpanan Ini →
                        </a>
                    </div>
                </x-card>
            @endif
        @endif
    </div>
</x-app>