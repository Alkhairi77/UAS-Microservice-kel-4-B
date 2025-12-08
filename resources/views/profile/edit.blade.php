<x-app>
    <x-slot:title>Pengaturan</x-slot:title>

    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Pengaturan Akun
        </h2>

        <div class="grid gap-6 mb-8">
            <!-- Update Profile Information -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app>