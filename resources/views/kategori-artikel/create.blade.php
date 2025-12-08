<x-app>
    <x-slot:title>
        Tambah Kategori Artikel
    </x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Kategori Artikel
            </h2>
            <x-button variant="secondary" href="{{ route('kategori-artikel.index') }}">
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
            <form action="{{ route('kategori-artikel.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <x-form-group label="Nama Kategori" name="nama_kategori" required>
                            <x-input name="nama_kategori" value="{{ old('nama_kategori') }}" 
                                     placeholder="Masukkan nama kategori" required />
                        </x-form-group>

                        <x-form-group label="Slug" name="slug">
                            <x-input name="slug" value="{{ old('slug') }}" 
                                     placeholder="akan-dibuat-otomatis" readonly />
                            <p class="mt-1 text-xs text-gray-500">
                                Slug akan dibuat otomatis dari nama kategori
                            </p>
                        </x-form-group>

                        <x-form-group label="Warna Kategori" name="warna" required>
                            <div class="flex items-center space-x-2">
                                <x-input type="color" name="warna" value="{{ old('warna', '#3B82F6') }}" 
                                         class="w-16 h-10 rounded border" required />
                                <x-input type="text" name="warna_hex" value="{{ old('warna', '#3B82F6') }}" 
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
                                    <option value="{{ $value }}" {{ old('icon') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Status" name="status" required>
                            <x-select name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </x-select>
                        </x-form-group>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Deskripsi" name="deskripsi">
                            <x-textarea name="deskripsi" rows="6" 
                                        placeholder="Deskripsi kategori artikel (opsional)">{{ old('deskripsi') }}</x-textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Deskripsi singkat tentang kategori artikel ini (maksimal 500 karakter)
                            </p>
                        </x-form-group>

                        <x-form-group label="Urutan" name="urutan" required>
                            <x-input type="number" name="urutan" value="{{ old('urutan', 0) }}" 
                                     min="0" placeholder="0" required />
                            <p class="mt-1 text-xs text-gray-500">
                                Urutan tampil kategori (semakin kecil semakin atas)
                            </p>
                        </x-form-group>

                        <!-- Preview Warna -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Preview Kategori</h4>
                            <div id="category-preview" class="inline-flex items-center px-3 py-1 rounded-full text-white text-sm font-medium" style="background-color: #3B82F6;">
                                <i id="preview-icon" class="fas fa-tag mr-2"></i>
                                <span id="preview-name">Nama Kategori</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" href="{{ route('kategori-artikel.index') }}">
                        Batal
                    </x-button>
                    <x-button type="submit">
                        Simpan Kategori
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
