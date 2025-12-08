<x-app>
    <x-slot:title>
        Tambah Laporan Hasil Pengelolaan
    </x-slot:title>

    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Laporan Hasil Pengelolaan
            </h2>
            <x-button variant="secondary" href="{{ route('laporan-hasil-pengelolaan.index') }}">
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

        @if($pengelolaanLimbah->isEmpty())
            <x-card>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Tidak Ada Pengelolaan yang Dapat Dilaporkan
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Anda perlu memiliki pengelolaan limbah yang sudah selesai untuk dapat membuat laporan hasil.
                    </p>
                    <x-button href="{{ route('pengelolaan-limbah.index') }}">
                        Lihat Pengelolaan Limbah
                    </x-button>
                </div>
            </x-card>
        @else
            <x-card>
                <form action="{{ route('laporan-hasil-pengelolaan.store') }}" method="POST" enctype="multipart/form-data" id="laporan-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <x-form-group label="Pengelolaan Limbah" name="pengelolaan_limbah_id" required>
                                <x-select name="pengelolaan_limbah_id" id="pengelolaan_select" required>
                                    <option value="">Pilih Pengelolaan Limbah</option>
                                    @foreach($pengelolaanLimbah as $pengelolaan)
                                        <option value="{{ $pengelolaan->id }}" 
                                                data-satuan="{{ $pengelolaan->satuan }}"
                                                data-jumlah="{{ $pengelolaan->jumlah_dikelola }}"
                                                {{ old('pengelolaan_limbah_id') == $pengelolaan->id ? 'selected' : '' }}>
                                            {{ $pengelolaan->jenisLimbah->nama }} - 
                                            {{ $pengelolaan->tanggal_mulai->format('d/m/Y') }} 
                                            ({{ number_format($pengelolaan->jumlah_dikelola, 2) }} {{ $pengelolaan->satuan }})
                                        </option>
                                    @endforeach
                                </x-select>
                            </x-form-group>

                            <x-form-group label="Tanggal Selesai" name="tanggal_selesai" required>
                                <x-input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" 
                                         max="{{ date('Y-m-d') }}" required />
                            </x-form-group>

                            <div class="grid grid-cols-2 gap-4">
                                <x-form-group label="Jumlah Berhasil Dikelola" name="jumlah_berhasil_dikelola" required>
                                    <div class="flex">
                                        <x-input type="number" name="jumlah_berhasil_dikelola" value="{{ old('jumlah_berhasil_dikelola') }}" 
                                                 step="0.01" min="0" placeholder="0.00" required class="rounded-r-none" />
                                        <x-input type="text" id="satuan_display" readonly 
                                                 class="w-20 rounded-l-none border-l-0 bg-gray-50 dark:bg-gray-700" 
                                                 placeholder="Satuan" />
                                    </div>
                                </x-form-group>

                                <x-form-group label="Jumlah Residu" name="jumlah_residu">
                                    <x-input type="number" name="jumlah_residu" value="{{ old('jumlah_residu', 0) }}" 
                                             step="0.01" min="0" placeholder="0.00" />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Sisa limbah yang tidak berhasil dikelola
                                    </p>
                                </x-form-group>
                            </div>

                            <x-form-group label="Status Hasil" name="status_hasil" required>
                                <x-select name="status_hasil" required>
                                    <option value="">Pilih Status Hasil</option>
                                    <option value="berhasil" {{ old('status_hasil') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                                    <option value="partial" {{ old('status_hasil') == 'partial' ? 'selected' : '' }}>Sebagian Berhasil</option>
                                    <option value="gagal" {{ old('status_hasil') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </x-select>
                            </x-form-group>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <x-form-group label="Metode Disposal Akhir" name="metode_disposal_akhir">
                                <x-select name="metode_disposal_akhir">
                                    <option value="">Pilih Metode Disposal</option>
                                    @foreach(\App\Models\LaporanHasilPengelolaan::getMetodeDisposalOptions() as $key => $label)
                                        <option value="{{ $key }}" {{ old('metode_disposal_akhir') == $key ? 'selected' : '' }}>
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
                                    <x-input type="number" name="biaya_aktual" value="{{ old('biaya_aktual') }}" 
                                             step="1000" min="0" placeholder="0" class="rounded-l-none" />
                                </div>
                            </x-form-group>

                            <x-form-group label="Nomor Sertifikat" name="nomor_sertifikat">
                                <x-input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}" 
                                         placeholder="Nomor sertifikat pengelolaan" />
                            </x-form-group>

                            <x-form-group label="Catatan Hasil" name="catatan_hasil">
                                <x-textarea name="catatan_hasil" rows="4" 
                                            placeholder="Catatan tambahan mengenai hasil pengelolaan">{{ old('catatan_hasil') }}</x-textarea>
                            </x-form-group>
                        </div>
                    </div>

                    <!-- Dokumentasi Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <x-form-group label="Dokumentasi" name="dokumentasi">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Upload Dokumentasi (Opsional)
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
                                </div>
                            </div>
                        </x-form-group>
                    </div>

                    <!-- Info Pengelolaan -->
                    <div id="pengelolaan-info" class="hidden mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Informasi Pengelolaan</h4>
                        <div id="pengelolaan-details" class="text-sm text-blue-700 dark:text-blue-300">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <x-button variant="secondary" href="{{ route('laporan-hasil-pengelolaan.index') }}">
                            Batal
                        </x-button>
                        <x-button type="submit">
                            Simpan Laporan
                        </x-button>
                    </div>
                </form>
            </x-card>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pengelolaanSelect = document.getElementById('pengelolaan_select');
            const satuanDisplay = document.getElementById('satuan_display');
            const pengelolaanInfo = document.getElementById('pengelolaan-info');
            const pengelolaanDetails = document.getElementById('pengelolaan-details');
            const addFileBtn = document.getElementById('add-file-btn');
            const fileInputs = document.getElementById('file-inputs');
            let fileCount = 1;

            // Handle pengelolaan change
            pengelolaanSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    const satuan = selectedOption.dataset.satuan;
                    const jumlah = selectedOption.dataset.jumlah;
                    
                    satuanDisplay.value = satuan;
                    
                    // Show pengelolaan info
                    pengelolaanDetails.innerHTML = `
                        <p><strong>Jumlah Target:</strong> ${parseFloat(jumlah).toFixed(2)} ${satuan}</p>
                        <p><strong>Satuan:</strong> ${satuan}</p>
                    `;
                    pengelolaanInfo.classList.remove('hidden');
                } else {
                    satuanDisplay.value = '';
                    pengelolaanInfo.classList.add('hidden');
                }
            });

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

            // Trigger change if there's old value
            if (pengelolaanSelect.value) {
                pengelolaanSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app>

