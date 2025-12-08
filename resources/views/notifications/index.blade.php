<x-app>
    <x-slot:title>Notifikasi</x-slot:title>
    
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Notifikasi
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <button onclick="markAllAsRead()" 
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tandai Semua Dibaca
                    </button>
                @endif
                
                @if(auth()->user()->notifications()->count() > 0)
                    <button onclick="deleteAllNotifications()" 
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Semua
                    </button>
                @endif
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alert-container" class="mb-4"></div>

        @if($notifications->count() > 0)
            <div class="space-y-4" id="notifications-container">
                                @foreach($notifications as $notification)
                    <x-card class="notification-item" data-id="{{ $notification->id }}">
                        <div class="flex items-start justify-between p-4">
                            <div class="flex items-start flex-1">
                                <!-- Status Indicator -->
                                <div class="flex-shrink-0 mr-3">
                                    @php
                                        $typeColors = [
                                            'success' => 'green',
                                            'info' => 'blue',
                                            'warning' => 'yellow',
                                            'danger' => 'red'
                                        ];
                                        $color = $typeColors[$notification->type] ?? 'gray';
                                    @endphp
                                    <div class="w-3 h-3 bg-{{ $color }}-500 rounded-full {{ $notification->read_at ? 'opacity-30' : '' }}"></div>
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $notification->title }}
                                        </h3>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 flex-shrink-0">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        {{ $notification->message }}
                                    </p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" 
                                                   onclick="markAsRead({{ $notification->id }})"
                                                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    Lihat Detail
                                                </a>
                                            @endif
                                            
                                            @if(!$notification->read_at)
                                                <button onclick="markAsRead({{ $notification->id }})" 
                                                        class="text-xs text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                    Tandai Dibaca
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center space-x-1">
                                            @if(!$notification->read_at)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                    Baru
                                                </span>
                                            @endif
                                            
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300">
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex-shrink-0 ml-4">
                                <button onclick="deleteNotification({{ $notification->id }})" 
                                        title="Hapus Notifikasi"
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <x-card>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-13h5v13z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Tidak Ada Notifikasi
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Anda belum memiliki notifikasi apapun.
                    </p>
                </div>
            </x-card>
        @endif
    </div>

    <script src="{{ asset('js/notifications.js') }}"></script>
    <script>
        // Helper function to show alert messages
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alert-container');
            const alertColors = {
                'success': 'green',
                'error': 'red',
                'warning': 'yellow',
                'info': 'blue'
            };
            const color = alertColors[type] || 'blue';
            
            const alertHtml = `
                <div class="p-4 mb-4 text-sm text-${color}-700 bg-${color}-100 rounded-lg dark:bg-${color}-200 dark:text-${color}-800 alert-message" role="alert">
                    <div class="flex items-center justify-between">
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-${color}-700 hover:text-${color}-900 dark:text-${color}-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            alertContainer.innerHTML = alertHtml;
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                const alertMessage = document.querySelector('.alert-message');
                if (alertMessage) {
                    alertMessage.remove();
                }
            }, 5000);
        }

        // Override showNotificationMessage to use showAlert
        function showNotificationMessage(message, type = 'info') {
            showAlert(message, type);
        }

        // Individual notification deletion
        function deleteNotification(notificationId) {
            if (!confirm('Hapus notifikasi ini?')) return;
            
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove notification from page
                    const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.style.transition = 'all 0.3s ease';
                        notificationItem.style.opacity = '0';
                        notificationItem.style.transform = 'translateX(100%)';
                        
                        setTimeout(() => {
                            notificationItem.remove();
                            
                            // Check if no notifications left
                            const remainingNotifications = document.querySelectorAll('.notification-item');
                            if (remainingNotifications.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                    
                    showAlert('Notifikasi berhasil dihapus', 'success');
                    
                    // Update header notification badge
                    fetchAndUpdateNotifications();
                }
            })
            .catch(error => {
                console.error('Error deleting notification:', error);
                showAlert('Gagal menghapus notifikasi', 'error');
            });
        }
    </script>
</x-app>

