<x-app>
    <x-slot:title>
        Edit Jenis Limbah - {{ $jenisLimbah->nama }}
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Jenis Limbah
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('jenis-limbah.show', $jenisLimbah) }}" class="btn-blue">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 5.943 7.523 3 12 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S3.732 18.057 2.458 12z">
                        </path>
                    </svg>
                    Detail
                </a>
                <a href="{{ route('jenis-limbah.index') }}" class="btn-gray">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" class="mb-6">{{ session('error') }}</x-alert>
    @endif

        <x-card>
            <form method="POST" action="{{ route('jenis-limbah.update', $jenisLimbah) }}" class="space-y-6">
        @csrf
        @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Jenis Limbah -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Jenis Limbah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $jenisLimbah->nama) }}"
                            class="form-input-custom @error('nama') border-red-500 @enderror"
                            placeholder="Masukkan nama jenis limbah" required />
                        @error('nama')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Limbah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Limbah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_limbah"
                            value="{{ old('kode_limbah', $jenisLimbah->kode_limbah) }}"
                            class="form-input-custom @error('kode_limbah') border-red-500 @enderror"
                            placeholder="Masukkan kode limbah" required />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Kode unik untuk identifikasi jenis limbah
                        </p>
                        @error('kode_limbah')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" class="form-select-custom @error('kategori') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\JenisLimbah::getKategoriOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori', $jenisLimbah->kategori) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan Default -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Satuan Default <span class="text-red-500">*</span>
                        </label>
                        <select name="satuan_default"
                            class="form-select-custom @error('satuan_default') border-red-500 @enderror" required>
                            <option value="">Pilih Satuan</option>
                            @foreach(\App\Models\JenisLimbah::getSatuanOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('satuan_default', $jenisLimbah->satuan_default) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan_default')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tingkat Bahaya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tingkat Bahaya
                        </label>
                        <select name="tingkat_bahaya"
                            class="form-select-custom @error('tingkat_bahaya') border-red-500 @enderror">
                            <option value="">Pilih Tingkat Bahaya</option>
                            @foreach(\App\Models\JenisLimbah::getTingkatBahayaOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('tingkat_bahaya', $jenisLimbah->tingkat_bahaya) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('tingkat_bahaya')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" class="form-select-custom @error('status') border-red-500 @enderror"
                            required>
                            <option value="active" {{ old('status', $jenisLimbah->status) == 'active' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="inactive" {{ old('status', $jenisLimbah->status) == 'inactive' ? 'selected' : '' }}>
                                Nonaktif
                            </option>
                        </select>
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
                                        {{ in_array($key, old('metode_pengelolaan_rekomendasi', $jenisLimbah->metode_pengelolaan_rekomendasi ?? [])) ? 'checked' : '' }}>
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
                        <textarea name="deskripsi" rows="4"
                            class="form-textarea-custom @error('deskripsi') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi jenis limbah">{{ old('deskripsi', $jenisLimbah->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
        </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p>Dibuat: {{ $jenisLimbah->created_at->format('d/m/Y H:i') }}</p>
                        <p>Diperbarui: {{ $jenisLimbah->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('jenis-limbah.index') }}" class="btn-gray">
                            Batal
                        </a>
                        <button type="submit" class="btn-green">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </x-card>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Usage Statistics -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Statistik Penggunaan
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Laporan:</span>
                        <span class="text-sm font-medium">{{ $jenisLimbah->laporanHarian()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Penyimpanan:</span>
                        <span class="text-sm font-medium">{{ $jenisLimbah->penyimpanans()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="badge-{{ $jenisLimbah->status_badge_class }}">
                            {{ $jenisLimbah->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </x-card>

            <!-- Current Information -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Saat Ini
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Kode:</span>
                        <span class="text-sm font-medium font-mono">{{ $jenisLimbah->kode_limbah }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Kategori:</span>
                        <span class="badge-{{ $jenisLimbah->kategori_badge_class }}">
                            {{ $jenisLimbah->kategori_name }}
                        </span>
                    </div>
                    @if($jenisLimbah->tingkat_bahaya)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tingkat Bahaya:</span>
                            <span class="badge-{{ $jenisLimbah->tingkat_bahaya_badge_class }}">
                                {{ $jenisLimbah->tingkat_bahaya_name }}
                            </span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Satuan:</span>
                        <span class="text-sm font-medium">{{ $jenisLimbah->satuan_name }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Recommended Methods -->
        @if($jenisLimbah->metode_pengelolaan_rekomendasi && count($jenisLimbah->metode_pengelolaan_rekomendasi) > 0)
            <x-card class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Metode Pengelolaan Saat Ini
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($jenisLimbah->metode_pengelolaan_names as $metode)
                        <span class="badge-blue">{{ $metode }}</span>
                    @endforeach
                </div>
            </x-card>
        @endif

        <!-- Warning for Dangerous Waste -->
        @if($jenisLimbah->requiresSpecialHandling())
            <x-card class="mt-6 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Peringatan Limbah Berbahaya
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <p>Jenis limbah ini memerlukan penanganan khusus karena tingkat bahayanya yang tinggi. Pastikan
                                semua prosedur keamanan diikuti dengan ketat.</p>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif

        <!-- Help Section -->
        <x-card class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Panduan Edit
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Perhatian Saat Edit:</h4>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Perubahan kode limbah akan mempengaruhi referensi di laporan</li>
                        <li>Mengubah kategori dapat mempengaruhi metode pengelolaan</li>
                        <li>Status nonaktif akan menyembunyikan dari pilihan baru</li>
                        <li>Satuan default akan digunakan untuk laporan baru</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Tips:</h4>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Gunakan deskripsi yang jelas dan informatif</li>
                        <li>Pilih metode pengelolaan yang sesuai dengan regulasi</li>
                        <li>Tingkat bahaya membantu dalam prioritas penanganan</li>
                        <li>Backup data sebelum melakukan perubahan besar</li>
                    </ul>
                </div>
            </div>
        </x-card>
</div>
</x-app>