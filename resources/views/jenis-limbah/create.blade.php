<x-app>
    <x-slot:title>
        Tambah Jenis Limbah
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Jenis Limbah Baru
            </h2>
            <a href="{{ route('jenis-limbah.index') }}" class="btn-gray">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        @if(session('error'))
            <x-alert type="error" class="mb-6">{{ session('error') }}</x-alert>
        @endif

        <x-card>
            <form method="POST" action="{{ route('jenis-limbah.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Jenis Limbah -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Jenis Limbah <span class="text-red-500">*</span>
                        </label>
                        <x-input type="text" name="nama" value="{{ old('nama') }}" class="@error('nama') @enderror"
                            placeholder="Masukkan nama jenis limbah" required />
                        @error('nama')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Limbah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Limbah
                        </label>
                        <x-input type="text" name="kode_limbah" value="{{ old('kode_limbah') }}"
                            class="@error('kode_limbah') @enderror" placeholder="Otomatis jika kosong" />
                       
                        @error('kode_limbah')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <x-select name="kategori" class=" @error('kategori') @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\JenisLimbah::getKategoriOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-select>
                        @error('kategori')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan Default -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Satuan Default <span class="text-red-500">*</span>
                        </label>
                        <x-select name="satuan_default" class="@error('satuan_default') @enderror" required>
                            <option value="">Pilih Satuan</option>
                            @foreach(\App\Models\JenisLimbah::getSatuanOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('satuan_default', 'kg') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-select>
                        @error('satuan_default')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tingkat Bahaya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tingkat Bahaya
                        </label>
                        <x-select name="tingkat_bahaya" class=" @error('tingkat_bahaya') @enderror">
                            <option value="">Pilih Tingkat Bahaya</option>
                            @foreach(\App\Models\JenisLimbah::getTingkatBahayaOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('tingkat_bahaya') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-select>
                        @error('tingkat_bahaya')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <x-select name="status" class=" @error('status') @enderror" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Nonaktif
                            </option>
                        </x-select>
                        @error('status')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metode Pengelolaan Rekomendasi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Metode Pengelolaan Rekomendasi
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            @foreach(\App\Models\JenisLimbah::getMetodePengelolaanOptions() as $key => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" name="metode_pengelolaan_rekomendasi[]" value="{{ $key }}"
                                        class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                        {{ in_array($key, old('metode_pengelolaan_rekomendasi', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('metode_pengelolaan_rekomendasi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Deskripsi
                        </label>
                        <x-textarea name="deskripsi" rows="4" class="@error('deskripsi') @enderror"
                            placeholder="Masukkan deskripsi jenis limbah">{{ old('deskripsi') }}</x-textarea>
                        @error('deskripsi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('jenis-limbah.index') }}" class="btn-gray">
                        Batal
                    </a>
                    <button type="submit" class="btn-green">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </x-card>

        <!-- Help Section -->
        <x-card class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Panduan Pengisian
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Kategori Limbah:</h4>
                    <ul class="space-y-1">
                        <li><strong>Limbah Berbahaya (B3):</strong> Limbah yang mengandung bahan berbahaya dan beracun
                        </li>
                        <li><strong>Limbah Non-B3:</strong> Limbah umum yang tidak berbahaya</li>
                        <li><strong>Limbah Daur Ulang:</strong> Limbah yang dapat didaur ulang</li>
                        <li><strong>Limbah Organik:</strong> Limbah yang dapat terurai secara alami</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Tingkat Bahaya:</h4>
                    <ul class="space-y-1">
                        <li><strong>Rendah:</strong> Tidak berbahaya bagi manusia dan lingkungan</li>
                        <li><strong>Sedang:</strong> Berpotensi berbahaya jika tidak ditangani dengan benar</li>
                        <li><strong>Tinggi:</strong> Berbahaya dan memerlukan penanganan khusus</li>
                        <li><strong>Sangat Tinggi:</strong> Sangat berbahaya dan memerlukan protokol keamanan ketat</li>
                    </ul>
                </div>
            </div>
        </x-card>
    </div>
</x-app>