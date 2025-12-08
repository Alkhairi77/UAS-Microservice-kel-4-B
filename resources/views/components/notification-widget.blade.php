<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Notifikasi Terbaru
        </h3>
        <a href="{{ route('notifications.index') }}" 
           class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
            Lihat Semua
        </a>
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications->take(5) as $notification)
                <div class="flex items-start space-x-3 p-3 rounded-lg {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900' : 'bg-gray-50 dark:bg-gray-700' }}">
                    <div class="w-2 h-2 bg-{{ $notification->type_color }}-500 rounded-full mt-2 {{ $notification->is_read ? 'opacity-30' : '' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                            {{ $notification->title }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ Str::limit($notification->message, 60) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if(!$notification->is_read)
                        <button onclick="markAsRead({{ $notification->id }})" 
                                class="text-xs text-blue-600 hover:text-blue-800">
                            ✓
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-13h5v13z"></path>
            </svg>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
        </div>
    @endif
</div>