<x-app>
    <x-slot:title>Edit Pengelolaan Limbah</x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Pengelolaan Limbah
            </h2>
            <div class="flex space-x-2">
                <x-button variant="secondary" href="{{ route('pengelolaan-limbah.show', $pengelolaanLimbah) }}">
                    Detail
                </x-button>
                <x-button variant="secondary" href="{{ route('pengelolaan-limbah.index') }}">
                    Kembali
                </x-button>
            </div>
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
        <!-- Info Pengelolaan Saat Ini -->
        <x-card class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Pengelolaan Saat Ini</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Tanggal:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium ml-2">{{ $pengelolaanLimbah->tanggal_mulai->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Jenis Limbah:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium ml-2">{{ $pengelolaanLimbah->jenisLimbah->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Jumlah Saat Ini:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium ml-2">{{ number_format($pengelolaanLimbah->jumlah_dikelola, 2) }} {{ $pengelolaanLimbah->satuan }}</span>
                </div>
            </div>
        </x-card>

        <x-card>
            <form action="{{ route('pengelolaan-limbah.update', $pengelolaanLimbah) }}" method="POST" id="pengelolaan-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <x-form-group label="Tanggal Mulai Pengelolaan" name="tanggal_mulai" required>
                            <x-input type="date" name="tanggal_mulai" 
                                     value="{{ old('tanggal_mulai', $pengelolaanLimbah->tanggal_mulai->format('Y-m-d')) }}" 
                                     min="{{ date('Y-m-d') }}" required />
                        </x-form-group>

                        <x-form-group label="Jenis Limbah" name="jenis_limbah_id" required>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $pengelolaanLimbah->jenisLimbah->nama }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Kode: {{ $pengelolaanLimbah->jenisLimbah->kode_limbah }} | 
                                    Satuan: {{ $pengelolaanLimbah->satuan }}
                                </div>
                            </div>
                            <input type="hidden" name="jenis_limbah_id" value="{{ $pengelolaanLimbah->jenis_limbah_id }}">
                            <p class="mt-1 text-xs text-gray-500">Jenis limbah tidak dapat diubah setelah pengelolaan dibuat</p>
                        </x-form-group>

                        <x-form-group label="Penyimpanan Sumber" name="penyimpanan_id" required>
                            <x-select name="penyimpanan_id" id="penyimpanan_select" required>
                                <option value="">Pilih Penyimpanan</option>
                                @foreach($penyimpanans as $penyimpanan)
                                    <option value="{{ $penyimpanan->id }}" 
                                            data-stok="{{ $penyimpanan->kapasitas_terpakai }}"
                                            {{ old('penyimpanan_id', $pengelolaanLimbah->penyimpanan_id) == $penyimpanan->id ? 'selected' : '' }}>
                                        {{ $penyimpanan->nama_penyimpanan }} - {{ $penyimpanan->lokasi }} 
                                        (Stok: {{ number_format($penyimpanan->kapasitas_terpakai, 2) }} {{ $penyimpanan->satuan }})
                                    </option>
                                @endforeach
                            </x-select>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih penyimpanan yang akan diambil limbahnya untuk dikelola
                            </p>
                        </x-form-group>

                        <x-form-group label="Jumlah yang Dikelola" name="jumlah_dikelola" required>
                            <div class="flex">
                                <x-input type="number" name="jumlah_dikelola" 
                                         value="{{ old('jumlah_dikelola', $pengelolaanLimbah->jumlah_dikelola) }}" 
                                         step="0.01" min="0.01" placeholder="0.00" required class="rounded-r-none" />
                                <x-input type="text" id="satuan_display" readonly 
                                         value="{{ $pengelolaanLimbah->satuan }}"
                                         class="w-20 rounded-l-none border-l-0 bg-gray-50 dark:bg-gray-700" />
                            </div>
                        </x-form-group>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Jenis Pengelolaan" name="jenis_pengelolaan" required>
                            <x-select name="jenis_pengelolaan" id="jenis_pengelolaan_select" required>
                                <option value="">Pilih Jenis Pengelolaan</option>
                                <option value="internal" {{ old('jenis_pengelolaan', $pengelolaanLimbah->jenis_pengelolaan) == 'internal' ? 'selected' : '' }}>
                                    Pengelolaan Internal
                                </option>
                                <option value="vendor_eksternal" {{ old('jenis_pengelolaan', $pengelolaanLimbah->jenis_pengelolaan) == 'vendor_eksternal' ? 'selected' : '' }}>
                                    Vendor Eksternal
                                </option>
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Metode Pengelolaan" name="metode_pengelolaan" required>
                            <x-select name="metode_pengelolaan" required>
                                <option value="">Pilih Metode Pengelolaan</option>
                                @foreach(\App\Models\PengelolaanLimbah::getMetodePengelolaanOptions() as $key => $label)
                                    <option value="{{ $key }}" {{ old('metode_pengelolaan', $pengelolaanLimbah->metode_pengelolaan) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-select>
                        </x-form-group>

                        <!-- Vendor (conditional) -->
                        <div id="vendor-section" style="display: {{ old('jenis_pengelolaan', $pengelolaanLimbah->jenis_pengelolaan) == 'vendor_eksternal' ? 'block' : 'none' }};">
                            <x-form-group label="Vendor" name="vendor_id">
                                <x-select name="vendor_id" id="vendor_select">
                                    <option value="">Pilih Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $pengelolaanLimbah->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->nama_perusahaan }} - {{ $vendor->jenis_layanan_name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </x-form-group>
                        </div>

                        <x-form-group label="Biaya Pengelolaan" name="biaya">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    Rp
                                </span>
                                <x-input type="number" name="biaya" 
                                         value="{{ old('biaya', $pengelolaanLimbah->biaya) }}" 
                                         step="0.01" min="0" placeholder="0.00" class="rounded-l-none" />
                            </div>
                        </x-form-group>

                        <x-form-group label="Status" name="status" required>
                            <x-select name="status" required>
                                <option value="diproses" {{ old('status', $pengelolaanLimbah->status) == 'diproses' ? 'selected' : '' }}>
                                    Sedang Diproses
                                </option>
                                <option value="dalam_pengangkutan" {{ old('status', $pengelolaanLimbah->status) == 'dalam_pengangkutan' ? 'selected' : '' }}>
                                    Dalam Pengangkutan
                                </option>
                                <option value="selesai" {{ old('status', $pengelolaanLimbah->status) == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                <option value="dibatalkan" {{ old('status', $pengelolaanLimbah->status) == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </x-select>
                        </x-form-group>

                        <x-form-group label="Keterangan" name="catatan">
                            <x-textarea name="catatan" rows="4" placeholder="Tambahkan keterangan atau catatan khusus...">{{ old('catatan', $pengelolaanLimbah->catatan) }}</x-textarea>
                        </x-form-group>
                    </div>
                </div>

                <!-- Info Stok -->
                <div id="stok-info" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
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

                <!-- Perubahan Stok -->
                <div id="perubahan-stok" class="mt-4 p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                    <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">Perubahan Stok</h4>
                    <div id="perubahan-details" class="text-sm text-green-700 dark:text-green-300 space-y-1">
                        <div><strong>Jumlah Sebelumnya:</strong> {{ number_format($pengelolaanLimbah->jumlah_dikelola, 2) }} {{ $pengelolaanLimbah->satuan }}</div>
                        <div id="jumlah-baru"><strong>Jumlah Baru:</strong> <span id="jumlah-baru-value">{{ number_format($pengelolaanLimbah->jumlah_dikelola, 2) }}</span> {{ $pengelolaanLimbah->satuan }}</div>
                        <div id="selisih-st
                        <div id="selisih-stok"><strong>Selisih:</strong> <span id="selisih-value">0.00</span> {{ $pengelolaanLimbah->satuan }}</div>
                        <div id="dampak-stok" class="text-xs mt-2 p-2 bg-white dark:bg-gray-800 rounded">
                            <span id="dampak-text">Tidak ada perubahan stok</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" href="{{ route('pengelolaan-limbah.show', $pengelolaanLimbah) }}">
                        Batal
                    </x-button>
                    <x-button type="submit" id="submit-btn">
                        Update Pengelolaan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const penyimpananSelect = document.getElementById('penyimpanan_select');
            const stokInfo = document.getElementById('stok-info');
            const stokWarning = document.getElementById('stok-warning');
            const jumlahInput = document.querySelector('input[name="jumlah_dikelola"]');
            const submitBtn = document.getElementById('submit-btn');
            const jenisPengelolaanSelect = document.getElementById('jenis_pengelolaan_select');
            const vendorSection = document.getElementById('vendor-section');
            const vendorSelect = document.getElementById('vendor_select');

            // Data awal
            const originalJumlah = {{ $pengelolaanLimbah->jumlah_dikelola }};
            let currentStokData = null;

            // Handle jenis pengelolaan change (show/hide vendor section)
            jenisPengelolaanSelect.addEventListener('change', function() {
                if (this.value === 'vendor_eksternal') {
                    vendorSection.style.display = 'block';
                    vendorSelect.required = true;
                } else {
                    vendorSection.style.display = 'none';
                    vendorSelect.required = false;
                    vendorSelect.value = '';
                }
            });

            // Handle penyimpanan change
            penyimpananSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const stok = parseFloat(selectedOption.dataset.stok);
                    currentStokData = { 
                        stok: stok, 
                        satuan: '{{ $pengelolaanLimbah->satuan }}',
                        nama: selectedOption.textContent.split(' (Stok:')[0]
                    };
                    showStokInfo();
                    validateStok();
                } else {
                    stokInfo.classList.add('hidden');
                    stokWarning.classList.add('hidden');
                }
            });

            // Handle jumlah input change
            jumlahInput.addEventListener('input', function() {
                validateStok();
                updatePerubahanStok();
            });

            function showStokInfo() {
                if (currentStokData) {
                    const stokDetails = document.getElementById('stok-details');
                    stokDetails.innerHTML = `
                        <div><strong>Penyimpanan:</strong> ${currentStokData.nama}</div>
                        <div><strong>Stok Tersedia:</strong> ${currentStokData.stok.toFixed(2)} ${currentStokData.satuan}</div>
                        <div><strong>Jumlah Saat Ini:</strong> ${originalJumlah.toFixed(2)} ${currentStokData.satuan}</div>
                        <div><strong>Total Stok (termasuk yang sedang dikelola):</strong> ${(currentStokData.stok + originalJumlah).toFixed(2)} ${currentStokData.satuan}</div>
                    `;
                    stokInfo.classList.remove('hidden');
                }
            }

            function validateStok() {
                const jumlah = parseFloat(jumlahInput.value) || 0;
                
                if (jumlah > 0 && currentStokData) {
                    // Total stok yang tersedia = stok di penyimpanan + jumlah yang sedang dikelola saat ini
                    const totalStokTersedia = currentStokData.stok + originalJumlah;
                    const warningText = document.getElementById('stok-warning-text');
                    
                    if (jumlah > totalStokTersedia) {
                        warningText.textContent = 
                            `Jumlah yang dikelola (${jumlah.toFixed(2)} ${currentStokData.satuan}) melebihi total stok tersedia (${totalStokTersedia.toFixed(2)} ${currentStokData.satuan}).`;
                        stokWarning.classList.remove('hidden');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else if (jumlah > (totalStokTersedia * 0.8)) {
                        warningText.textContent = 
                            `Peringatan: Jumlah limbah akan menggunakan lebih dari 80% total stok tersedia.`;
                        stokWarning.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        stokWarning.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                } else {
                    stokWarning.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            function updatePerubahanStok() {
                const jumlahBaru = parseFloat(jumlahInput.value) || 0;
                const selisih = jumlahBaru - originalJumlah;
                
                document.getElementById('jumlah-baru-value').textContent = jumlahBaru.toFixed(2);
                document.getElementById('selisih-value').textContent = selisih.toFixed(2);
                
                const dampakText = document.getElementById('dampak-text');
                const dampakStok = document.getElementById('dampak-stok');
                
                if (selisih > 0) {
                    dampakText.textContent = `Stok penyimpanan akan berkurang ${selisih.toFixed(2)} ${currentStokData?.satuan || '{{ $pengelolaanLimbah->satuan }}'}`;
                    dampakStok.className = 'text-xs mt-2 p-2 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded';
                } else if (selisih < 0) {
                    dampakText.textContent = `Stok penyimpanan akan bertambah ${Math.abs(selisih).toFixed(2)} ${currentStokData?.satuan || '{{ $pengelolaanLimbah->satuan }}'}`;
                    dampakStok.className = 'text-xs mt-2 p-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded';
                } else {
                    dampakText.textContent = 'Tidak ada perubahan stok';
                    dampakStok.className = 'text-xs mt-2 p-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded';
                }
            }

            // Initialize
            if (penyimpananSelect.value) {
                penyimpananSelect.dispatchEvent(new Event('change'));
            }

            // Initialize vendor section visibility
            if (jenisPengelolaanSelect.value === 'vendor_eksternal') {
                vendorSection.style.display = 'block';
                vendorSelect.required = true;
            }

            // Initialize perubahan stok
            updatePerubahanStok();
        });
    </script>
</x-app>
