<x-app>
    <x-slot:title>
        Detail Perusahaan - {{ $perusahaan->nama_perusahaan }}
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Perusahaan
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->isAdmin())
                    <x-button variant="secondary" href="{{ route('admin.perusahaan.index') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </x-button>
                @endif
                
                @if(auth()->user()->isPerusahaan() && $perusahaan->user_id === auth()->user()->id)
                    <x-button href="{{ route('perusahaan.edit', $perusahaan) }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profil
                    </x-button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif

        @if(session('info'))
            <x-alert type="info" dismissible>{{ session('info') }}</x-alert>
        @endif

        <div class="grid gap-6 mb-8 md:grid-cols-3">
            <!-- Informasi Utama -->
            <div class="md:col-span-2">
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Informasi Perusahaan
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label>Nama Perusahaan</x-label>
                                <div class="text-gray-900 dark:text-gray-100 font-semibold">
                                    {{ $perusahaan->nama_perusahaan }}
                                </div>
                            </div>
                            
                            <div>
                                <x-label>Nomor Registrasi</x-label>
                                <div class="text-gray-700 dark:text-gray-200 font-mono">
                                    {{ $perusahaan->no_registrasi }}
                                </div>
                            </div>
                            
                            <div>
                                <x-label>Jenis Usaha</x-label>
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                    {{ ucfirst($perusahaan->jenis_usaha) }}
                                </span>
                            </div>
                            
                            <div>
                                <x-label>Email</x-label>
                                <div class="text-gray-700 dark:text-gray-200">
                                    <a href="mailto:{{ $perusahaan->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $perusahaan->email }}
                                    </a>
                                </div>
                            </div>
                            
                            <div>
                                <x-label>Telepon</x-label>
                                <div class="text-gray-700 dark:text-gray-200">
                                    <a href="tel:{{ $perusahaan->no_telp }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $perusahaan->no_telp }}
                                    </a>
                                </div>
                            </div>
                            
                            <div>
                                <x-label>Terdaftar Pada</x-label>
                                <div class="text-gray-700 dark:text-gray-200">
                                    {{ $perusahaan->created_at->format('d F Y H:i') }}
                                    <span class="text-xs text-gray-500">({{ $perusahaan->created_at->diffForHumans() }})</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <x-label>Alamat</x-label>
                            <div class="text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                {{ $perusahaan->alamat }}
                            </div>
                        </div>
                        
                        @if($perusahaan->deskripsi)
                            <div class="mt-6">
                                <x-label>Deskripsi</x-label>
                                <div class="text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                    {{ $perusahaan->deskripsi }}
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-6">
                            <x-label>Terakhir Diperbarui</x-label>
                            <div class="text-gray-700 dark:text-gray-200">
                                {{ $perusahaan->updated_at->format('d F Y H:i') }}
                                <span class="text-xs text-gray-500">({{ $perusahaan->updated_at->diffForHumans() }})</span>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Informasi User Account (hanya untuk admin) -->
                @if(auth()->user()->isAdmin())
                    <x-card class="mt-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Informasi Akun User
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-label>Nama User</x-label>
                                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $perusahaan->user->name }}
                                    </div>
                                </div>
                                
                                <div>
                                    <x-label>Email User</x-label>
                                    <div class="text-gray-700 dark:text-gray-200">
                                        <a href="mailto:{{ $perusahaan->user->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $perusahaan->user->email }}
                                        </a>
                                    </div>
                                </div>
                                
                                <div>
                                    <x-label>Role</x-label>
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full dark:bg-purple-700 dark:text-purple-100">
                                        {{ ucfirst($perusahaan->user->role) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <x-label>Status Akun</x-label>
                                    @if($perusahaan->user->status === 'active')
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                                
                                <div>
                                    <x-label>Email Verified</x-label>
                                    @if($perusahaan->user->email_verified_at)
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Terverifikasi
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $perusahaan->user->email_verified_at->format('d/m/Y H:i') }}
                                        </div>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                            Belum Verifikasi
                                        </span>
                                    @endif
                                </div>
                                
                                <div>
                                    <x-label>Terakhir Login</x-label>
                                    <div class="text-gray-700 dark:text-gray-200">
                                        @if($perusahaan->user->last_login_at)
                                            {{ $perusahaan->user->last_login_at->format('d F Y H:i') }}
                                            <div class="text-xs text-gray-500">
                                                {{ $perusahaan->user->last_login_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">Belum pernah login</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex space-x-3">
                                    <x-button variant="secondary" href="{{ route('admin.users.show', $perusahaan->user) }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Kelola User
                                    </x-button>
                                    
                                    <x-button variant="outline" href="{{ route('admin.users.edit', $perusahaan->user) }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit User
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Logo Perusahaan -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Logo Perusahaan
                        </h3>
                    </div>
                    
                    <div class="p-6 text-center">
                        @if($perusahaan->logo)
                            <img src="{{ Storage::url($perusahaan->logo) }}" 
                                 alt="Logo {{ $perusahaan->nama_perusahaan }}"
                                 class="w-32 h-32 object-cover rounded-lg mx-auto shadow-md">
                        @else
                            <div class="w-32 h-32 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mx-auto">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Tidak ada logo</p>
                        @endif
                    </div>
                </x-card>

                <!-- Statistik (jika ada data) -->
                @if(!empty($statistics))
                    <x-card>
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Statistik Aktivitas
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Laporan Harian</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($statistics['total_laporan_harian']) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pengelolaan Limbah</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($statistics['total_pengelolaan']) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Laporan Hasil</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($statistics['total_laporan_hasil']) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Penyimpanan</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format($statistics['total_penyimpanan']) }}
                                </span>
                            </div>
                            
                            @if($statistics['laporan_draft'] > 0)
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-yellow-600 dark:text-yellow-400">Draft Laporan</span>
                                        <span class="font-semibold text-yellow-600 dark:text-yellow-400">
                                            {{ number_format($statistics['laporan_draft']) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($statistics['pengelolaan_aktif'] > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-blue-600 dark:text-blue-400">Pengelolaan Aktif</span>
                                    <span class="font-semibold text-blue-600 dark:text-blue-400">
                                        {{ number_format($statistics['pengelolaan_aktif']) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </x-card>
                @endif

                <!-- Quick Actions -->
                @if(auth()->user()->isAdmin())
                    <x-card>
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Aksi Cepat
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-3">
                            <a href="{{ route('laporan-harian.index', ['perusahaan_id' => $perusahaan->id]) }}"
                               class="block w-full text-center px-3 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                Lihat Laporan Harian
                            </a>
                            
                            <a href="{{ route('laporan-hasil-pengelolaan.index', ['perusahaan_id' => $perusahaan->id]) }}"
                               class="block w-full text-center px-3 py-2 text-sm text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                Lihat Laporan Hasil
                            </a>
                            
                            <a href="{{ route('admin.users.show', $perusahaan->user) }}"
                               class="block w-full text-center px-3 py-2 text-sm text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                Detail User Account
                            </a>
                        </div>
                    </x-card>
                @endif
            </div>
        </div>
    </div>
</x-app>
