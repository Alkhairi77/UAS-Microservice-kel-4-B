<x-app>
    <x-slot:title>Edit Jenis Sampah</x-slot:title>

    <div class="container px-6 mx-auto">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Jenis Sampah
            </h2>
            <a href="{{ route('admin.jenis-sampah.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <x-card>
            <form method="POST" action="{{ route('admin.jenis-sampah.update', $item->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <x-input type="text" name="nama" value="{{ old('nama', $item->nama) }}" placeholder="Masukkan nama jenis sampah" required />
                    @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <x-select name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(\App\Models\JenisSampah::getKategoriOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('kategori', $item->kategori) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('kategori')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga per KG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Harga per KG <span class="text-red-500">*</span>
                    </label>
                    <x-input type="number" name="harga_per_kg" value="{{ old('harga_per_kg', $item->harga_per_kg) }}" step="0.01" placeholder="Masukkan harga per KG" required />
                    @error('harga_per_kg')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Deskripsi
                    </label>
                    <x-textarea name="deskripsi" rows="4" placeholder="Masukkan deskripsi">{{ old('deskripsi', $item->deskripsi) }}</x-textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <x-select name="status" required>
                        <option value="aktif" {{ old('status', $item->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="non-aktif" {{ old('status', $item->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </x-select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.jenis-sampah.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg">
                        Update
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app>
