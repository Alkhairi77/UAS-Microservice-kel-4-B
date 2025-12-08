<x-app>
    <x-slot:title>
        Edit Kategori Artikel
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Kategori Artikel
            </h2>
            <x-button variant="secondary" href="{{ route('kategori-artikel.index', $kategoriArtikel) }}">
                Kembali
            </x-button>
        </div>

        @if ($errors->any())
            <x-alert type="error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card>
            <form action="{{ route('kategori-artikel.update', $kategoriArtikel) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <x-form-group label="Nama Kategori" name="nama_kategori" required>
                            <x-input name="nama_kategori" value="{{ old('nama_kategori', $kategoriArtikel->nama_kategori ?? $kategoriArtikel->nama ?? '') }}" 
                                     placeholder="Masukkan nama kategori" required />
                        </x-form-group>

                        <x-form-group label="Slug" name="slug">
                            <x-input name="slug" value="{{ old('slug', $kategoriArtikel->slug) }}" 
                                     placeholder="akan-dibuat-otomatis" readonly />
                            <p class="mt-1 text-xs text-gray-500">
                                Slug akan dibuat otomatis dari nama kategori
                            </p>
                        </x-form-group>

                        <x-form-group label="Warna Kategori" name="warna" required>
                            <div class="flex items-center space-x-2">
                                <x-input type="color" name="warna" value="{{ old('warna', $kategoriArtikel->warna ?? '#3B82F6') }}" 
                                         class="w-16 h-10 rounded border" required />
                                <x-input type="text" name="warna_hex" value="{{ old('warna', $kategoriArtikel->warna ?? '#3B82F6') }}" 
                                         placeholder="#3B82F6" class="flex-1" readonly />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih warna untuk kategori artikel
                            </p>
                        </x-form-group>

                        <x-form-group label="Icon" name="icon">
                            <x-select name="icon">
                                <option value="">Pilih Icon (Opsional)</option>
                                @foreach(\App\Models\KategoriArtikel::getIconOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ old('icon', $kategoriArtikel->icon) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Status" name="status" required>
                            <x-select name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status', $kategoriArtikel->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status', $kategoriArtikel->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </x-select>
                        </x-form-group>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Deskripsi" name="deskripsi">
                            <x-textarea name="deskripsi" rows="6" 
                                        placeholder="Deskripsi kategori artikel (opsional)">{{ old('deskripsi', $kategoriArtikel->deskripsi) }}</x-textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Deskripsi singkat tentang kategori artikel ini (maksimal 500 karakter)
                            </p>
                        </x-form-group>

                        <x-form-group label="Urutan" name="urutan" required>
                            <x-input type="number" name="urutan" value="{{ old('urutan', $kategoriArtikel->urutan ?? 0) }}" 
                                     min="0" placeholder="0" required />
                            <p class="mt-1 text-xs text-gray-500">
                                Urutan tampil kategori (semakin kecil semakin atas)
                            </p>
                        </x-form-group>

                        <!-- Preview Warna -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Preview Kategori</h4>
                            <div id="category-preview" class="inline-flex items-center px-3 py-1 rounded-full text-white text-sm font-medium" style="background-color: {{ old('warna', $kategoriArtikel->warna ?? '#3B82F6') }};">
                                <i id="preview-icon" class="{{ old('icon', $kategoriArtikel->icon ?? 'fas fa-tag') }} mr-2"></i>
                                <span id="preview-name">{{ old('nama_kategori', $kategoriArtikel->nama_kategori ?? $kategoriArtikel->nama ?? 'Nama Kategori') }}</span>
                            </div>
                        </div>

                        <!-- Info Artikel -->
                        @if($kategoriArtikel->artikel_count > 0)
                            <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-blue-800 dark:text-blue-200">Informasi</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                            Kategori ini memiliki {{ $kategoriArtikel->artikel_count }} artikel. 
                                            Perubahan akan mempengaruhi semua artikel dalam kategori ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" href="{{ route('kategori-artikel.index', $kategoriArtikel) }}">
                        Batal
                    </x-button>
                    <x-button type="submit">
                        Update Kategori
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.querySelector('input[name="nama_kategori"]');
            const slugInput = document.querySelector('input[name="slug"]');
            const warnaInput = document.querySelector('input[name="warna"]');
            const warnaHexInput = document.querySelector('input[name="warna_hex"]');
            const iconSelect = document.querySelector('select[name="icon"]');
            const previewElement = document.getElementById('category-preview');
            const previewIcon = document.getElementById('preview-icon');
            const previewName = document.getElementById('preview-name');

            // Auto generate slug from nama_kategori
            if (namaInput && slugInput) {
                namaInput.addEventListener('input', function() {
                    const nama = this.value;
                    const slug = nama.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim('-');
                    slugInput.value = slug;
                    // Update preview name
                    if (previewName) {
                        previewName.textContent = nama || 'Nama Kategori';
                    }
                });
            }

            // Sync color inputs
            if (warnaInput && warnaHexInput && previewElement) {
                warnaInput.addEventListener('input', function() {
                    warnaHexInput.value = this.value;
                    previewElement.style.backgroundColor = this.value;
                });

                warnaHexInput.addEventListener('input', function() {
                    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                        warnaInput.value = this.value;
                        previewElement.style.backgroundColor = this.value;
                    }
                });
            }

            // Update preview icon
            if (iconSelect && previewIcon) {
                iconSelect.addEventListener('change', function() {
                    const selectedIcon = this.value || 'fas fa-tag';
                    previewIcon.className = selectedIcon + ' mr-2';
                });
            }
        });
    </script>
</x-app>