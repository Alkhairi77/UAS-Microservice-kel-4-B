<x-app>
    <x-slot:title>
        Detail Vendor - {{ $vendor->nama_perusahaan }}
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Detail Vendor
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.vendor.edit', $vendor) }}"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Edit
                    </a>
                @endif
                <a href="{{ route('vendor.index') }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Informasi Vendor -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Informasi Vendor
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Perusahaan</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $vendor->nama_perusahaan }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama PIC</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $vendor->nama_pic }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            <a href="mailto:{{ $vendor->email }}" class="text-purple-600 hover:text-purple-800">
                                {{ $vendor->email }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            <a href="tel:{{ $vendor->telepon }}" class="text-purple-600 hover:text-purple-800">
                                {{ $vendor->telepon }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $vendor->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Layanan -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Informasi Layanan
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Layanan</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            <span
                                class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                {{ $vendor->jenis_layanan_name }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            <span
                                class="px-2 py-1 text-xs font-semibold leading-tight text-{{ $vendor->status_badge_class }}-700 bg-{{ $vendor->status_badge_class }}-100 rounded-full dark:bg-{{ $vendor->status_badge_class }}-700 dark:text-{{ $vendor->status_badge_class }}-100">
                                {{ $vendor->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar Pada</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $vendor->created_at->format('d F Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</label>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $vendor->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
            <!-- Aksi Admin -->
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Aksi Admin
                </h3>

                <div class="flex space-x-4">
                    <!-- Toggle Status -->
                    <form action="{{ route('admin.vendor.toggle-status', $vendor) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-{{ $vendor->status === 'aktif' ? 'yellow' : 'green' }}-600 border border-transparent rounded-lg active:bg-{{ $vendor->status === 'aktif' ? 'yellow' : 'green' }}-600 hover:bg-{{ $vendor->status === 'aktif' ? 'yellow' : 'green' }}-700 focus:outline-none focus:shadow-outline-{{ $vendor->status === 'aktif' ? 'yellow' : 'green' }}">
                            {{ $vendor->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }} Vendor
                        </button>
                    </form>

                    <!-- Hapus Vendor -->
                    <form action="{{ route('admin.vendor.destroy', $vendor) }}" method="POST" class="inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                            Hapus Vendor
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app>