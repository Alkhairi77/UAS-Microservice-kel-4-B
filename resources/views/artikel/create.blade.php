<x-app>
    <x-slot:title>
        Tambah Artikel
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Artikel
            </h2>
            <x-button variant="secondary" href="{{ route('admin.artikel.index') }}">
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
            <!-- Tambahkan di bagian atas form, setelah @csrf -->
            @csrf

            <!-- Debug: tampilkan error jika ada -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <strong>Errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Debug: tampilkan old input -->
            @if(old())
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                    <strong>Old Input:</strong>
                    <pre>{{ print_r(old(), true) }}</pre>
                </div>
            @endif
            <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <x-form-group label="Judul Artikel" name="judul" required>
                            <x-input name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul artikel"
                                required />
                        </x-form-group>

                        <x-form-group label="Slug" name="slug">
                            <x-input name="slug" value="{{ old('slug') }}" placeholder="akan-dibuat-otomatis"
                                readonly />
                            <p class="mt-1 text-xs text-gray-500">
                                Slug akan dibuat otomatis dari judul artikel
                            </p>
                        </x-form-group>

                        <x-form-group label="Excerpt (Ringkasan)" name="excerpt">
                            <x-textarea name="excerpt" rows="3"
                                placeholder="Ringkasan singkat artikel (opsional)">{{ old('excerpt') }}</x-textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Jika kosong, akan dibuat otomatis dari konten artikel
                            </p>
                        </x-form-group>

                        <x-form-group label="Konten Artikel" name="konten" required>
                            <x-textarea name="konten" rows="15" placeholder="Tulis konten artikel di sini..."
                                required>{{ old('konten') }}</x-textarea>
                        </x-form-group>

                        <!-- SEO Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">SEO Settings</h3>

                            <div class="space-y-4">
                                <x-form-group label="Meta Title" name="meta_title">
                                    <x-input name="meta_title" value="{{ old('meta_title') }}"
                                        placeholder="Judul untuk SEO (max 60 karakter)" maxlength="60" />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Jika kosong, akan menggunakan judul artikel
                                    </p>
                                </x-form-group>

                                <x-form-group label="Meta Description" name="meta_description">
                                    <x-textarea name="meta_description" rows="3"
                                        placeholder="Deskripsi untuk SEO (max 160 karakter)"
                                        maxlength="160">{{ old('meta_description') }}</x-textarea>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Jika kosong, akan menggunakan excerpt artikel
                                    </p>
                                </x-form-group>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <x-form-group label="Status" name="status" required>
                            <x-select name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                    Dipublikasikan</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Diarsipkan
                                </option>
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Kategori" name="kategori_artikel_id" required>
                            <x-select name="kategori_artikel_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $id => $nama)
                                    <option value="{{ $id }}" {{ old('kategori_artikel_id') == $id ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Tanggal Publikasi" name="tanggal_publikasi">
                            <x-input type="datetime-local" name="tanggal_publikasi"
                                value="{{ old('tanggal_publikasi') }}" />
                            <p class="mt-1 text-xs text-gray-500">
                                Kosongkan untuk menggunakan waktu saat ini jika status dipublikasikan
                            </p>
                        </x-form-group>

                        <x-form-group label="Gambar Utama" name="gambar_utama">
                            <x-input type="file" name="gambar_utama" accept="image/*" />
                            <p class="mt-1 text-xs text-gray-500">
                                Format: JPG, PNG, GIF. Maksimal 2MB
                            </p>
                        </x-form-group>

                        <!-- Preview Gambar -->
                        <div id="image-preview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Preview Gambar
                            </label>
                            <img id="preview-img" class="w-full h-48 object-cover rounded-lg border" alt="Preview">
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Tips Menulis Artikel</h4>
                            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li>• Gunakan judul yang menarik dan deskriptif</li>
                                <li>• Tulis excerpt yang menggambarkan isi artikel</li>
                                <li>• Gunakan gambar yang relevan dan berkualitas</li>
                                <li>• Optimalkan meta title dan description untuk SEO</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" href="{{ route('admin.artikel.index') }}">
                        Batal
                    </x-button>
                    <x-button type="submit" name="action" value="draft">
                        Simpan sebagai Draft
                    </x-button>
                    <x-button type="submit" name="action" value="publish">
                        Publikasikan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const judulInput = document.querySelector('input[name="judul"]');
            const slugInput = document.querySelector('input[name="slug"]');
            const gambarInput = document.querySelector('input[name="gambar_utama"]');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            // Auto generate slug from title
            judulInput.addEventListener('input', function () {
                const judul = this.value;
                const slug = judul.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                slugInput.value = slug;
            });

            // Image preview
            gambarInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                }
            });
        });
    </script>
</x-app>