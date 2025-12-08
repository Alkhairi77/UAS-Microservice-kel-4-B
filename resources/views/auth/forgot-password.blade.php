<x-guest>
    <x-slot:title>Lupa Password - EcoCycle</x-slot:title>

    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
        <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <!-- Left side - Branding -->
                <div class="h-32 md:h-auto md:w-1/2">
                    <div class="relative h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <div class="absolute inset-0 bg-black opacity-20"></div>
                        <div class="relative text-center text-white p-8">
                            <img src="{{ asset('assets/img/logo2.png') }}" alt="EcoCycle" class="h-16 mx-auto mb-4">
                            <h1 class="text-3xl font-bold mb-2">Lupa Password?</h1>
                            <p class="text-green-100">
                                Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right side - Form -->
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                            Reset Password
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            Masukkan alamat email Anda dan kami akan mengirimkan link reset password.
                        </p>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800 border border-green-200" role="alert">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ session('status') }}
                                </div>
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        autocomplete="email" 
                                        required 
                                        autofocus
                                        value="{{ old('email') }}"
                                        placeholder="Masukkan email Anda"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500 @error('email') border-red-500 @enderror"
                                    />
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-4">
                                <button 
                                    type="submit" 
                                    class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Kirim Link Reset Password
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Ingat password Anda?
                                    <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200">
                                        Kembali ke Login
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest>
