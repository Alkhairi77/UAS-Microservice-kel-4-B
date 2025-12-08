<x-app>
    <x-slot:title>
        Edit Laporan Harian Limbah
    </x-slot:title>
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Edit Laporan Harian
        </h2>

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('laporan-harian.update', $laporanHarian) }}">
                @csrf
                @method('PUT')

                <!-- Tanggal -->
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Tanggal Laporan</span>
                    <x-input 
                        type="date" 
                        name="tanggal" 
                        value="{{ old('tanggal', $laporanHarian->tanggal->format('Y-m-d')) }}" 
                        required 
                    />
                    @error('tanggal')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Jenis Limbah -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Jenis Limbah</span>
                    <select name="jenis_limbah_id" id="jenis_limbah_id" 
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-gray form-input" 
                            required>
                        <option value="">Pilih Jenis Limbah</option>
                        @foreach($jenisLimbahs as $jenisLimbah)
                            <option value="{{ $jenisLimbah->id }}" 
                                    data-satuan="{{ $jenisLimbah->satuan_default }}"
                                    {{ old('jenis_limbah_id', $laporanHarian->jenis_limbah_id) == $jenisLimbah->id ? 'selected' : '' }}>
                                {{ $jenisLimbah->nama }} ({{ $jenisLimbah->kode_limbah }})
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_limbah_id')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Penyimpanan -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Penyimpanan</span>
                    <select name="penyimpanan_id" id="penyimpanan_id" 
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-gray form-input" 
                            required>
                        <option value="">Pilih Penyimpanan</option>
                        @foreach($penyimpanans as $penyimpanan)
                            @if($penyimpanan->jenis_limbah_id == $laporanHarian->jenis_limbah_id)
                                <option value="{{ $penyimpanan->id }}" 
                                        {{ old('penyimpanan_id', $laporanHarian->penyimpanan_id) == $penyimpanan->id ? 'selected' : '' }}>
                                    {{ $penyimpanan->nama_penyimpanan }} - {{ $penyimpanan->lokasi }}
                                    (Sisa: {{ number_format($penyimpanan->kapasitas_maksimal - $penyimpanan->kapasitas_terpakai, 2) }} {{ $penyimpanan->jenisLimbah->satuan_default }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('penyimpanan_id')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Jumlah -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Jumlah</span>
                    <x-input 
                        type="number" 
                        name="jumlah" 
                        step="0.01" 
                        min="0.01"
                        value="{{ old('jumlah', $laporanHarian->jumlah) }}" 
                        required 
                    />
                    @error('jumlah')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Satuan -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Satuan</span>
                    <x-input 
                        type="text" 
                        name="satuan" 
                        id="satuan"
                        value="{{ old('satuan', $laporanHarian->satuan) }}" 
                        readonly
                        required 
                    />
                    @error('satuan')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Keterangan -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Keterangan</span>
                    <x-textarea name="keterangan" rows="3">{{ old('keterangan', $laporanHarian->keterangan) }}</x-textarea>
                    @error('keterangan')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex items-center justify-end mt-8 space-x-3">
                    <x-button variant="secondary" href="{{ route('laporan-harian.index') }}">
                        Batal
                    </x-button>
                    <button type="submit" name="action" value="draft"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                        Simpan Draft
                    </button>
                    
                    <button type="submit" name="action" value="submit"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Simpan & Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisLimbahSelect = document.getElementById('jenis_limbah_id');
            const penyimpananSelect = document.getElementById('penyimpanan_id');
            const satuanInput = document.getElementById('satuan');

            // Set initial satuan
            if (jenisLimbahSelect.value) {
                const selectedOption = jenisLimbahSelect.options[jenisLimbahSelect.selectedIndex];
                if (selectedOption.dataset.satuan) {
                    satuanInput.value = selectedOption.dataset.satuan;
                }
            }

            // Handle jenis limbah change
            jenisLimbahSelect.addEventListener('change', function() {
                const jenisLimbahId = this.value;
                
                // Clear penyimpanan options
                penyimpananSelect.innerHTML = '<option value="">Pilih Penyimpanan</option>';
                
                // Set satuan
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.satuan) {
                    satuanInput.value = selectedOption.dataset.satuan;
                } else {
                    satuanInput.value = '';
                }

                if (jenisLimbahId) {
                    // Fetch penyimpanan via AJAX
                    fetch(`{{ route('api.penyimpanan-by-jenis-limbah') }}?jenis_limbah_id=${jenisLimbahId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(penyimpanan => {
                                const option = document.createElement('option');
                                option.value = penyimpanan.id;
                                option.textContent = `${penyimpanan.nama_penyimpanan} - ${penyimpanan.lokasi} (Sisa: ${penyimpanan.sisa_kapasitas.toFixed(2)} ${penyimpanan.satuan})`;
                                penyimpananSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>
</x-app>
