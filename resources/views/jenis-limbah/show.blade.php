<x-app>
    <x-slot:title>
        Detail Jenis Limbah - {{ $jenisLimbah->nama }}
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Jenis Limbah
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('jenis-limbah.edit', $jenisLimbah) }}" class="btn-green">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('jenis-limbah.index') }}" class="btn-gray">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Dasar -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Dasar</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Limbah</dt>
                        <dd class="mt-1 text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">
                            {{ $jenisLimbah->kode_limbah }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $jenisLimbah->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($jenisLimbah->kategori == 'hazardous') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                @elseif($jenisLimbah->kategori == 'recyclable') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                @elseif($jenisLimbah->kategori == 'organic') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                                @endif">
                                {{ $jenisLimbah->kategori_name }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Satuan Default</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $jenisLimbah->satuan_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat Bahaya</dt>
                        <dd class="mt-1">
                            @if($jenisLimbah->tingkat_bahaya)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($jenisLimbah->tingkat_bahaya == 'sangat_tinggi') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                        @elseif($jenisLimbah->tingkat_bahaya == 'tinggi') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-100
                                        @elseif($jenisLimbah->tingkat_bahaya == 'sedang') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                        @endif">
                                    {{ $jenisLimbah->tingkat_bahaya_name }}
                                </span>
                            @else
                                <span class="text-gray-400">Tidak ditentukan</span>
                            @endif
                        </dd>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $jenisLimbah->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' }}">
                                    {{ $jenisLimbah->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            <!-- Metode Pengelolaan & Deskripsi -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Detail Tambahan</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pengelolaan Rekomendasi
                        </dt>
                        <dd class="mt-1">
                            @if($jenisLimbah->metode_pengelolaan_rekomendasi && count($jenisLimbah->metode_pengelolaan_rekomendasi) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($jenisLimbah->metode_pengelolaan_rekomendasi as $metode)
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100 rounded-full">
                                            {{ \App\Models\JenisLimbah::METODE_PENGELOLAAN[$metode] ?? $metode }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">Tidak ada rekomendasi</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $jenisLimbah->deskripsi ?: 'Tidak ada deskripsi' }}
                        </dd>
                    </div>
                </dl>
            </x-card>
        </div>

        @if(auth()->user()->isAdmin())
            <!-- Statistik Penggunaan - Hanya untuk Admin -->
            <x-card class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Statistik Penggunaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $jenisLimbah->laporanHarian()->count() }}
                        </div>
                        <div class="text-sm text-blue-600 dark:text-blue-400">Total Laporan Harian</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $jenisLimbah->penyimpanans()->count() }}
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400">Lokasi Penyimpanan</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ $jenisLimbah->laporanHarian()->sum('jumlah') ?? 0 }} {{ $jenisLimbah->satuan_default }}
                        </div>
                        <div class="text-sm text-yellow-600 dark:text-yellow-400">Total Volume Dilaporkan</div>
                    </div>
                </div>
            </x-card>

            <!-- Informasi Sistem - Hanya untuk Admin -->
            <x-card class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Sistem</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $jenisLimbah->created_at->format('d F Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $jenisLimbah->updated_at->format('d F Y H:i') }}</dd>
                    </div>
                </dl>
            </x-card>
        @endif
    </div>
</x-app>