<x-guest>
    <x-slot:title>
        Login
    </x-slot:title>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
        <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex flex-col overflow-y-auto md:flex-row">

                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                            Masuk ke Akun Anda
                        </h1>

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Email -->
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Email</span>
                                <x-input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Password with Show/Hide Toggle -->
                            <label class="block mt-4 text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Password</span>
                                <div class="relative">
                                    <x-input 
                                        type="password" 
                                        name="password" 
                                        id="password"
                                        required 
                                        autocomplete="current-password"
                                    />
                                    <button 
                                        type="button" 
                                        id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none"
                                        aria-label="Toggle password visibility"
                                    >
                                        <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg id="eyeIcon" class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between mt-4">
                                <label class="inline-flex items-center text-sm cursor-pointer">
                                    <input 
                                        type="checkbox"
                                        name="remember" 
                                        id="remember"
                                        value="1"
                                        {{ old('remember') ? 'checked' : '' }}
                                        class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                    />
                                    <span class="ml-2 text-gray-700 dark:text-gray-400 select-none">Ingat saya</span>
                                </label>

                                <!-- Link Lupa Password -->
                                <a href="{{ route('password.request') }}"
                                    class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline focus:outline-none focus:underline">
                                    Lupa password?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <x-button 
                                type="submit"
                                class="w-full "
                            >
                                <span class="flex items-center justify-center">
                                    Masuk
                                </span>
                            </x-button>
                        </form>

                        <!-- Register Link -->
                        <p class="mt-4 text-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Belum punya akun?</span>
                            <a class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline focus:outline-none focus:underline ml-1"
                                href="{{ route('register') }}">
                                Daftar sekarang
                            </a>
                        </p>
                        
                        
                    </div>
                </div>
                
                <!-- Right Side Image -->
                <div class="h-32 md:h-auto md:w-1/2">
                    <img aria-hidden="true" class="object-cover w-full h-full dark:hidden"
                        src="{{ asset('assets/img/login.jpg') }}" alt="Office" />
                    <img aria-hidden="true" class="hidden object-cover w-full h-full dark:block"
                        src="{{ asset('assets/img/login-office-dark.jpeg') }}" alt="Office" />
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Password Toggle and Remember Me -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Toggle Functionality
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (passwordInput && toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle icons
                    if (type === 'text') {
                        eyeIcon.classList.add('hidden');
                        eyeOffIcon.classList.remove('hidden');
                        toggleButton.setAttribute('aria-label', 'Hide password');
                    } else {
                        eyeIcon.classList.remove('hidden');
                        eyeOffIcon.classList.add('hidden');
                        toggleButton.setAttribute('aria-label', 'Show password');
                    }
                });
            }

            // Remember Me Functionality
            const rememberCheckbox = document.getElementById('remember');
            const emailInput = document.querySelector('input[name="email"]');
            
            // Load saved email if remember me was checked
            if (localStorage.getItem('rememberMe') === 'true') {
                const savedEmail = localStorage.getItem('savedEmail');
                if (savedEmail && emailInput) {
                    emailInput.value = savedEmail;
                    rememberCheckbox.checked = true;
                }
            }

            // Handle form submission
            const loginForm = document.querySelector('form');
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    if (rememberCheckbox.checked) {
                        localStorage.setItem('rememberMe', 'true');
                        localStorage.setItem('savedEmail', emailInput.value);
                    } else {
                        localStorage.removeItem('rememberMe');
                        localStorage.removeItem('savedEmail');
                    }
                });
            }

            // Handle remember checkbox change
            if (rememberCheckbox) {
                rememberCheckbox.addEventListener('change', function() {
                    if (!this.checked) {
                        localStorage.removeItem('rememberMe');
                        localStorage.removeItem('savedEmail');
                    }
                });
            }

            // Keyboard accessibility for password toggle
            toggleButton.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });

            // Auto-focus password field after email is filled
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    if (this.value && passwordInput) {
                        setTimeout(() => passwordInput.focus(), 100);
                    }
                });
            }

            // Show loading state on form submit
            loginForm.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                const buttonText = submitButton.querySelector('span');
                
                submitButton.disabled = true;
                buttonText.innerHTML = `
                    <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Memproses...
                `;
                
                // Re-enable button after 5 seconds (fallback)
                setTimeout(() => {
                    submitButton.disabled = false;
                    buttonText.innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 0a4 4 0 01-4 4H3a4 4 0 014-4z"></path>
                        </svg>
                        Masuk
                    `;
                }, 5000);
            });
        });
    </script>

    <!-- Additional Styles -->
    <style>
        /* Custom checkbox styling */
        .form-checkbox:checked {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        
        .form-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        /* Password toggle button hover effect */
        #togglePassword:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .dark #togglePassword:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Loading animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Focus styles for better accessibility */
        .form-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        /* Smooth transitions */
        .form-input, .form-checkbox, button {
            transition: all 0.2s ease-in-out;
        }
    </style>
</x-guest>
