<x-app>
    <x-slot:title>Detail User</x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center my-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail User
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Edit
                </a>
                <a href="{{ route('users.password.edit', $user) }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-yellow-600 border border-transparent rounded-lg active:bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:shadow-outline-yellow">
                    Ubah Password
                </a>
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-300 border border-transparent rounded-lg active:bg-gray-300 hover:bg-gray-400 focus:outline-none focus:shadow-outline-gray">
                    Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Informasi User -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-medium text-gray-700 dark:text-gray-300">Informasi User</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                        <dd class="mt-1">
                            <span
                                class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                {{ $user->role_name }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            <span
                                class="px-2 py-1 text-xs font-semibold leading-tight {{ $user->status === 'active' ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' }} rounded-full">
                                {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->notes ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Informasi Sistem -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-medium text-gray-700 dark:text-gray-300">Informasi Sistem</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar Pada</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Login</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">IP Terakhir Login</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->last_login_ip ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Terverifikasi</dt>
                        <dd class="mt-1">
                            <span
                                class="px-2 py-1 text-xs font-semibold leading-tight {{ $user->email_verified_at ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' }} rounded-full">
                                {{ $user->email_verified_at ? 'Ya' : 'Belum' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        @if ($user->id !== auth()->id())
            <!-- Aksi Berbahaya -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Aksi Berbahaya</h3>
                    <div class="flex space-x-2">
                        <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-{{ $user->status === 'active' ? 'red' : 'green' }}-600 border border-transparent rounded-lg active:bg-{{ $user->status === 'active' ? 'red' : 'green' }}-600 hover:bg-{{ $user->status === 'active' ? 'red' : 'green' }}-700 focus:outline-none focus:shadow-outline-{{ $user->status === 'active' ? 'red' : 'green' }}">
                                {{ $user->status === 'active' ? 'Nonaktifkan User' : 'Aktifkan User' }}
                            </button>
                        </form>

                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                                Hapus User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app>