<!DOCTYPE html>
<html :class="{ 'dark': dark }" x-data="data()" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'EcoCycle-Naima Sustainability' }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="shorcut icon" href="assets/img/icon.jpeg">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>
    
    <!-- Notification Script -->
    <script src="resources/js/notifications.js" defer></script>
  </head>
  <body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen overflow-hidden" x-data="data()">
      <!-- Sidebar -->
      <x-sidebar />
      
      <!-- Main Content Area -->
      <div class="flex flex-col flex-1 overflow-hidden">
        <!-- Header -->
        <x-header />
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-800">
          <div class="container px-6 mx-auto py-6">
            {{ $slot }}
          </div>
        </main>
      </div>
    </div>
    
    <!-- Backdrop for mobile sidebar -->
    <div x-show="isSideMenuOpen" 
         x-transition:enter="transition ease-in-out duration-150"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in-out duration-150" 
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center md:hidden"
         @click="closeSideMenu">
    </div>
    <!-- Notification Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Additional Scripts -->
    <script>
        // Global notification functions
        window.showToast = function(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            toast.className = `${colors[type] || colors.info} text-white px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 max-w-sm`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, duration);
        };

        // Handle Laravel session flash messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif

        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif

        // Initialize notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh notifications every 30 seconds
            if (typeof fetchAndUpdateNotifications === 'function') {
                fetchAndUpdateNotifications();
                setInterval(fetchAndUpdateNotifications, 30000);
            }
        });
    </script>
    @stack('scripts')
  </body>
</html>
