<x-app>
    <x-slot:title>
        Tambah Penyimpanan Limbah
    </x-slot:title>
    
    
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tambah Penyimpanan Limbah
            </h2>
            <x-button variant="secondary" href="{{ route('penyimpanan.index') }}">
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
            <form action="{{ route('penyimpanan.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- TAMBAH: Jenis Limbah -->
                        <x-form-group label="Jenis Limbah" name="jenis_limbah_id" required>
                            <x-select name="jenis_limbah_id" placeholder="Pilih Jenis Limbah" required>
                                @if(isset($jenisLimbahs) && $jenisLimbahs->count() > 0)
                                    @foreach($jenisLimbahs as $jenis)
                                        <option value="{{ $jenis->id }}" 
                                                data-satuan="{{ $jenis->satuan_default }}"
                                                {{ old('jenis_limbah_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }} ({{ $jenis->kode_limbah }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada jenis limbah tersedia</option>
                                @endif
                            </x-select>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih jenis limbah yang akan disimpan di tempat ini
                                @if(isset($jenisLimbahs))
                                    ({{ $jenisLimbahs->count() }} jenis tersedia)
                                @endif
                            </p>
                        </x-form-group>

                        <x-form-group label="Nama Penyimpanan" name="nama_penyimpanan" required>
                            <x-input name="nama_penyimpanan" value="{{ old('nama_penyimpanan') }}" placeholder="Contoh: Tangki Limbah B3 - 001" required />
                        </x-form-group>

                        <x-form-group label="Lokasi Penyimpanan" name="lokasi" required>
                            <x-input name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Gudang A, Lantai 2" required />
                        </x-form-group>

                        <x-form-group label="Jenis Penyimpanan" name="jenis_penyimpanan" required>
                            <x-select name="jenis_penyimpanan" :options="\App\Models\Penyimpanan::getJenisPenyimpananOptions()" value="{{ old('jenis_penyimpanan') }}" required />
                        </x-form-group>

                        <!-- UPDATE: Kapasitas dengan satuan dinamis -->
                        <div class="grid grid-cols-2 gap-4">
                            <x-form-group label="Kapasitas Maksimal" name="kapasitas_maksimal" required>
                                <x-input type="number" name="kapasitas_maksimal" value="{{ old('kapasitas_maksimal') }}" step="0.01" min="0" placeholder="0.00" required />
                            </x-form-group>

                            <x-form-group label="Satuan" name="satuan_display">
                                <x-input name="satuan_display" id="satuan_display" readonly placeholder="Pilih jenis limbah dulu" class="bg-gray-50" />
                                <p class="mt-1 text-xs text-gray-500">Satuan mengikuti jenis limbah yang dipilih</p>
                            </x-form-group>
                        </div>

                        <!-- HAPUS: Kapasitas terpakai di create form -->
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <x-form-group label="Kondisi Penyimpanan" name="kondisi" required>
                            <x-select name="kondisi" :options="\App\Models\Penyimpanan::getKondisiOptions()" value="{{ old('kondisi', 'baik') }}" required />
                        </x-form-group>

                        <x-form-group label="Status" name="status" required>
                            <x-select name="status" :options="\App\Models\Penyimpanan::getStatusOptions()" value="{{ old('status', 'aktif') }}" required />
                        </x-form-group>

                        <x-form-group label="Tanggal Pembuatan/Instalasi" name="tanggal_pembuatan" required>
                            <x-input type="date" name="tanggal_pembuatan" value="{{ old('tanggal_pembuatan') }}" max="{{ date('Y-m-d') }}" required />
                        </x-form-group>

                        <x-form-group label="Catatan" name="catatan">
                            <x-textarea name="catatan" rows="4" placeholder="Catatan tambahan tentang penyimpanan ini...">{{ old('catatan') }}</x-textarea>
                        </x-form-group>

                        <!-- TAMBAH: Info jenis limbah yang dipilih -->
                        <div id="jenis-limbah-info" class="hidden p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Informasi Jenis Limbah</h4>
                            <div id="limbah-details" class="text-sm text-blue-700 dark:text-blue-300">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 space-x-3">
                    <x-button variant="secondary" href="{{ route('penyimpanan.index') }}">
                        Batal
                    </x-button>
                    <x-button type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Penyimpanan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- TAMBAH: JavaScript untuk update satuan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisLimbahSelect = document.querySelector('select[name="jenis_limbah_id"]');
            const satuanDisplay = document.querySelector('input[name="satuan_display"]');
            const jenisLimbahInfo = document.getElementById('jenis-limbah-info');
            const limbahDetails = document.getElementById('limbah-details');

            if (jenisLimbahSelect && satuanDisplay) {
                jenisLimbahSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    
                    if (selectedOption.value) {
                        const satuan = selectedOption.dataset.satuan;
                        satuanDisplay.value = satuan;
                        
                        limbahDetails.innerHTML = `
                            <p><strong>Satuan:</strong> ${satuan}</p>
                            <p><strong>Kode:</strong> ${selectedOption.text.match(/\((.*?)\)/)?.[1] || '-'}</p>
                        `;
                        jenisLimbahInfo.classList.remove('hidden');
                    } else {
                        satuanDisplay.value = '';
                        jenisLimbahInfo.classList.add('hidden');
                    }
                });

                if (jenisLimbahSelect.value) {
                    jenisLimbahSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
</x-app>