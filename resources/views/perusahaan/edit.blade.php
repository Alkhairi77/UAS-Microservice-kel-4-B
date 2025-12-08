<x-app>
    <x-slot:title>
        Edit Profil Perusahaan
    </x-slot:title>
    <div class="container max-w-2xl mx-auto py-8">
        <h2 class="mb-6 text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Profil Perusahaan</h2>

        <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('perusahaan.update', $perusahaan) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama Perusahaan --}}
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nama Perusahaan</span>
                    <input type="text" name="nama_perusahaan"
                        value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-green form-input"
                        required />
                    @error('nama_perusahaan')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Alamat --}}
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Alamat</span>
                    <textarea name="alamat" rows="3"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-green"
                        required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                    @error('alamat')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Nomor Telepon --}}
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nomor Telepon</span>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-green form-input"
                        required />
                    @error('no_telp')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Jenis Usaha --}}
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Jenis Usaha</span>
                    <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha', $perusahaan->jenis_usaha) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-green form-input"
                        required />
                    @error('jenis_usaha')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Nomor Registrasi --}}
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nomor Registrasi</span>
                    <input type="text" name="no_registrasi"
                        value="{{ old('no_registrasi', $perusahaan->no_registrasi) }}"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-green form-input"
                        required />
                    @error('no_registrasi')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Deskripsi --}}
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Deskripsi</span>
                    <textarea name="deskripsi" rows="4"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-green">{{ old('deskripsi', $perusahaan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Logo --}}
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Logo Perusahaan</span>
                    @if($perusahaan->logo)
                        <div class="mt-2 mb-2">
                            <img src="{{ asset('storage/' . $perusahaan->logo) }}"
                                alt="Logo {{ $perusahaan->nama_perusahaan }}" class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="logo"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-green-400 focus:outline-none focus:shadow-outline-green dark:text-gray-300 dark:focus:shadow-outline-green form-input"
                        accept="image/*" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG atau JPEG (Maks. 2MB). Kosongkan
                        jika tidak ingin mengubah logo.</p>
                    @error('logo')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex items-center justify-end mt-6 space-x-2">
                    <a href="{{ route('perusahaan.show', $perusahaan) }}"
                        class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-300 border border-transparent rounded-lg active:bg-gray-300 hover:bg-gray-400 focus:outline-none focus:shadow-outline-gray">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>