<x-app>
    <x-slot:title>Edit User</x-slot:title>

    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Edit User
        </h2>

        <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Nama</span>
                    <x-input type="text" name="name" value="{{ old('name', $user->name) }}" required />
                    @error('name')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Email</span>
                    <x-input type="email" name="email" value="{{ old('email', $user->email) }}" required />
                    @error('email')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Role</span>
                    <x-select name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator
                        </option>
                        <option value="perusahaan" {{ old('role', $user->role) == 'perusahaan' ? 'selected' : '' }}>
                            Perusahaan</option>
                    </x-select>
                    @error('role')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Status</span>
                    <x-select name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif
                        </option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                            Nonaktif</option>
                    </x-select>
                    @error('status')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Catatan</span>
                    <x-textarea name="notes" rows="3">{{ old('notes', $user->notes) }}</x-textarea>
                    @error('notes')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex items-center gap-4">
                    <x-button type="submit">
                        Simpan
                    </x-button>
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-300 border border-transparent rounded-lg active:bg-gray-300 hover:bg-gray-400 focus:outline-none focus:shadow-outline-gray">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app>