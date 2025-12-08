<x-app>
    <x-slot:title>
        Detail Laporan Hasil Pengelolaan
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <!-- Info Box untuk Admin -->
        @if(auth()->user()->isAdmin())
            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Mode Admin - Readonly</h4>
                        <p class="text-sm text-blue-600 dark:text-blue-300">
                            Anda melihat laporan dari {{ $laporanHasilPengelolaan->perusahaan->nama_perusahaan }} dalam mode
                            readonly.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Laporan Hasil Pengelolaan
                @if(auth()->user()->isAdmin())
                    <span class="text-sm font-normal text-gray-500">
                        - {{ $laporanHasilPengelolaan->perusahaan->nama_perusahaan }}
                    </span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <!-- Edit button hanya untuk perusahaan pemilik -->
                @if(
                        auth()->user()->isPerusahaan() &&
                        $laporanHasilPengelolaan->perusahaan_id === auth()->user()->perusahaan->id
                    )
                    <x-button href="{{ route('laporan-hasil-pengelolaan.edit', $laporanHasilPengelolaan) }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </x-button>
                @endif
                <x-button variant="secondary" href="{{ route('laporan-hasil-pengelolaan.index') }}">
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

        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Informasi Laporan -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Informasi Laporan
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <x-label>Tanggal Selesai</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $laporanHasilPengelolaan->tanggal_selesai->format('d F Y') }}
                        </div>
                    </div>

                    <div>
                        <x-label>Status Hasil</x-label>
                        @php
                            $statusClass = match ($laporanHasilPengelolaan->status_hasil) {
                                'berhasil' => 'green',
                                'sebagian_berhasil' => 'yellow',
                                'gagal' => 'red',
                                default => 'gray'
                            };
                            $statusName = match ($laporanHasilPengelolaan->status_hasil) {
                                'berhasil' => 'Berhasil',
                                'sebagian_berhasil' => 'Sebagian Berhasil',
                                'gagal' => 'Gagal',
                                default => ucfirst($laporanHasilPengelolaan->status_hasil)
                            };
                        @endphp
                        <span
                            class="px-3 py-1 text-sm font-semibold leading-tight text-{{ $statusClass }}-700 bg-{{ $statusClass }}-100 rounded-full">
                            {{ $statusName }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label>Jumlah Berhasil Dikelola</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ number_format($laporanHasilPengelolaan->jumlah_berhasil_dikelola, 2) }}
                                {{ $laporanHasilPengelolaan->satuan }}
                            </div>
                        </div>

                        <div>
                            <x-label>Jumlah Residu</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ number_format($laporanHasilPengelolaan->jumlah_residu ?? 0, 2) }}
                                {{ $laporanHasilPengelolaan->satuan }}
                            </div>
                        </div>
                    </div>

                    <!-- Efisiensi -->
                    <div>
                        <x-label>Efisiensi Pengelolaan</x-label>
                        @php
                            $totalJumlah = $laporanHasilPengelolaan->jumlah_berhasil_dikelola + ($laporanHasilPengelolaan->jumlah_residu ?? 0);
                            $efisiensi = $totalJumlah > 0 ? ($laporanHasilPengelolaan->jumlah_berhasil_dikelola / $totalJumlah) * 100 : 0;
                            $colorClass = $efisiensi >= 80 ? 'green' : ($efisiensi >= 60 ? 'yellow' : 'red');
                        @endphp
                        <div class="flex items-center mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-3 mr-3">
                                <div class="bg-{{ $colorClass }}-600 h-3 rounded-full"
                                    style="width: {{ min($efisiensi, 100) }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-{{ $colorClass }}-600">
                                {{ number_format($efisiensi, 1) }}%
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($efisiensi >= 80)
                                Efisiensi sangat baik
                            @elseif($efisiensi >= 60)
                                Efisiensi cukup baik
                            @else
                                Perlu perbaikan efisiensi
                            @endif
                        </p>
                    </div>

                    @if($laporanHasilPengelolaan->biaya_aktual)
                        <div>
                            <x-label>Biaya Aktual</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                Rp {{ number_format($laporanHasilPengelolaan->biaya_aktual, 0, ',', '.') }}
                            </div>
                        </div>
                    @endif

                    @if($laporanHasilPengelolaan->nomor_sertifikat)
                        <div>
                            <x-label>Nomor Sertifikat</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $laporanHasilPengelolaan->nomor_sertifikat }}
                            </div>
                        </div>
                    @endif

                    @if($laporanHasilPengelolaan->metode_disposal_akhir)
                        <div>
                            <x-label>Metode Disposal Akhir</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ ucfirst(str_replace('_', ' ', $laporanHasilPengelolaan->metode_disposal_akhir)) }}
                            </div>
                        </div>
                    @endif

                    @if($laporanHasilPengelolaan->catatan_hasil)
                        <div>
                            <x-label>Catatan Hasil</x-label>
                            <div
                                class="text-gray-700 dark:text-gray-200 text-sm bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                {{ $laporanHasilPengelolaan->catatan_hasil }}
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>

            <!-- Informasi Pengelolaan -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Informasi Pengelolaan
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <x-label>Jenis Limbah</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $laporanHasilPengelolaan->pengelolaanLimbah->jenisLimbah->nama }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Kode: {{ $laporanHasilPengelolaan->pengelolaanLimbah->jenisLimbah->kode_limbah }}
                        </div>
                    </div>

                    <div>
                        <x-label>Penyimpanan Asal</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $laporanHasilPengelolaan->pengelolaanLimbah->penyimpanan->nama_penyimpanan }}
                        </div>
                    </div>

                    <div>
                        <x-label>Vendor Pengelola</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $laporanHasilPengelolaan->pengelolaanLimbah->vendor->nama_perusahaan ?? 'Internal' }}
                        </div>
                        @if($laporanHasilPengelolaan->pengelolaanLimbah->vendor)
                            <div class="text-sm text-gray-500">
                                {{ $laporanHasilPengelolaan->pengelolaanLimbah->vendor->email }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-label>Tanggal Mulai Pengelolaan</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $laporanHasilPengelolaan->pengelolaanLimbah->tanggal_mulai->format('d F Y') }}
                        </div>
                    </div>

                    <div>
                        <x-label>Jumlah Dikelola (Target)</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ number_format($laporanHasilPengelolaan->pengelolaanLimbah->jumlah_dikelola, 2) }}
                            {{ $laporanHasilPengelolaan->satuan }}
                        </div>
                    </div>

                    <div>
                        <x-label>Jenis Pengelolaan</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ ucfirst(str_replace('_', ' ', $laporanHasilPengelolaan->pengelolaanLimbah->jenis_pengelolaan)) }}
                        </div>
                    </div>

                    <div>
                        <x-label>Metode Pengelolaan</x-label>
                        <div class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ ucfirst(str_replace('_', ' ', $laporanHasilPengelolaan->pengelolaanLimbah->metode_pengelolaan)) }}
                        </div>
                    </div>

                    <!-- Perbandingan Target vs Hasil -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Perbandingan Target vs Hasil</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Target:</span>
                                <span
                                    class="font-medium">{{ number_format($laporanHasilPengelolaan->pengelolaanLimbah->jumlah_dikelola, 2) }}
                                    {{ $laporanHasilPengelolaan->satuan }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Berhasil:</span>
                                <span
                                    class="font-medium text-green-600">{{ number_format($laporanHasilPengelolaan->jumlah_berhasil_dikelola, 2) }}
                                    {{ $laporanHasilPengelolaan->satuan }}</span>
                            </div>
                            @if($laporanHasilPengelolaan->jumlah_residu > 0)
                                <div class="flex justify-between text-sm">
                                    <span>Residu:</span>
                                    <span
                                        class="font-medium text-red-600">{{ number_format($laporanHasilPengelolaan->jumlah_residu, 2) }}
                                        {{ $laporanHasilPengelolaan->satuan }}</span>
                                </div>
                            @endif
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between text-sm font-medium">
                                    <span>Pencapaian:</span>
                                    <span class="text-{{ $colorClass }}-600">
                                        {{ number_format(($laporanHasilPengelolaan->jumlah_berhasil_dikelola / $laporanHasilPengelolaan->pengelolaanLimbah->jumlah_dikelola) * 100, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Informasi Perusahaan (hanya untuk admin) -->
        @if(auth()->user()->isAdmin())
            <x-card class="mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Informasi Perusahaan
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-label>Nama Perusahaan</x-label>
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $laporanHasilPengelolaan->perusahaan->nama_perusahaan }}
                            </div>
                        </div>
                        <div>
                            <x-label>Email</x-label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $laporanHasilPengelolaan->perusahaan->email }}
                            </div>
                        </div>
                        <div>
                            <x-label>Telepon</x-label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $laporanHasilPengelolaan->perusahaan->telepon ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <x-label>NPWP</x-label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $laporanHasilPengelolaan->perusahaan->npwp ?? '-' }}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <x-label>Alamat</x-label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $laporanHasilPengelolaan->perusahaan->alamat }}
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif

        <!-- Dokumentasi -->
        @if($laporanHasilPengelolaan->dokumentasi)
            <x-card class="mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Dokumentasi
                    </h3>
                </div>

                <div class="p-6">
                    @php
                        $files = json_decode($laporanHasilPengelolaan->dokumentasi, true) ?? [];
                    @endphp

                    @if(count($files) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($files as $index => $file)
                                @php
                                    $fileName = basename($file);
                                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                                    $isPdf = $fileExtension === 'pdf';
                                @endphp

                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    @if($isImage)
                                        <div class="mb-3">
                                            <img src="{{ Storage::url($file) }}" alt="Dokumentasi {{ $index + 1 }}"
                                                class="w-full h-32 object-cover rounded-md">
                                        </div>
                                    @elseif($isPdf)
                                        <div class="mb-3 flex items-center justify-center h-32 bg-red-50 dark:bg-red-900 rounded-md">
                                            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="mb-3 flex items-center justify-center h-32 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 truncate" title="{{ $fileName }}">
                                            {{ Str::limit($fileName, 30) }}
                                        </div>
                                        <div class="text-gray-500 uppercase">{{ $fileExtension }}</div>
                                    </div>

                                    <div class="mt-3">
                                        <a href="{{ route('laporan-hasil-pengelolaan.download-dokumentasi', [$laporanHasilPengelolaan, $index]) }}"
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada dokumentasi</p>
                        </div>
                    @endif
                </div>
            </x-card>
        @endif

        <!-- Timeline/Riwayat -->
        <x-card class="mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Timeline Pengelolaan
                </h3>
            </div>

            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <!-- Mulai Pengelolaan -->
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                    aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Pengelolaan dimulai</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $laporanHasilPengelolaan->pengelolaanLimbah->tanggal_mulai->format('d F Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $laporanHasilPengelolaan->pengelolaanLimbah->tanggal_mulai->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Selesai Pengelolaan -->
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full bg-{{ $statusClass }}-500 flex items-center justify-center ring-8 ring-white">
                                            @if($laporanHasilPengelolaan->status_hasil === 'berhasil')
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @elseif($laporanHasilPengelolaan->status_hasil === 'sebagian_berhasil')
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Pengelolaan selesai - {{ $statusName }}</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $laporanHasilPengelolaan->tanggal_selesai->format('d F Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $laporanHasilPengelolaan->tanggal_selesai->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Laporan Dibuat -->
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Laporan hasil dibuat</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $laporanHasilPengelolaan->created_at->format('d F Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $laporanHasilPengelolaan->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </x-card>

        <!-- Action Buttons -->
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            @if(
                    auth()->user()->isPerusahaan() &&
                    $laporanHasilPengelolaan->perusahaan_id === auth()->user()->perusahaan->id
                )
                <x-button href="{{ route('laporan-hasil-pengelolaan.edit', $laporanHasilPengelolaan) }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Laporan
                </x-button>

                <!-- Export PDF Button -->
                <x-button variant="secondary"
                    href="{{ route('laporan-hasil-pengelolaan.single.pdf', $laporanHasilPengelolaan) }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    Export PDF
                </x-button>

                <form action="{{ route('laporan-hasil-pengelolaan.destroy', $laporanHasilPengelolaan) }}" method="POST"
                    class="inline"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Hapus Laporan
                    </x-button>
                </form>
            @endif

            <x-button variant="secondary" href="{{ route('laporan-hasil-pengelolaan.index') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </x-button>
        </div>
    </div>
</x-app>