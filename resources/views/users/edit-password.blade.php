<x-app>
    <x-slot:title>Edit Password User</x-slot:title>

    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Edit Password User
        </h2>

        <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                    User: {{ $user->name }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
            </div>

            <form action="{{ route('users.password.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Password Baru</span>
                    <input type="password" name="password"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                    @error('password')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Konfirmasi Password Baru</span>
                    <input type="password" name="password_confirmation"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                    @error('password_confirmation')
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