<x-app>
    <x-slot:title>Tambah Pengelolaan Limbah</x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Pengelolaan Limbah
            </h2>
            <x-button variant="secondary" href="{{ route('pengelolaan-limbah.index') }}">
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

        @if($jenisLimbahs->isEmpty())
            <x-card>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Tidak Ada Stok Limbah
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Belum ada limbah di penyimpanan yang dapat dikelola.
                    </p>
                    <x-button href="{{ route('laporan-harian.create') }}">
                        Buat Laporan Harian
                    </x-button>
                </div>
            </x-card>
        @else
            <x-card>
                <form action="{{ route('pengelolaan-limbah.store') }}" method="POST" id="pengelolaan-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <!-- Tanggal Pengelolaan -->
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Pengelolaan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required
                                       class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Limbah -->
                            <div>
                                <label for="jenis_limbah_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Limbah <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_limbah_id" id="jenis_limbah_select" required
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Jenis Limbah</option>
                                    @foreach($jenisLimbahs as $jenis)
                                        <option value="{{ $jenis->id }}" 
                                                data-satuan="{{ $jenis->satuan_default }}"
                                                data-stok="{{ $jenis->total_stok_tersedia }}"
                                                {{ old('jenis_limbah_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }} ({{ $jenis->kode_limbah }}) - Stok: {{ number_format($jenis->total_stok_tersedia, 2) }} {{ $jenis->satuan_default }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_limbah_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Penyimpanan Sumber -->
                            <div>
                                <label for="penyimpanan_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Penyimpanan Sumber <span class="text-red-500">*</span>
                                </label>
                                <select name="penyimpanan_id" id="penyimpanan_select" required
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih jenis limbah terlebih dahulu</option>
                                </select>
                                @error('penyimpanan_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jumlah yang Dikelola -->
                            <div>
                                <label for="jumlah_dikelola" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jumlah yang Dikelola <span class="text-red-500">*</span>
                                </label>
                                <div class="flex">
                                    <input type="number" name="jumlah_dikelola" id="jumlah_dikelola" 
                                           value="{{ old('jumlah_dikelola') }}" 
                                           step="0.01" min="0.01" placeholder="0.00" required 
                                           class="block w-full text-sm border-gray-300 rounded-r-none rounded-l-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                                    <input type="text" id="satuan_display" readonly 
                                           class="w-20 text-sm border-l-0 border-gray-300 rounded-l-none rounded-r-md bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" 
                                           placeholder="Satuan" />
                                </div>
                                @error('jumlah_dikelola')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <!-- Jenis Pengelolaan (Siapa yang mengelola) -->
                            <div>
                                <label for="jenis_pengelolaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Pengelolaan <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_pengelolaan" id="jenis_pengelolaan" required
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Jenis Pengelolaan</option>
                                    <option value="internal" {{ old('jenis_pengelolaan') == 'internal' ? 'selected' : '' }}>
                                        Pengelolaan Internal
                                    </option>
                                    <option value="vendor_eksternal" {{ old('jenis_pengelolaan') == 'vendor_eksternal' ? 'selected' : '' }}>
                                        Vendor Eksternal
                                    </option>
                                </select>
                                @error('jenis_pengelolaan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Metode Pengelolaan (Bagaimana cara mengelola) -->
                            <div>
                                <label for="metode_pengelolaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Metode Pengelolaan <span class="text-red-500">*</span>
                                </label>
                                <select name="metode_pengelolaan" id="metode_pengelolaan" required
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Metode Pengelolaan</option>
                                    <option value="reduce" {{ old('metode_pengelolaan') == 'reduce' ? 'selected' : '' }}>
                                        Reduce (Pengurangan)
                                    </option>
                                    <option value="reuse" {{ old('metode_pengelolaan') == 'reuse' ? 'selected' : '' }}>
                                        Reuse (Penggunaan Kembali)
                                    </option>
                                    <option value="recycle" {{ old('metode_pengelolaan') == 'recycle' ? 'selected' : '' }}>
                                        Recycle (Daur Ulang)
                                    </option>
                                    <option value="treatment" {{ old('metode_pengelolaan') == 'treatment' ? 'selected' : '' }}>
                                        Treatment (Pengolahan)
                                    </option>
                                    <option value="disposal" {{ old('metode_pengelolaan') == 'disposal' ? 'selected' : '' }}>
                                        Disposal (Pembuangan)
                                    </option>
                                    <option value="incineration" {{ old('metode_pengelolaan') == 'incineration' ? 'selected' : '' }}>
                                        Incineration (Pembakaran)
                                    </option>
                                    <option value="landfill" {{ old('metode_pengelolaan') == 'landfill' ? 'selected' : '' }}>
                                        Landfill (Penimbunan)
                                    </option>
                                    <option value="composting" {{ old('metode_pengelolaan') == 'composting' ? 'selected' : '' }}>
                                        Composting (Pengomposan)
                                    </option>
                                    <option value="stabilization" {{ old('metode_pengelolaan') == 'stabilization' ? 'selected' : '' }}>
                                        Stabilization (Stabilisasi)
                                    </option>
                                </select>
                                @error('metode_pengelolaan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vendor (conditional) -->
                            <div id="vendor-section" style="display: none;">
                                <label for="vendor_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Vendor <span class="text-red-500">*</span>
                                </label>
                                <select name="vendor_id" id="vendor_select"
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Biaya Pengelolaan -->
                            <div>
                                <label for="biaya" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Biaya Pengelolaan
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        Rp
                                    </span>
                                    <input type="number" name="biaya" id="biaya" 
                                           value="{{ old('biaya') }}" 
                                           step="0.01" min="0" placeholder="0.00" 
                                           class="block w-full text-sm border-gray-300 rounded-l-none rounded-r-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                                </div>
                                @error('biaya')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="diproses" {{ old('status', 'diproses') == 'diproses' ? 'selected' : '' }}>
                                        Diproses
                                    </option>
                                    <option value="dalam_pengangkutan" {{ old('status') == 'dalam_pengangkutan' ? 'selected' : '' }}>
                                        Dalam Pengangkutan
                                    </option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Keterangan
                                </label>
                                <textarea name="catatan" id="catatan" rows="4" 
                                          placeholder="Tambahkan keterangan atau catatan khusus..."
                                          class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-green-400 dark:focus:ring-green-400">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>                        
                        </div>
                    </div>

                    <!-- Info Stok -->
                    <div id="stok-info" class="hidden mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Informasi Stok</h4>
                        <div id="stok-details" class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Peringatan Stok -->
                    <div id="stok-warning" class="hidden mt-4 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-yellow-800 dark:text-yellow-200">Peringatan Stok</h4>
                                <p id="stok-warning-text" class="text-sm text-yellow-700 dark:text-yellow-300 mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <x-button variant="secondary" href="{{ route('pengelolaan-limbah.index') }}">
                            Batal
                        </x-button>
                        <x-button type="submit" id="submit-btn">
                            Simpan Pengelolaan
                        </x-button>
                    </div>
                </form>
            </x-card>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisLimbahSelect = document.getElementById('jenis_limbah_select');
            const penyimpananSelect = document.getElementById('penyimpanan_select');
            const satuanDisplay = document.getElementById('satuan_display');
            const stokInfo = document.getElementById('stok-info');
            const stokWarning = document.getElementById('stok-warning');
            const jumlahInput = document.getElementById('jumlah_dikelola');
            const submitBtn = document.getElementById('submit-btn');
            const jenisPengelolaanSelect = document.getElementById('jenis_pengelolaan');
            const vendorSection = document.getElementById('vendor-section');

            let currentStokData = null;

            // Handle jenis limbah change
            jenisLimbahSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    const satuan = selectedOption.dataset.satuan;
                    const stok = parseFloat(selectedOption.dataset.stok);
                    
                    satuanDisplay.value = satuan;
                    currentStokData = { stok: stok, satuan: satuan };
                    
                    loadPenyimpananOptions(selectedOption.value);
                    showStokInfo();
                } else {
                    resetForm();
                }
            });

            // Handle jenis pengelolaan change (show/hide vendor section)
            jenisPengelolaanSelect.addEventListener('change', function() {
                if (this.value === 'vendor_eksternal') {
                    vendorSection.style.display = 'block';
                    document.getElementById('vendor_select').required = true;
                } else {
                    vendorSection.style.display = 'none';
                    document.getElementById('vendor_select').required = false;
                    document.getElementById('vendor_select').value = '';
                }
            });

            // Handle jumlah input change
            jumlahInput.addEventListener('input', function() {
                validateStok();
            });

            function loadPenyimpananOptions(jenisLimbahId) {
                penyimpananSelect.innerHTML = '<option value="">Loading...</option>';
                penyimpananSelect.disabled = true;

                fetch(`/api/penyimpanan-by-jenis-limbah?jenis_limbah_id=${jenisLimbahId}`)
                    .then(response => response.json())
                    .then(data => {
                        penyimpananSelect.disabled = false;
                        penyimpananSelect.innerHTML = '<option value="">Pilih Penyimpanan</option>';
                        
                        if (data.length > 0) {
                            data.forEach(penyimpanan => {
                                const option = document.createElement('option');
                                option.value = penyimpanan.id;
                                option.textContent = `${penyimpanan.nama_penyimpanan} - ${penyimpanan.lokasi} (Stok: ${parseFloat(penyimpanan.kapasitas_terpakai || 0).toFixed(2)} ${penyimpanan.satuan})`;
                                option.dataset.stok = penyimpanan.kapasitas_terpakai;
                                
                                // Check if this was previously selected
                                if ({{ old('penyimpanan_id') ?: 'null' }} == penyimpanan.id) {
                                    option.selected = true;
                                }
                                
                                penyimpananSelect.appendChild(option);
                            });
                        } else {
                            penyimpananSelect.innerHTML = '<option value="">Tidak ada penyimpanan dengan stok limbah ini</option>';
                        }

                        // Trigger change if there's old value
                        if ({{ old('penyimpanan_id') ?: 'null' }}) {
                            penyimpananSelect.dispatchEvent(new Event('change'));
                        }
                    })
                    .catch(error => {
                        console.error('Error loading penyimpanan:', error);
                        penyimpananSelect.disabled = false;
                        penyimpananSelect.innerHTML = '<option value="">Error loading data</option>';
                    });
            }

            // Handle penyimpanan change untuk update stok spesifik
            penyimpananSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.stok) {
                    const stokPenyimpanan = parseFloat(selectedOption.dataset.stok);
                    if (currentStokData) {
                        currentStokData.stok = stokPenyimpanan;
                        showStokInfo();
                    }
                }
            });

            function showStokInfo() {
                if (currentStokData) {
                    const stokDetails = document.getElementById('stok-details');
                    stokDetails.innerHTML = `
                        <div><strong>Total Stok Tersedia:</strong> ${currentStokData.stok.toFixed(2)} ${currentStokData.satuan}</div>
                        <div><strong>Status:</strong> ${currentStokData.stok > 0 ? '<span class="text-green-600">Tersedia</span>' : '<span class="text-red-600">Stok Habis</span>'}</div>
                    `;
                    stokInfo.classList.remove('hidden');
                    validateStok();
                }
            }

            function validateStok() {
                const jumlah = parseFloat(jumlahInput.value) || 0;
                
                if (currentStokData && jumlah > 0) {
                    if (jumlah > currentStokData.stok) {
                        showStokWarning(`Jumlah yang dikelola (${jumlah.toFixed(2)} ${currentStokData.satuan}) melebihi stok tersedia (${currentStokData.stok.toFixed(2)} ${currentStokData.satuan})`);
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        hideStokWarning();
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                } else {
                    hideStokWarning();
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            function showStokWarning(message) {
                document.getElementById('stok-warning-text').textContent = message;
                stokWarning.classList.remove('hidden');
            }

            function hideStokWarning() {
                stokWarning.classList.add('hidden');
            }

            function resetForm() {
                satuanDisplay.value = '';
                penyimpananSelect.innerHTML = '<option value="">Pilih jenis limbah terlebih dahulu</option>';
                penyimpananSelect.disabled = true;
                stokInfo.classList.add('hidden');
                hideStokWarning();
                currentStokData = null;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Form validation before submit
            document.getElementById('pengelolaan-form').addEventListener('submit', function(e) {
                const jumlah = parseFloat(jumlahInput.value) || 0;
                
                if (currentStokData && jumlah > currentStokData.stok) {
                    e.preventDefault();
                    alert('Jumlah yang dikelola melebihi stok tersedia!');
                    return false;
                }

                // Validasi vendor jika jenis pengelolaan vendor eksternal
                if (jenisPengelolaanSelect.value === 'vendor_eksternal') {
                    const vendorSelect = document.getElementById('vendor_select');
                    if (!vendorSelect.value) {
                        e.preventDefault();
                        alert('Vendor wajib dipilih untuk pengelolaan eksternal!');
                        vendorSelect.focus();
                        return false;
                    }
                }

                // Konfirmasi sebelum submit
                if (!confirm('Yakin ingin menyimpan data pengelolaan limbah ini?')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Auto-format number inputs
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value && !isNaN(this.value)) {
                        this.value = parseFloat(this.value).toFixed(2);
                    }
                });
            });

            // Initialize form if old values exist
            @if(old('jenis_limbah_id'))
                setTimeout(() => {
                    jenisLimbahSelect.dispatchEvent(new Event('change'));
                }, 100);
            @endif

            @if(old('jenis_pengelolaan'))
                setTimeout(() => {
                    jenisPengelolaanSelect.dispatchEvent(new Event('change'));
                }, 100);
            @endif
        });
    </script>

    <style>
        /* Custom styles for better UX */
        .form-error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Loading state for select options */
        .loading-select {
            background-image: url("data:image/svg+xml,%3csvg width='20' height='20' xmlns='http://www.w3.org/2000/svg'%3e%3cg fill='none' fill-rule='evenodd'%3e%3cg fill='%236b7280' fill-rule='nonzero'%3e%3cpath d='M10 3v3l4-4-4-4v3c-4.42 0-8 3.58-8 8 0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6zm6.76 1.74L14.3 9.2c.44.84.7 1.79.7 2.8 0 3.31-2.69 6-6 6v-3l-4 4 4 4v-3c4.42 0 8-3.58 8-8 0-1.57-.46-3.03-1.24-4.26z'/%3e%3c/g%3e%3c/g%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.25rem;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .grid-cols-1.lg\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
            
            .flex.space-x-3 {
                flex-direction: column;
                space-x: 0
                            }
            
            .flex.space-x-3 > * + * {
                margin-left: 0;
                margin-top: 0.75rem;
            }
        }

        /* Animation for showing/hiding sections */
        #vendor-section {
            transition: all 0.3s ease-in-out;
        }

        /* Better focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* Loading spinner for select */
        .select-loading {
            position: relative;
        }

        .select-loading::after {
            content: '';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid #10b981;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        /* Success/Error states */
        .input-success {
            border-color: #10b981;
            background-color: #f0fdf4;
        }

        .input-error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        /* Disabled state improvements */
        input:disabled, select:disabled, textarea:disabled {
            background-color: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        /* Dark mode improvements */
        @media (prefers-color-scheme: dark) {
            input:disabled, select:disabled, textarea:disabled {
                background-color: #374151;
                color: #9ca3af;
            }
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #1f2937;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.875rem;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* Card hover effects */
        .card-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Progress indicator */
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .progress-step .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 0.75rem;
        }

        .progress-step.active .step-number {
            background-color: #10b981;
            color: white;
        }

        .progress-step.completed .step-number {
            background-color: #059669;
            color: white;
        }

        /* Button loading state */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .btn-loading span {
            opacity: 0;
        }

        /* Form validation indicators */
        .field-valid {
            position: relative;
        }

        .field-valid::after {
            content: '✓';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #10b981;
            font-weight: bold;
        }

        .field-invalid {
            position: relative;
        }

        .field-invalid::after {
            content: '✗';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #ef4444;
            font-weight: bold;
        }

        /* Smooth transitions for all interactive elements */
        input, select, textarea, button {
            transition: all 0.2s ease-in-out;
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .print-only {
                display: block !important;
            }
        }
    </style>
</x-app>

