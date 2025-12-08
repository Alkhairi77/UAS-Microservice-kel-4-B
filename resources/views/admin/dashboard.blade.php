<x-app>
    <x-slot:title>
        Dashboard Admin
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <h1 class="mb-6 text-3xl font-bold text-gray-700 dark:text-gray-200">Dashboard Admin</h1>
        
        <!-- System Alerts -->
        @if(count($alerts) > 0)
            <div class="mb-6">
                @foreach($alerts as $alert)
                    <x-alert type="{{ $alert['type'] }}" class="mb-3" dismissible>
                        <div class="flex items-center justify-between">
                            <div>
                                <strong>{{ $alert['title'] }}:</strong> {{ $alert['message'] }}
                            </div>
                            <a href="{{ $alert['action'] }}" class="text-sm underline hover:no-underline">
                                Lihat Detail
                            </a>
                        </div>
                    </x-alert>
                @endforeach
            </div>
        @endif

        <!-- Main Statistics Cards -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <!-- Total Users Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalUsers) }}</p>
                    <p class="text-xs text-gray-500">{{ $activeUsers }} aktif, {{ $pendingUsers }} pending</p>
                </div>
            </div>

            <!-- Total Perusahaan Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Perusahaan</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalPerusahaan) }}</p>
                </div>
            </div>

            <!-- Total Laporan Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zM8 8a1 1 0 012 0v3a1 1 0 11-2 0V8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Laporan</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalLaporanHarian) }}</p>
                    <p class="text-xs text-gray-500">{{ $laporanHarianBulanIni }} bulan ini</p>
                </div>
            </div>

            <!-- Total Pengelolaan Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengelolaan</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalPengelolaan) }}</p>
                    <p class="text-xs text-gray-500">{{ $pengelolaanAktif }} sedang berjalan</p>
                </div>
            </div>
        </div>

        <!-- Secondary Statistics -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <!-- Vendors -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Vendor</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalVendors) }}</p>
                </div>
            </div>

            <!-- Jenis Limbah -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Jenis Limbah</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalJenisLimbah) }}</p>
                </div>
            </div>

            <!-- Penyimpanan -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Penyimpanan</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalPenyimpanan) }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($persentaseKapasitas, 1) }}% terpakai</p>
                </div>
            </div>

            <!-- Laporan Hasil -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-pink-500 bg-pink-100 rounded-full dark:text-pink-100 dark:bg-pink-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Laporan Hasil</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ number_format($totalLaporanHasil) }}</p>
                </div>
            </div>
        </div>

        

        <!-- Charts Section -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Monthly Trends Chart -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tren Bulanan</h3>
                </div>
                <div class="p-6">
                    <canvas id="monthlyTrendChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Distribusi Status Pengelolaan</h3>
                </div>
                <div class="p-6">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Efficiency and Business Type Charts -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Efficiency Chart -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Distribusi Efisiensi</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Efisiensi</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($efisiensiStats['rata_rata'], 1) }}%
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-600">Tinggi (≥80%)</span>
                                <span class="text-sm font-medium">{{ $efisiensiStats['tinggi'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalLaporanHasil > 0 ? ($efisiensiStats['tinggi'] / $totalLaporanHasil) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-yellow-600">Sedang (60-79%)</span>
                                <span class="text-sm font-medium">{{ $efisiensiStats['sedang'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalLaporanHasil > 0 ? ($efisiensiStats['sedang'] / $totalLaporanHasil) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-red-600">Rendah <(60%) </span>
                                <span class="text-sm font-medium">{{ $efisiensiStats['rendah'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalLaporanHasil > 0 ? ($efisiensiStats['rendah'] / $totalLaporanHasil) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Type Distribution -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Distribusi Jenis Usaha</h3>
                </div>
                <div class="p-6">
                    <canvas id="businessTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Lists Section -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Top Perusahaan -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Top 5 Perusahaan (Berdasarkan Laporan)</h3>
                </div>
                <div class="p-6">
                    @if($topPerusahaanByLaporan->count() > 0)
                        <div class="space-y-3">
                            @foreach($topPerusahaanByLaporan as $index => $perusahaan)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $perusahaan->nama_perusahaan }}</p>
                                            <p class="text-xs text-gray-500">{{ $perusahaan->jenis_usaha }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $perusahaan->total_laporan }}</p>
                                        <p class="text-xs text-gray-500">laporan</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada data</p>
                    @endif
                </div>
            </div>

            <!-- Top Jenis Limbah -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Top 5 Jenis Limbah</h3>
                </div>
                <div class="p-6">
                    @if($topJenisLimbah->count() > 0)
                        <div class="space-y-3">
                            @foreach($topJenisLimbah as $index => $jenis)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $jenis->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ $jenis->kode_limbah }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $jenis->total_laporan }}</p>
                                        <p class="text-xs text-gray-500">laporan</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="grid gap-6 mb-8 md:grid-cols-3">
            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">User Terbaru</h3>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentUsers as $user)
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $user->status === 'active' ? 'green' : 'yellow' }}-700 bg-{{ $user->status === 'active' ? 'green' : 'yellow' }}-100 rounded-full">
                                        {{ $user->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada user</p>
                    @endif
                </div>
            </div>

            <!-- Recent Perusahaan -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Perusahaan Terbaru</h3>
                        <a href="{{ route('admin.perusahaan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentPerusahaan->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentPerusahaan as $perusahaan)
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $perusahaan->nama_perusahaan }}</p>
                                        <p class="text-xs text-gray-500">{{ $perusahaan->jenis_usaha }}</p>
                                        <p class="text-xs text-gray-400">{{ $perusahaan->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada perusahaan</p>
                    @endif
                </div>
            </div>

            <!-- Recent Laporan -->
            <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Laporan Terbaru</h3>
                        <a href="{{ route('laporan-harian.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentLaporan->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentLaporan as $laporan)
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $laporan->jenisLimbah->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ $laporan->perusahaan->nama_perusahaan }}</p>
                                        <p class="text-xs text-gray-400">{{ $laporan->tanggal->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $laporan->status === 'submitted' ? 'green' : 'yellow' }}-700 bg-{{ $laporan->status === 'submitted' ? 'green' : 'yellow' }}-100 rounded-full">
                                        {{ $laporan->status_name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada laporan</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="xl:col-span-1 mb-8">
            @php
                $adminNotifications = auth()->user()->notifications()->latest()->take(5)->get();
            @endphp
            <x-notification-widget :notifications="$adminNotifications" />
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Aksi Cepat</h3>
            </div>
            <div class="p-6">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Tambah User</p>
                            <p class="text-sm text-gray-500">Buat user baru</p>
                        </div>
                    </a>

                    <a href="{{ route('jenis-limbah.create') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <div class="p-2 bg-green-500 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Tambah Jenis Limbah</p>
                            <p class="text-sm text-gray-500">Definisi limbah baru</p>
                        </div>
                    </a>

                                       <a href="{{ route('admin.vendor.create') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Tambah Vendor</p>
                            <p class="text-sm text-gray-500">Daftarkan vendor baru</p>
                        </div>
                    </a>

                    <a href="{{ route('kategori-artikel.create') }}" class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                        <div class="p-2 bg-orange-500 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Tambah Kategori</p>
                            <p class="text-sm text-gray-500">Kategori artikel baru</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Trend Chart
            const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
            if (monthlyTrendCtx) {
                new Chart(monthlyTrendCtx, {
                    type: 'line',
                    data: {
                        labels: [
                            @foreach($monthlyLaporanTrend as $trend)
                                '{{ Carbon\Carbon::createFromFormat("Y-m", $trend->month)->format("M Y") }}',
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Laporan Harian',
                            data: [
                                @foreach($monthlyLaporanTrend as $trend)
                                    {{ $trend->total }},
                                @endforeach
                            ],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Pengelolaan',
                            data: [
                                @foreach($monthlyPengelolaanTrend as $trend)
                                    {{ $trend->total }},
                                @endforeach
                            ],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'User Baru',
                            data: [
                                @foreach($monthlyUserTrend as $trend)
                                    {{ $trend->total }},
                                @endforeach
                            ],
                            borderColor: 'rgb(245, 158, 11)',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Tren Aktivitas 6 Bulan Terakhir'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }

            // Status Distribution Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            @foreach($statusPengelolaanStats as $status)
                                '{{ ucfirst(str_replace("_", " ", $status->status)) }}',
                            @endforeach
                        ],
                        datasets: [{
                            data: [
                                @foreach($statusPengelolaanStats as $status)
                                    {{ $status->total }},
                                @endforeach
                            ],
                            backgroundColor: [
                                '#3B82F6', // blue - diproses
                                '#10B981', // green - selesai
                                '#F59E0B', // yellow - dalam_pengangkutan
                                '#EF4444', // red - dibatalkan
                                '#8B5CF6', // purple - pending
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Business Type Chart
            const businessTypeCtx = document.getElementById('businessTypeChart');
            if (businessTypeCtx) {
                new Chart(businessTypeCtx, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach($jenisUsahaStats as $jenis)
                                '{{ ucfirst($jenis->jenis_usaha) }}',
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Jumlah Perusahaan',
                            data: [
                                @foreach($jenisUsahaStats as $jenis)
                                    {{ $jenis->total }},
                                @endforeach
                            ],
                            backgroundColor: [
                                '#3B82F6',
                                '#10B981',
                                '#F59E0B',
                                '#EF4444',
                                '#8B5CF6',
                                '#EC4899',
                                '#14B8A6',
                                '#F97316'
                            ],
                            borderRadius: 4,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return 'Jenis Usaha: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return 'Jumlah: ' + context.parsed.y + ' perusahaan';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            }
        });

        // Auto refresh data setiap 5 menit
        setInterval(function() {
            // Refresh halaman untuk update data terbaru
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 300000); // 5 menit

        // Smooth scroll untuk link internal
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        /* Chart containers */
        #monthlyTrendChart,
        #statusChart,
        #businessTypeChart {
            max-height: 300px;
        }
        
        /* Card hover effects */
        .bg-white:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out;
        }
        
        /* Quick action cards */
        .bg-blue-50:hover,
        .bg-green-50:hover,
        .bg-purple-50:hover,
        .bg-orange-50:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Progress bars animation */
        .bg-green-500,
        .bg-yellow-500,
        .bg-red-500 {
            transition: width 0.8s ease-in-out;
        }
        
        /* Alert animations */
        .alert-enter {
            animation: slideInDown 0.3s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .grid.gap-6.mb-8.md\\:grid-cols-2.xl\\:grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .grid.gap-6.mb-8.md\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .text-3xl {
                font-size: 1.875rem;
            }
        }
        
        /* Dark mode improvements */
        .dark .bg-white {
            background-color: rgb(31 41 55);
        }
        
        .dark .shadow-xs {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }
        
        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Smooth transitions */
        * {
            transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }
    </style>
</x-app>
