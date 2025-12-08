// Auto refresh notifikasi setiap 30 detik

// Helper: fetch and update notifications (badge & dropdown)
function fetchAndUpdateNotifications() {
    fetch('/api/notifications')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateNotificationBadge(data.unread_count);
            updateNotificationDropdown(data.notifications);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

// Auto refresh notifikasi setiap 30 detik
setInterval(fetchAndUpdateNotifications, 30000);

// Fetch on open dropdown (Alpine.js event)
document.addEventListener('alpine:notification-dropdown-open', function() {
    fetchAndUpdateNotifications();
});

// Initial load when page loads
document.addEventListener('DOMContentLoaded', function() {
    fetchAndUpdateNotifications();
});

function updateNotificationBadge(count) {
    // Try to find badge by id or fallback to notification icon badge
    let badge = document.querySelector('#notification-badge');
    if (!badge) {
        badge = document.querySelector('.notification-badge');
    }
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'inline-flex';
            badge.classList.remove('hidden');
        } else {
            badge.style.display = 'none';
            badge.classList.add('hidden');
        }
    }
}

function updateNotificationDropdown(notifications) {
    const dropdown = document.querySelector('#notification-dropdown');
    if (!dropdown) return;

    let html = '';
    if (!notifications || notifications.length === 0) {
        html = `
            <div class="px-4 py-8 text-center">
                <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-13h5v13z"></path>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
            </div>
        `;
    } else {
        notifications.forEach(notification => {
            const typeColors = {
                'success': 'green',
                'info': 'blue', 
                'warning': 'yellow',
                'danger': 'red'
            };
            const color = typeColors[notification.type] || 'gray';
            const isUnread = !notification.read_at;
            
            html += `
                <div class="border-b border-gray-100 dark:border-gray-600 last:border-b-0 flex items-center group notification-item" data-id="${notification.id}">
                    <a href="${notification.action_url || '#'}" 
                       onclick="markAsRead(${notification.id}); return ${notification.action_url ? 'true' : 'false'};"
                       class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-150 flex-1 ${isUnread ? 'bg-blue-50 dark:bg-blue-900' : ''}">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-${color}-500 rounded-full mt-2 mr-3 ${isUnread ? '' : 'opacity-30'}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    ${notification.title}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    ${notification.message}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    ${formatTimeAgo(notification.created_at)}
                                </p>
                            </div>
                        </div>
                    </a>
                    <button onclick="deleteNotification(${notification.id}); event.stopPropagation();" 
                            title="Hapus" 
                            class="text-red-500 hover:text-red-700 p-2 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
        });
    }
    dropdown.innerHTML = html;
}

// Hapus notifikasi individual
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
            // Remove notification from dropdown immediately
            const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.remove();
            }
            
            // Update badge and dropdown
            fetchAndUpdateNotifications();
            
            // Show success message if on notifications page
            if (window.location.pathname.includes('/notifications')) {
                showNotificationMessage('Notifikasi berhasil dihapus', 'success');
            }
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
        showNotificationMessage('Gagal menghapus notifikasi', 'error');
    });
}

// Mark notification as read
function markAsRead(notificationId) {
    fetch(`/api/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
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
            // Update badge and dropdown after a short delay
            setTimeout(() => {
                fetchAndUpdateNotifications();
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

// Mark all notifications as read
function markAllAsRead() {
    if (!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) return;
    
    fetch('/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
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
            // Update badge and dropdown
            fetchAndUpdateNotifications();
            
            // Show success message if on notifications page
            if (window.location.pathname.includes('/notifications')) {
                showNotificationMessage('Semua notifikasi telah ditandai sebagai dibaca', 'success');
            }
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
        showNotificationMessage('Gagal menandai notifikasi', 'error');
    });
}

// Delete all notifications
function deleteAllNotifications() {
    if (!confirm('Hapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.')) return;
    
    fetch('/notifications/delete-all', {
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
            // Update badge and dropdown
            fetchAndUpdateNotifications();
            
            // Show success message and reload if on notifications page
            if (window.location.pathname.includes('/notifications')) {
                showNotificationMessage('Semua notifikasi berhasil dihapus', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        }
    })
    .catch(error => {
        console.error('Error deleting all notifications:', error);
        showNotificationMessage('Gagal menghapus semua notifikasi', 'error');
    });
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Baru saja';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;
    
    return date.toLocaleDateString('id-ID');
}

// Helper function to show notification messages
function showNotificationMessage(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    notification.className += ` ${bgColor} text-white`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}
