<x-app>
    <x-slot:title>
        Dashboard Perusahaan
    </x-slot:title>
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Dashboard Perusahaan
        </h2>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800"
                role="alert">
                {{ session('info') }}
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex items-center">
                @if($perusahaan->logo)
                    <img src="{{ Storage::url($perusahaan->logo) }}" alt="Logo"
                        class="w-16 h-16 rounded-full object-cover mr-4">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-4">
                        <span class="text-gray-500 dark:text-gray-400 text-xs">Logo</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                        Selamat datang, {{ $perusahaan->nama_perusahaan }}!
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ $perusahaan->jenis_usaha }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <!-- Total Laporan Harian -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div
                    class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Laporan Harian
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $total_laporan }}
                    </p>
                </div>
            </div>

            


            <!-- Laporan Pending -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div
                    class="p-3 mr-4 text-yellow-500 bg-yellow-100 rounded-full dark:text-yellow-100 dark:bg-yellow-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Laporan Draft
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $laporan_pending }}
                    </p>
                </div>
            </div>

            <!-- Total Pengelolaan -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Pengelolaan
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $total_pengelolaan }}
                    </p>
                </div>
            </div>

            <!-- Laporan Hasil -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Laporan Hasil
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $total_laporan_hasil }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Stats for Laporan Hasil -->
        <div class="grid gap-6 mb-8 md:grid-cols-3">
            <!-- Average Efficiency -->
            <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Rata-rata Efisiensi
                        </p>
                        <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                            {{ number_format($avg_efisiensi, 1) }}%
                        </p>
                        <p class="text-xs text-gray-500">
                            @if($avg_efisiensi >= 80)
                                Sangat Baik
                            @elseif($avg_efisiensi >= 60)
                                Baik
                            @elseif($avg_efisiensi >= 40)
                                Cukup
                            @else
                                Perlu Perbaikan
                            @endif
                        </p>
                    </div>
                    <div class="relative w-16 h-16">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-300 dark:text-gray-700" stroke="currentColor" stroke-width="3"
                                fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path
                                class="text-{{ $avg_efisiensi >= 80 ? 'green' : ($avg_efisiensi >= 60 ? 'yellow' : 'red') }}-500"
                                stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"
                                stroke-dasharray="{{ $avg_efisiensi }}, 100"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Cost -->
            <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="flex items-center">
                    <div
                        class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                            </path>
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Total Biaya Pengelolaan
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            Rp {{ number_format($total_biaya, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="flex items-center">
                    <div
                        class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Tingkat Keberhasilan
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $total_laporan_hasil > 0 ? number_format(($laporan_berhasil / $total_laporan_hasil) * 100, 1) : 0 }}%
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $laporan_berhasil }} dari {{ $total_laporan_hasil }} berhasil
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Chart: Laporan Harian per Jenis Limbah -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h4 class="mb-4 font-semibold text-gray-700 dark:text-gray-300">
                    Laporan Harian per Jenis Limbah
                </h4>
                <canvas id="chart-laporan-harian-jenis-limbah" height="220"></canvas>
            </div>
            <!-- Status Distribution Chart (tetap tampil) -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h4 class="mb-4 font-semibold text-gray-700 dark:text-gray-300">
                    Distribusi Status Laporan Hasil
                </h4>
                @if($total_laporan_hasil > 0)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Berhasil</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100 mr-2">{{ $laporan_berhasil }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($laporan_berhasil / $total_laporan_hasil) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Sebagian Berhasil</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100 mr-2">{{ $laporan_partial }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($laporan_partial / $total_laporan_hasil) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Gagal</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100 mr-2">{{ $laporan_gagal }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ ($laporan_gagal / $total_laporan_hasil) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data laporan hasil</p>
                    </div>
                @endif
            </div>
        </div>

        

        <!-- Recent Activities -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Recent Laporan Harian -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                        Laporan Harian Terbaru
                    </h4>
                    <a href="{{ route('laporan-harian.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Lihat Semua
                    </a>
                </div>
                @if($recent_laporan_harian->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_laporan_harian as $laporan)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $laporan->jenisLimbah->nama }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $laporan->tanggal->format('d/m/Y') }} - {{ number_format($laporan->jumlah, 2) }}
                                        {{ $laporan->satuan }}
                                    </p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $laporan->status_badge_class }}-700 bg-{{ $laporan->status_badge_class }}-100 rounded-full">
                                    {{ $laporan->status_name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada laporan harian</p>
                    </div>
                @endif
            </div>

            <!-- Recent Pengelolaan -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                        Pengelolaan Terbaru
                    </h4>
                    <a href="{{ route('pengelolaan-limbah.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Lihat Semua
                    </a>
                </div>
                @if($recent_pengelolaan->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_pengelolaan as $pengelolaan)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="font-semibold">
                                        @if($pengelolaan->jenisLimbah)
                                            {{ $pengelolaan->jenisLimbah->nama }}
                                        @else
                                            <span class="text-gray-400">Data tidak tersedia</span>
                                        @endif
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $pengelolaan->tanggal_mulai->format('d/m/Y') }} -
                                        {{ number_format($pengelolaan->jumlah_dikelola, 2) }} {{ $pengelolaan->satuan }}
                                    </p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $pengelolaan->status_badge_class }}-700 bg-{{ $pengelolaan->status_badge_class }}-100 rounded-full">
                                    {{ $pengelolaan->status_name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada pengelolaan limbah</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Laporan Hasil -->
        @if($recent_laporan_hasil->count() > 0)
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                        Laporan Hasil Terbaru
                    </h4>
                    <a href="{{ route('laporan-hasil-pengelolaan.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Jenis Limbah</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Efisiensi</th>
                                <th class="px-4 py-3">Biaya</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($recent_laporan_hasil as $laporan)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            {{ $laporan->tanggal_selesai->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium">
                                            {{ $laporan->pengelolaanLimbah->jenisLimbah->nama }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ number_format($laporan->jumlah_berhasil_dikelola, 2) }} {{ $laporan->satuan }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $laporan->status_hasil_badge_class }}-700 bg-{{ $laporan->status_hasil_badge_class }}-100 rounded-full">
                                            {{ $laporan->status_hasil_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-{{ $laporan->efisiensi_pengelolaan >= 80 ? 'green' : ($laporan->efisiensi_pengelolaan >= 60 ? 'yellow' : 'red') }}-600 h-2 rounded-full"
                                                    style="width: {{ min($laporan->efisiensi_pengelolaan, 100) }}%"></div>
                                            </div>
                                            <span
                                                class="text-xs">{{ number_format($laporan->efisiensi_pengelolaan, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            @if($laporan->biaya_aktual)
                                                Rp {{ number_format($laporan->biaya_aktual, 0, ',', '.') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <a href="{{ route('laporan-hasil-pengelolaan.show', $laporan) }}"
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="mb-4 font-semibold text-gray-700 dark:text-gray-300">
                Aksi Cepat
            </h4>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('laporan-harian.create') }}"
                    class="flex items-center p-3 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Laporan Harian
                </a>
                <a href="{{ route('pengelolaan-limbah.create') }}"
                    class="flex items-center p-3 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Kelola Limbah
                </a>
                <a href="{{ route('laporan-hasil-pengelolaan.create') }}"
                    class="flex items-center p-3 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Laporan Hasil
                </a>
                <a href="{{ route('penyimpanan.create') }}"
                    class="flex items-center p-3 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Tambah Penyimpanan
                </a>
            </div>
        </div>
        

<!-- Chart.js & chart-laporan-harian.js -->
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data for chart: Laporan Harian per Jenis Limbah
        window.laporanHarianByJenisLimbahData = {
            labels: {!! json_encode($laporan_harian_by_jenis_limbah_labels ?? []) !!},
            data: {!! json_encode($laporan_harian_by_jenis_limbah_data ?? []) !!}
        };
    </script>
    <script src="{{ asset('js/chart-laporan-harian.js') }}"></script>
@endpush

        <!-- Alerts and Notifications -->
        @if(count($alerts) > 0)
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h4 class="mb-4 font-semibold text-gray-700 dark:text-gray-300">
                    Peringatan & Notifikasi
                </h4>
                <div class="space-y-3">
                    @foreach($alerts as $alert)
                        <div
                            class="flex items-start p-3 bg-{{ $alert['type'] }}-50 dark:bg-{{ $alert['type'] }}-900 rounded-lg border-l-4 border-{{ $alert['type'] }}-400">
                            <div class="flex-shrink-0">
                                @if($alert['type'] === 'yellow')
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($alert['type'] === 'red')
                                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h5
                                    class="text-sm font-medium text-{{ $alert['type'] }}-800 dark:text-{{ $alert['type'] }}-200">
                                    {{ $alert['title'] }}
                                </h5>
                                <p class="text-sm text-{{ $alert['type'] }}-700 dark:text-{{ $alert['type'] }}-300 mt-1">
                                    {{ $alert['message'] }}
                                </p>
                                @if(isset($alert['action']))
                                    <div class="mt-2">
                                        <a href="{{ $alert['action']['url'] }}"
                                            class="text-sm font-medium text-{{ $alert['type'] }}-800 dark:text-{{ $alert['type'] }}-200 hover:text-{{ $alert['type'] }}-900 dark:hover:text-{{ $alert['type'] }}-100">
                                            {{ $alert['action']['text'] }} →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer Info -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Data terakhir diperbarui: {{ now()->format('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</x-app>