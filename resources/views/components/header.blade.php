<!-- Tambahkan di bagian head -->
<script src="{{ asset('resources/js/notifications.js') }}" defer></script>
<header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-green-600 dark:text-green-400">
        <!-- Mobile hamburger -->
        <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-green"
            @click="toggleSideMenu" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>

        <!-- Desktop sidebar toggle -->
        <button class="hidden md:block p-1 rounded-md focus:outline-none focus:shadow-outline-green"
            @click="toggleSidebar" aria-label="Toggle Sidebar">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>

        <!-- Search input -->
        <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl mr-6 focus-within:text-green-500">
                <div class="absolute inset-y-0 flex items-center pl-2">
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input
                    class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-green-300 focus:outline-none focus:shadow-outline-green form-input"
                    type="text" placeholder="Cari laporan, jenis limbah..." aria-label="Search" />
            </div>
        </div>

        <ul class="flex items-center flex-shrink-0 space-x-6">
            <!-- Theme toggler -->
            {{-- <li class="flex">
                <button id="theme-toggle" class="rounded-md focus:outline-none focus:shadow-outline-green"
                    aria-label="Toggle color mode"> --}}
                    <!-- Sun icon (light mode) -->
                    {{-- <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg> --}}
                    <!-- Moon icon (dark mode) -->
                    {{-- <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </li> --}}

            <!-- Notifications menu -->
            <li class="relative" x-data="{ open: false }">
                <div x-data="{ isNotificationMenuOpen: false }">
                    <button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-green"
                    @click="isNotificationMenuOpen = !isNotificationMenuOpen; if(isNotificationMenuOpen) { $dispatch('alpine:notification-dropdown-open') }"
                    @keydown.escape="isNotificationMenuOpen = false"
                    aria-label="Notifications"
                    aria-haspopup="true">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z">
                        </path>
                    </svg>
                    <!-- Notification badge -->
                    <span id="notification-badge"
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full notification-badge hidden">
                        0
                    </span>
                </button>

                <template x-if="isNotificationMenuOpen">
                    <ul x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click.away="isNotificationMenuOpen = false"
                        @keydown.escape="isNotificationMenuOpen = false"
                        class="absolute right-0 w-80 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                        aria-label="submenu">
                        
                        <!-- Notification Header -->
                        <li class="flex items-center justify-between px-2 py-1 border-b border-gray-100 dark:border-gray-600">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Notifikasi</span>
                            <div class="flex space-x-2">
                                <button onclick="markAllAsRead()" 
                                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Tandai semua sebagai dibaca">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                                <a href="{{ route('notifications.index') }}" 
                                   class="text-xs text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300"
                                   title="Lihat semua notifikasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        </li>
                        <!-- Notification Content -->
                        <li>
                            <div id="notification-dropdown" class="max-h-96 overflow-y-auto">
                                <!-- Notifications will be loaded here by JavaScript -->
                                <div class="px-4 py-8 text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mx-auto mb-2"></div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Memuat notifikasi...</p>
                                </div>
                            </div>
                        </li>

                        <!-- Footer -->
                        <li class="border-t border-gray-100 dark:border-gray-600 pt-2">
                            <a href="{{ route('notifications.index') }}"
                                class="flex items-center justify-center w-full px-2 py-2 text-sm font-medium text-purple-600 transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-purple-700 dark:text-purple-400 dark:hover:bg-gray-600 dark:hover:text-purple-300">
                                <span>Lihat Semua Notifikasi</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </template>
            </li>

            <div class="py-2 text-lg font-bold text-gray-700 dark:text-gray-300 border-b border-gray-600 dark:border-gray-600">
                {{ Auth::user()->name }}
            </div>

            <!-- Profile menu -->
            <li class="relative" x-data="{ isProfileMenuOpen: false }">
                <button class="align-middle rounded-full focus:shadow-outline-green focus:outline-none"
                    @click="isProfileMenuOpen = !isProfileMenuOpen" @keydown.escape="isProfileMenuOpen = false"
                    aria-label="Account" aria-haspopup="true">
                    @if(Auth::user() && Auth::user()->perusahaan && Auth::user()->perusahaan->logo)
                        <img src="{{ Storage::url(Auth::user()->perusahaan->logo) }}" alt="Logo"
                            class="object-cover w-8 h-8 rounded-full" aria-hidden="true">
                    @else
                        <img class="object-cover w-8 h-8 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                            alt="" aria-hidden="true">
                    @endif
                </button>

                <!-- Profile Dropdown -->
                <div x-show="isProfileMenuOpen" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95" @click.away="isProfileMenuOpen = false"
                    @keydown.escape="isProfileMenuOpen = false"
                    class="absolute right-0 z-50 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                    style="display: none;" aria-label="submenu">

                    <!-- User Info Header -->
                    <div
                        class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600">
                        {{ Auth::user()->name }}
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                    </div>

                    @if(Auth::user() && Auth::user()->perusahaan)
                        <div class="flex">
                            <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                href="{{ route('perusahaan.show', Auth::user()->perusahaan->id) }}">
                                <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span>Profile Perusahaan</span>
                            </a>
                        </div>
                    @endif

                    <div class="flex">
                        <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                            href="{{ route('profile.edit') }}">
                            <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Settings</span>
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="flex">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                                <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                <span>Log out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<style>
    /* Custom styles for notification dropdown */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Animation for notification badge */
    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .animate-pulse-slow {
        animation: pulse 2s infinite;
    }

    /* Scrollbar styling for notification list */
    .max-h-96::-webkit-scrollbar {
        width: 4px;
    }

    .max-h-96::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .max-h-96::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Dark mode scrollbar */
    .dark .max-h-96::-webkit-scrollbar-track {
        background: #374151;
    }

    .dark .max-h-96::-webkit-scrollbar-thumb {
        background: #6b7280;
    }

    .dark .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<script>
    // Initialize notifications when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Load notifications.js functions
        if (typeof fetchAndUpdateNotifications === 'function') {
            fetchAndUpdateNotifications();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        // Fungsi untuk update icon sesuai mode
        function updateThemeIcon() {
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            }
        }

        // Set icon saat load
        updateThemeIcon();

        // Pantau perubahan preferensi sistem
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('color-theme')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                updateThemeIcon();
            }
        });

        // Set mode awal sesuai preferensi user atau sistem
        if (localStorage.getItem('color-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        updateThemeIcon();

        // Toggle mode saat tombol diklik
        themeToggleBtn.addEventListener('click', function () {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
            updateThemeIcon();
        });
    });

</script>