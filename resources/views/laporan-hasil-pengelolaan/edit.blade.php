<x-app>
    <x-slot:title>
        Edit Laporan Hasil Pengelolaan
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Laporan Hasil Pengelolaan
            </h2>
            <x-button variant="secondary" href="{{ route('laporan-hasil-pengelolaan.show', $laporanHasilPengelolaan) }}">
                Kembali
            </x-button>
        </div>

        @if ($errors->any())
            <x-alert type="error" class="mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card>
            <form action="{{ route('laporan-hasil-pengelolaan.update', $laporanHasilPengelolaan) }}" method="POST" enctype="multipart/form-data" id="laporan-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- Info Pengelolaan (Read-only) -->
                        <x-form-group label="Pengelolaan Limbah" name="pengelolaan_limbah_id">
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $laporanHasilPengelolaan->pengelolaanLimbah->jenisLimbah->nama }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Tanggal: {{ $laporanHasilPengelolaan->pengelolaanLimbah->tanggal_mulai->format('d/m/Y') }} | 
                                    Target: {{ number_format($laporanHasilPengelolaan->pengelolaanLimbah->jumlah_dikelola, 2) }} {{ $laporanHasilPengelolaan->satuan }}
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Pengelolaan limbah tidak dapat diubah setelah laporan dibuat</p>
                        </x-form-group>

                        <x-form-group label="Tanggal Selesai" name="tanggal_selesai" required>
                            <x-input type="date" name="tanggal_selesai" 
                                     value="{{ old('tanggal_selesai', $laporanHasilPengelolaan->tanggal_selesai->format('Y-m-d')) }}" 
                                     max="{{ date('Y-m-d') }}" required />
                        </x-form-group>

                        <div class="grid grid-cols-2 gap-4">
                            <x-form-group label="Jumlah Berhasil Dikelola" name="jumlah_berhasil_dikelola" required>
                                <div class="flex">
                                    <x-input type="number" name="jumlah_berhasil_dikelola" 
                                             value="{{ old('jumlah_berhasil_dikelola', $laporanHasilPengelolaan->jumlah_berhasil_dikelola) }}" 
                                             step="0.01" min="0" placeholder="0.00" required class="rounded-r-none"
                                             data-max="{{ $laporanHasilPengelolaan->pengelolaanLimbah->jumlah_dikelola }}" />
                                    <x-input type="text" value="{{ $laporanHasilPengelolaan->satuan }}" readonly 
                                             class="w-20 rounded-l-none border-l-0 bg-gray-50 dark:bg-gray-700" />
                                </div>
                            </x-form-group>

                            <x-form-group label="Jumlah Residu" name="jumlah_residu">
                                <x-input type="number" name="jumlah_residu" 
                                         value="{{ old('jumlah_residu', $laporanHasilPengelolaan->jumlah_residu) }}" 
                                         step="0.01" min="0" placeholder="0.00" />
                                <p class="mt-1 text-xs text-gray-500">
                                    Sisa limbah yang tidak berhasil dikelola
                                </p>
                            </x-form-group>
                        </div>

                        <x-form-group label="Status Hasil" name="status_hasil" required>
                            <x-select name="status_hasil" required>
                                <option value="">Pilih Status Hasil</option>
                                <option value="berhasil" {{ old('status_hasil', $laporanHasilPengelolaan->status_hasil) == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                                <option value="partial" {{ old('status_hasil', $laporanHasilPengelolaan->status_hasil) == 'partial' ? 'selected' : '' }}>Sebagian Berhasil</option>
                                <option value="gagal" {{ old('status_hasil', $laporanHasilPengelolaan->status_hasil) == 'gagal' ? 'selected' : '' }}>Gagal</option>
                            </x-select>
                        </x-form-group>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Metode Disposal Akhir" name="metode_disposal_akhir">
                            <x-select name="metode_disposal_akhir">
                                <option value="">Pilih Metode Disposal</option>
                                @foreach(\App\Models\LaporanHasilPengelolaan::getMetodeDisposalOptions() as $key => $label)
                                    <option value="{{ $key }}" {{ old('metode_disposal_akhir', $laporanHasilPengelolaan->metode_disposal_akhir) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Biaya Aktual" name="biaya_aktual">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    Rp
                                </span>
                                <x-input type="number" name="biaya_aktual" 
                                         value="{{ old('biaya_aktual', $laporanHasilPengelolaan->biaya_aktual) }}" 
                                         step="1000" min="0" placeholder="0" class="rounded-l-none" />
                            </div>
                        </x-form-group>

                        <x-form-group label="Nomor Sertifikat" name="nomor_sertifikat">
                            <x-input type="text" name="nomor_sertifikat" 
                                     value="{{ old('nomor_sertifikat', $laporanHasilPengelolaan->nomor_sertifikat) }}" 
                                     placeholder="Nomor sertifikat pengelolaan" />
                        </x-form-group>

                        <x-form-group label="Catatan Hasil" name="catatan_hasil">
                            <x-textarea name="catatan_hasil" rows="4" 
                                        placeholder="Catatan tambahan mengenai hasil pengelolaan">{{ old('catatan_hasil', $laporanHasilPengelolaan->catatan_hasil) }}</x-textarea>
                        </x-form-group>
                    </div>
                </div>

                <!-- Dokumentasi Section -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-form-group label="Dokumentasi" name="dokumentasi">
                        <!-- Dokumentasi yang sudah ada -->
                        @if($laporanHasilPengelolaan->dokumentasi)
                            @php
                                $existingFiles = json_decode($laporanHasilPengelolaan->dokumentasi, true) ?: [];
                            @endphp
                            
                            @if(!empty($existingFiles))
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Dokumentasi Saat Ini
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($existingFiles as $index => $file)
                                            @php
                                                $fileName = basename($file);
                                                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png']);
                                                $isPdf = $fileExtension === 'pdf';
                                            @endphp
                                            
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                                @if($isImage)
                                                    <div class="mb-2">
                                                        <img src="{{ Storage::url($file) }}" 
                                                             alt="Dokumentasi {{ $index + 1 }}" 
                                                             class="w-full h-24 object-cover rounded">
                                                    </div>
                                                @elseif($isPdf)
                                                    <div class="mb-2 flex items-center justify-center h-24 bg-red-50 dark:bg-red-900 rounded">
                                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="mb-2 flex items-center justify-center h-24 bg-gray-50 dark:bg-gray-700 rounded">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                
                                                <p class="text-xs text-gray-600 dark:text-gray-400 truncate mb-2">
                                                    {{ $fileName }}
                                                </p>
                                                
                                                <a href="{{ route('laporan-hasil-pengelolaan.download-dokumentasi', [$laporanHasilPengelolaan, $index]) }}"
                                                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                    Download
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="hapus_dokumentasi" value="1" 
                                                   class="text-red-600 rounded">
                                            <span class="ml-2 text-sm text-red-600">Hapus semua dokumentasi yang ada</span>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Upload dokumentasi baru -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Upload Dokumentasi Baru (Opsional)
                                </label>
                                <button type="button" id="add-file-btn" 
                                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    + Tambah File
                                </button>
                            </div>
                            
                            <div id="file-inputs" class="space-y-2">
                                <div class="file-input-group">
                                    <input type="file" name="dokumentasi[]" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                                </div>
                            </div>
                            
                            <div class="text-xs text-gray-500 space-y-1">
                                                                <p>• Format yang diizinkan: PDF, JPG, JPEG, PNG</p>
                                <p>• Ukuran maksimal per file: 5MB</p>
                                <p>• Maksimal 5 file dokumentasi</p>
                                <p>• File baru akan ditambahkan ke dokumentasi yang ada (kecuali jika dihapus)</p>
                            </div>
                        </div>
                    </x-form-group>
                </div>

                <!-- Warning untuk jumlah -->
                <div id="jumlah-warning" class="hidden mt-4 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Peringatan Jumlah
                            </h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1" id="warning-text">
                                <!-- Will be populated by JavaScript -->
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" href="{{ route('laporan-hasil-pengelolaan.show', $laporanHasilPengelolaan) }}">
                        Batal
                    </x-button>
                    <x-button type="submit" id="submit-btn">
                        Update Laporan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addFileBtn = document.getElementById('add-file-btn');
            const fileInputs = document.getElementById('file-inputs');
            const jumlahBerhasilInput = document.querySelector('input[name="jumlah_berhasil_dikelola"]');
            const jumlahResiduInput = document.querySelector('input[name="jumlah_residu"]');
            const jumlahWarning = document.getElementById('jumlah-warning');
            const warningText = document.getElementById('warning-text');
            const submitBtn = document.getElementById('submit-btn');
            let fileCount = 1;

            // Handle add file button
            addFileBtn.addEventListener('click', function() {
                if (fileCount < 5) {
                    fileCount++;
                    const newFileInput = document.createElement('div');
                    newFileInput.className = 'file-input-group flex items-center space-x-2';
                    newFileInput.innerHTML = `
                        <input type="file" name="dokumentasi[]" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                        <button type="button" class="remove-file-btn text-red-600 hover:text-red-800 text-sm">
                            Hapus
                        </button>
                    `;
                    fileInputs.appendChild(newFileInput);
                    
                    // Add remove functionality
                    newFileInput.querySelector('.remove-file-btn').addEventListener('click', function() {
                        newFileInput.remove();
                        fileCount--;
                        updateAddButton();
                    });
                    
                    updateAddButton();
                }
            });

            function updateAddButton() {
                if (fileCount >= 5) {
                    addFileBtn.style.display = 'none';
                } else {
                    addFileBtn.style.display = 'inline';
                }
            }

            // Validasi jumlah
            function validateJumlah() {
                const jumlahBerhasil = parseFloat(jumlahBerhasilInput.value) || 0;
                const jumlahResidu = parseFloat(jumlahResiduInput.value) || 0;
                const maxJumlah = parseFloat(jumlahBerhasilInput.dataset.max) || 0;
                const totalJumlah = jumlahBerhasil + jumlahResidu;

                if (totalJumlah > maxJumlah) {
                    warningText.textContent = `Total jumlah (${totalJumlah.toFixed(2)}) melebihi target pengelolaan (${maxJumlah.toFixed(2)}).`;
                    jumlahWarning.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    jumlahWarning.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Event listeners untuk validasi
            if (jumlahBerhasilInput) {
                jumlahBerhasilInput.addEventListener('input', validateJumlah);
            }
            if (jumlahResiduInput) {
                jumlahResiduInput.addEventListener('input', validateJumlah);
            }

            // Initial validation
            validateJumlah();
        });
    </script>
</x-app>

