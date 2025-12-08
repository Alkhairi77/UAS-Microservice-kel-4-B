<x-app>
    <x-slot:title>
        Daftar Vendor
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Daftar Vendor
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.vendor.create') }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    Tambah Vendor
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter dan Search -->
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="GET" action="{{ route('vendor.index') }}" class="grid gap-4 md:grid-cols-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Cari Vendor</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Nama perusahaan, PIC, email..." />
                    </label>
                </div>

                <!-- Filter Jenis Layanan -->
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Jenis Layanan</span>
                        <select name="jenis_layanan"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-gray">
                            <option value="">Semua Layanan</option>
                            @foreach(\App\Models\Vendor::getJenisLayananOptions() as $key => $label)
                                <option value="{{ $key }}" {{ request('jenis_layanan') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Status</span>
                        <select name="status"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-gray">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak
                                Aktif</option>
                        </select>
                    </label>
                </div>

                <!-- Tombol Filter -->
                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Filter
                    </button>
                    <a href="{{ route('vendor.index') }}"
                        class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg active:bg-gray-100 hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Vendor -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                @if($vendors->count())
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Nama Perusahaan</th>
                                <th class="px-4 py-3">Nama PIC</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Telepon</th>
                                <th class="px-4 py-3">Jenis Layanan</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($vendors as $vendor)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $vendor->nama_perusahaan }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $vendor->nama_pic }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="mailto:{{ $vendor->email }}" class="text-green-600 hover:text-green-800">
                                            {{ $vendor->email }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="tel:{{ $vendor->telepon }}" class="text-green-600 hover:text-green-800">
                                            {{ $vendor->telepon }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                            {{ $vendor->jenis_layanan_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-{{ $vendor->status_badge_class }}-700 bg-{{ $vendor->status_badge_class }}-100 rounded-full dark:bg-{{ $vendor->status_badge_class }}-700 dark:text-{{ $vendor->status_badge_class }}-100">
                                            {{ $vendor->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center space-x-4 text-sm">
                                            <!-- Detail -->
                                            <a href="{{ route('vendor.show', $vendor->id) }}"
                                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-green">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>

                                            @if(auth()->user()->isAdmin())
                                                <!-- Edit -->
                                                <a href="{{ route('admin.vendor.edit', $vendor->id) }}"
                                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-green">
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                <!-- Hapus -->
                                                <form action="{{ route('admin.vendor.destroy', $vendor->id) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-red">
                                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-4 py-8 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada data vendor</p>
                            <p class="text-sm">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.vendor.create') }}" class="text-green-600 hover:text-green-800">
                                        Tambah vendor pertama
                                    </a>
                                @else
                                    Hubungi admin untuk menambahkan vendor
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            @if($vendors->hasPages())
                <div
                    class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                    <span class="flex items-center col-span-3">
                        Showing {{ $vendors->firstItem() ?? 0 }}-{{ $vendors->lastItem() ?? 0 }} of {{ $vendors->total() }}
                    </span>
                    <span class="col-span-2"></span>
                    <!-- Pagination -->
                    <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                        {{ $vendors->links() }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</x-app>