<x-app>
    <x-slot:title>
        Tambah Laporan Harian
    </x-slot:title>
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Laporan Harian Limbah
            </h2>
            <x-button variant="secondary" href="{{ route('laporan-harian.index') }}">
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
            <form action="{{ route('laporan-harian.store') }}" method="POST" id="laporan-form">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <x-form-group label="Tanggal Laporan" name="tanggal" required>
                            <x-input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required />
                        </x-form-group>
                        
                        <!-- Jenis Limbah -->
                        <div class="mb-4">
                            <label for="jenis_limbah_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Limbah <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_limbah_id" id="jenis_limbah_select" required 
                                    class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-green-400 dark:focus:ring-green-400">
                                <option value="">Pilih Jenis Limbah</option>
                                @foreach($jenisLimbahs as $jenis)
                                    <option value="{{ $jenis->id }}" 
                                            data-satuan="{{ $jenis->satuan_default }}"
                                            {{ old('jenis_limbah_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }} ({{ $jenis->kode_limbah }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Penyimpanan -->
                        <div class="mb-4">
                            <label for="penyimpanan_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penyimpanan <span class="text-red-500">*</span>
                            </label>
                            <select name="penyimpanan_id" id="penyimpanan_select" required 
                                    class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-green-400 focus:ring focus:ring-green-400 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-green-400 dark:focus:ring-green-400">
                                <option value="">Pilih jenis limbah terlebih dahulu</option>
                            </select>
                            
                            <div id="penyimpanan-info" class="hidden mt-2 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <div class="flex justify-between mb-1">
                                        <span>Sisa Kapasitas:</span>
                                        <span id="sisa-kapasitas" class="font-medium">-</span>
                                    </div>
                                    <div class="w-full bg-blue-200 rounded-full h-2">
                                        <div id="kapasitas-bar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs mt-1">
                                        <span id="status-kapasitas">-</span>
                                        <span id="persentase-kapasitas">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <x-form-group label="Jumlah" name="jumlah" required>
                                <x-input type="number" id="jumlah" name="jumlah" step="0.01" min="0.01" 
                                         value="{{ old('jumlah') }}" placeholder="0.00" required />
                            </x-form-group>

                            <!-- Satuan -->
                            <div class="mb-4">
                                <label for="satuan_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Satuan
                                </label>
                                <input type="text" name="satuan" id="satuan_display" readonly placeholder="Pilih jenis limbah" 
                                       class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" />
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Keterangan" name="keterangan">
                            <x-textarea name="keterangan" rows="6" 
                                        placeholder="Catatan tambahan tentang laporan ini...">{{ old('keterangan') }}</x-textarea>
                        </x-form-group>

                        <!-- Info Jenis Limbah -->
                        <div id="jenis-limbah-info" class="hidden p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                            <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">Informasi Jenis Limbah</h4>
                            <div id="limbah-details" class="text-sm text-green-700 dark:text-green-300 space-y-1">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Peringatan Kapasitas -->
                        <div id="kapasitas-warning" class="hidden p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-yellow-800 dark:text-yellow-200">Peringatan Kapasitas</h4>
                                    <p id="kapasitas-warning-text" class="text-sm text-yellow-700 dark:text-yellow-300 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 space-x-3">
                    <x-button variant="secondary" href="{{ route('laporan-harian.index') }}">
                        Batal
                    </x-button>
                    <button type="submit" name="action" value="draft"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                        Simpan Draft
                    </button>
                    <button type="submit" name="action" value="submit" id="submit-btn"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Submit Laporan
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Get elements with null checking
    const jenisLimbahSelect = document.getElementById('jenis_limbah_select');
    const penyimpananSelect = document.getElementById('penyimpanan_select');
    const satuanDisplay = document.getElementById('satuan_display');
    const penyimpananInfo = document.getElementById('penyimpanan-info');
    const jenisLimbahInfo = document.getElementById('jenis-limbah-info');
    const limbahDetails = document.getElementById('limbah-details');
    const jumlahInput = document.querySelector('input[name="jumlah"]');
    const kapasitasWarning = document.getElementById('kapasitas-warning');
    const submitBtn = document.getElementById('submit-btn');
    const debugStatus = document.getElementById('debug-status');

    // Check if all required elements exist
    console.log('Element check:');
    console.log('jenisLimbahSelect:', jenisLimbahSelect);
    console.log('penyimpananSelect:', penyimpananSelect);
    console.log('satuanDisplay:', satuanDisplay);
    console.log('jumlahInput:', jumlahInput);
    console.log('submitBtn:', submitBtn);

    // Exit if critical elements don't exist
    if (!jenisLimbahSelect || !penyimpananSelect || !satuanDisplay) {
        console.error('Critical elements not found!');
        return;
    }

    let currentPenyimpananData = null;

    // Handle jenis limbah change
    jenisLimbahSelect.addEventListener('change', function() {
        console.log('Jenis limbah changed to:', this.value);
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const satuan = selectedOption.dataset.satuan;
            satuanDisplay.value = satuan;
            
            // Show jenis limbah info
            showJenisLimbahInfo(selectedOption);
            
            // Load penyimpanan options
            loadPenyimpananOptions(selectedOption.value);
        } else {
            resetForm();
        }
    });

    // Handle penyimpanan change
    penyimpananSelect.addEventListener('change', function() {
        console.log('Penyimpanan changed to:', this.value);
        if (this.value && currentPenyimpananData) {
            const selected = currentPenyimpananData.find(p => p.id == this.value);
            if (selected) {
                updatePenyimpananInfo(selected);
                checkKapasitas();
            }
        } else {
            if (penyimpananInfo) penyimpananInfo.classList.add('hidden');
            if (kapasitasWarning) kapasitasWarning.classList.add('hidden');
        }
    });

    // Handle jumlah change
    if (jumlahInput) {
        jumlahInput.addEventListener('input', checkKapasitas);
    }

    // Show jenis limbah info
    function showJenisLimbahInfo(selectedOption) {
        if (!jenisLimbahInfo || !limbahDetails) return;
        
        const jenisLimbahId = selectedOption.value;
        const nama = selectedOption.textContent;
        
        limbahDetails.innerHTML = `
            <div><strong>Nama:</strong> ${nama}</div>
            <div><strong>Satuan:</strong> ${selectedOption.dataset.satuan}</div>
        `;
        
        jenisLimbahInfo.classList.remove('hidden');
    }

    // Load penyimpanan options via AJAX
    function loadPenyimpananOptions(jenisLimbahId) {
        console.log('=== Loading penyimpanan for jenis limbah ID:', jenisLimbahId, '===');
        
        // Update debug status
        if (debugStatus) {
            debugStatus.textContent = 'Memuat data penyimpanan...';
        }
        
        // Reset dropdown
        penyimpananSelect.innerHTML = '<option value="">Loading...</option>';
        penyimpananSelect.disabled = true;

        // Construct URL
        const url = `{{ route('api.penyimpanan-by-jenis-limbah') }}?jenis_limbah_id=${jenisLimbahId}`;
        console.log('Fetching URL:', url);

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('=== Received data ===');
            console.log('Data type:', typeof data);
            console.log('Data length:', Array.isArray(data) ? data.length : 'Not array');
            console.log('Full data:', data);
            
            // Store data
            currentPenyimpananData = data;
            
            // Enable dropdown
            penyimpananSelect.disabled = false;
            
            // Clear dropdown
            penyimpananSelect.innerHTML = '';
            
            // Add default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Pilih Penyimpanan';
            penyimpananSelect.appendChild(defaultOption);
            
            // Check if data exists and is array
            if (!Array.isArray(data) || data.length === 0) {
                const noDataOption = document.createElement('option');
                noDataOption.value = '';
                noDataOption.textContent = 'Tidak ada penyimpanan tersedia untuk jenis limbah ini';
                penyimpananSelect.appendChild(noDataOption);
                
                if (debugStatus) {
                    debugStatus.textContent = 'Tidak ada penyimpanan ditemukan';
                }
                console.log('No penyimpanan found for this jenis limbah');
            } else {
                // Add options
                data.forEach((penyimpanan, index) => {
                    console.log(`Adding option ${index + 1}:`, penyimpanan);
                    
                    const option = document.createElement('option');
                    option.value = penyimpanan.id;
                    option.textContent = `${penyimpanan.nama_penyimpanan} - ${penyimpanan.lokasi} (Sisa: ${parseFloat(penyimpanan.sisa_kapasitas || 0).toFixed(2)} ${penyimpanan.satuan})`;
                    
                    // Check if this was previously selected
                    const oldValue = {{ old('penyimpanan_id') ?: 'null' }};
                    if (oldValue && oldValue == penyimpanan.id) {
                        option.selected = true;
                    }
                    
                    penyimpananSelect.appendChild(option);
                });
                
                if (debugStatus) {
                    debugStatus.textContent = `${data.length} penyimpanan ditemukan`;
                }
                console.log(`Successfully added ${data.length} penyimpanan options`);
            }
            
            // Trigger change if there's old value
            const oldValue = {{ old('penyimpanan_id') ?: 'null' }};
            if (oldValue) {
                console.log('Triggering change for old value:', oldValue);
                penyimpananSelect.dispatchEvent(new Event('change'));
            }
        })
        .catch(error => {
            console.error('=== Error loading penyimpanan ===');
            console.error('Error:', error);
            console.error('Error message:', error.message);
            
            if (debugStatus) {
                debugStatus.textContent = 'Error: ' + error.message;
            }
            
            penyimpananSelect.disabled = false;
            penyimpananSelect.innerHTML = '';
            
            const errorOption = document.createElement('option');
            errorOption.value = '';
            errorOption.textContent = 'Error loading data - ' + error.message;
            penyimpananSelect.appendChild(errorOption);
        });
    }

    // Update penyimpanan info display
    function updatePenyimpananInfo(penyimpanan) {
        if (!penyimpananInfo) return;
        
        console.log('Updating penyimpanan info:', penyimpanan);
        
        const sisaKapasitas = parseFloat(penyimpanan.sisa_kapasitas || 0);
        const persentaseKapasitas = penyimpanan.persentase_kapasitas || 0;
        
        const sisaKapasitasEl = document.getElementById('sisa-kapasitas');
        const kapasitasBarEl = document.getElementById('kapasitas-bar');
        const statusKapasitasEl = document.getElementById('status-kapasitas');
        const persentaseKapasitasEl = document.getElementById('persentase-kapasitas');
        
        if (sisaKapasitasEl) {
            sisaKapasitasEl.textContent = `${sisaKapasitas.toFixed(2)} ${penyimpanan.satuan}`;
        }
        
        // Determine color based on remaining capacity percentage
        let colorClass = 'bg-green-600';
        let statusText = 'Kapasitas Tersedia';
        
        if (persentaseKapasitas >= 90) {
            colorClass = 'bg-red-600';
            statusText = 'Kapasitas Hampir Penuh';
        } else if (persentaseKapasitas >= 70) {
            colorClass = 'bg-yellow-600';
            statusText = 'Kapasitas Terbatas';
        }
        
        if (kapasitasBarEl) {
            kapasitasBarEl.style.width = `${persentaseKapasitas}%`;
            kapasitasBarEl.className = `${colorClass} h-2 rounded-full`;
        }
        
        if (statusKapasitasEl) {
            statusKapasitasEl.textContent = statusText;
        }
        
        if (persentaseKapasitasEl) {
            persentaseKapasitasEl.textContent = `${persentaseKapasitas.toFixed(1)}%`;
        }
        
        penyimpananInfo.classList.remove('hidden');
    }

        // Check kapasitas and show warning
    function checkKapasitas() {
        if (!jumlahInput || !kapasitasWarning || !submitBtn) return;
        
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const selectedPenyimpanan = penyimpananSelect.value;
        
        if (jumlah > 0 && selectedPenyimpanan && currentPenyimpananData) {
            const penyimpanan = currentPenyimpananData.find(p => p.id == selectedPenyimpanan);
            
            if (penyimpanan) {
                const sisaKapasitas = parseFloat(penyimpanan.sisa_kapasitas || 0);
                const warningTextEl = document.getElementById('kapasitas-warning-text');
                
                if (jumlah > sisaKapasitas) {
                    if (warningTextEl) {
                        warningTextEl.textContent = 
                            `Jumlah limbah (${jumlah.toFixed(2)} ${penyimpanan.satuan}) melebihi sisa kapasitas penyimpanan (${sisaKapasitas.toFixed(2)} ${penyimpanan.satuan}).`;
                    }
                    kapasitasWarning.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else if (jumlah > (sisaKapasitas * 0.8)) {
                    if (warningTextEl) {
                        warningTextEl.textContent = 
                            `Peringatan: Jumlah limbah akan mengisi lebih dari 80% sisa kapasitas penyimpanan.`;
                    }
                    kapasitasWarning.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    kapasitasWarning.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        } else {
            kapasitasWarning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Reset form
    function resetForm() {
        console.log('Resetting form');
        satuanDisplay.value = '';
        if (penyimpananInfo) penyimpananInfo.classList.add('hidden');
        if (jenisLimbahInfo) jenisLimbahInfo.classList.add('hidden');
        if (kapasitasWarning) kapasitasWarning.classList.add('hidden');
        penyimpananSelect.innerHTML = '<option value="">Pilih jenis limbah terlebih dahulu</option>';
        penyimpananSelect.disabled = true;
        currentPenyimpananData = null;
        
        if (debugStatus) {
            debugStatus.textContent = 'Menunggu pilihan jenis limbah...';
        }
    }

    // Initialize if old values exist
    @if(old('jenis_limbah_id'))
        console.log('Initializing with old jenis_limbah_id:', {{ old('jenis_limbah_id') }});
        setTimeout(() => {
            jenisLimbahSelect.dispatchEvent(new Event('change'));
        }, 100);
    @endif
});
</script>


</x-app>
