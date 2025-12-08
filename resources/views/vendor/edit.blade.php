<x-app>
    <x-slot:title>
        Edit Vendor - {{ $vendor->nama_perusahaan }}
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Vendor
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('vendor.show', $vendor) }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    Detail
                </a>
                <a href="{{ route('vendor.index') }}"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('admin.vendor.update', $vendor) }}">
                @csrf
                @method('PUT')

                <!-- Nama Perusahaan -->
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nama Perusahaan <span
                            class="text-red-500">*</span></span>
                    <input type="text" name="nama_perusahaan"
                        value="{{ old('nama_perusahaan', $vendor->nama_perusahaan) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                    @error('nama_perusahaan')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Nama PIC -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nama PIC <span class="text-red-500">*</span></span>
                    <input type="text" name="nama_pic" value="{{ old('nama_pic', $vendor->nama_pic) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                    @error('nama_pic')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Email -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Email <span class="text-red-500">*</span></span>
                    <input type="email" name="email" value="{{ old('email', $vendor->email) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                    @error('email')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Telepon -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nomor Telepon <span
                            class="text-red-500">*</span></span>
                    <input type="text" name="telepon" value="{{ old('telepon', $vendor->telepon) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                    @error('telepon')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Alamat -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Alamat <span class="text-red-500">*</span></span>
                    <textarea name="alamat" rows="3"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        required>{{ old('alamat', $vendor->alamat) }}</textarea>
                    @error('alamat')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Jenis Layanan -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Jenis Layanan <span
                            class="text-red-500">*</span></span>
                    <select name="jenis_layanan"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        required>
                        <option value="">-- Pilih Jenis Layanan --</option>
                        @foreach(\App\Models\Vendor::getJenisLayananOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis_layanan', $vendor->jenis_layanan) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_layanan')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Deskripsi (Opsional) -->
                <label class="block mt-4 text-sm" for="deskripsi">
                    <span class="text-gray-700 dark:text-gray-400">Deskripsi (Opsional)</span>
                    <textarea name="deskripsi" id="deskripsi"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        rows="3">{{ old('deskripsi', $vendor->deskripsi) }}</textarea>
                </label>

                <!-- Status -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Status <span class="text-red-500">*</span></span>
                    <select name="status"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        required>
                        @foreach(\App\Models\Vendor::getStatusOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $vendor->status) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex items-center justify-end mt-6 space-x-2">
                    <a href="{{ route('vendor.show', $vendor) }}"
                        class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg active:bg-gray-100 hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray">
                        Batal
                    </a>
                    <x-button type="submit">
                        Perbarui Vendor
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app>