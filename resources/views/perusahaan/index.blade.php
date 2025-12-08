<x-app>
    <x-slot:title>
        Daftar Perusahaan
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Daftar Perusahaan
                <span class="text-sm font-normal text-gray-500">({{ $perusahaans->total() }} perusahaan)</span>
            </h2>
        </div>

        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        <!-- Filter dan Search -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.perusahaan.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <x-label>Cari Perusahaan</x-label>
                        <x-input type="text" name="search" value="{{ request('search') }}" 
                                 placeholder="Nama, email, telepon, registrasi..." />
                    </div>
                    
                    <div>
                        <x-label>Jenis Usaha</x-label>
                        <x-select name="jenis_usaha" :options="$jenisUsahaOptions" 
                                  value="{{ request('jenis_usaha') }}" placeholder="Semua Jenis Usaha" />
                    </div>
                    
                    <div>
                        <x-label>Status User</x-label>
                        <x-select name="status_user" :options="$statusUserOptions" 
                                  value="{{ request('status_user') }}" placeholder="Semua Status" />
                    </div>
                    
                    <div>
                        <x-label>Tanggal Daftar Dari</x-label>
                        <x-input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" />
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-label>Tanggal Daftar Sampai</x-label>
                        <x-input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" />
                    </div>
                    
                    <div class="flex items-end space-x-2 md:col-span-3">
                        <x-button type="submit" variant="secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </x-button>
                        
                        @if(request()->hasAny(['search', 'jenis_usaha', 'status_user', 'tanggal_dari', 'tanggal_sampai']))
                            <x-button variant="outline" href="{{ route('perusahaan.index') }}">
                                Reset
                            </x-button>
                        @endif
                    </div>
                </div>
            </form>
        </x-card>

        <!-- Tabel Perusahaan -->
        <x-card>
            @if($perusahaans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Perusahaan</th>
                                <th class="px-4 py-3">Kontak</th>
                                <th class="px-4 py-3">Jenis Usaha</th>
                                <th class="px-4 py-3">User Account</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Terdaftar</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($perusahaans as $perusahaan)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            @if($perusahaan->logo)
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full" 
                                                         src="{{ Storage::url($perusahaan->logo) }}" 
                                                         alt="{{ $perusahaan->nama_perusahaan }}" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                                </div>
                                            @else
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block bg-gray-200 dark:bg-gray-600">
                                                    <div class="flex items-center justify-center w-full h-full text-xs font-medium text-gray-500 dark:text-gray-400">
                                                        {{ substr($perusahaan->nama_perusahaan, 0, 2) }}
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold">{{ $perusahaan->nama_perusahaan }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $perusahaan->no_registrasi }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div>
                                            <p class="font-medium">{{ $perusahaan->email }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $perusahaan->no_telp }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                            {{ ucfirst($perusahaan->jenis_usaha) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div>
                                            <p class="font-medium">{{ $perusahaan->user->name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $perusahaan->user->email }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($perusahaan->user->status === 'active')
                                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div>
                                            <p class="font-medium">{{ $perusahaan->created_at->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $perusahaan->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-4 text-sm">
                                            <a href="{{ route('admin.perusahaan.show', $perusahaan) }}"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                               title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            
                                            <a href="{{ route('admin.users.show', $perusahaan->user) }}"
                                               class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                               title="Kelola User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                    {{ $perusahaans->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Belum ada perusahaan terdaftar</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        @if(request()->hasAny(['search', 'jenis_usaha', 'status_user', 'tanggal_dari', 'tanggal_sampai']))
                            Tidak ada perusahaan yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada perusahaan yang mendaftar di sistem.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'jenis_usaha', 'status_user', 'tanggal_dari', 'tanggal_sampai']))
                        <x-button variant="outline" href="{{ route('perusahaan.index') }}">
                            Reset Filter
                        </x-button>
                    @endif
                </div>
            @endif
        </x-card>

        <!-- Info Box untuk Admin -->
        <x-card class="mt-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        Informasi untuk Admin
                    </h3>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <p>Halaman ini menampilkan semua perusahaan yang terdaftar di sistem. Anda dapat:</p>
                        <ul class="mt-1 list-disc list-inside">
                            <li>Melihat detail profil perusahaan</li>
                            <li>Mengelola akun user perusahaan</li>
                            <li>Mengekspor data perusahaan ke CSV</li>
                            <li>Memfilter berdasarkan berbagai kriteria</li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-app>
