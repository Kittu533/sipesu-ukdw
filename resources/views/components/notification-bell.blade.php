<button type="button" onclick="toggleNotificationDropdown()" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
    </svg>
    @if(isset($unreadCount) && $unreadCount > 0)
        <span id="notif-badge" class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform -translate-y-1/2 translate-x-1/2 bg-red-500 rounded-full min-w-[18px] text-center">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
    @endif
</button>

<!-- Notification Dropdown -->
<div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
        <span class="font-semibold text-gray-900">Notifikasi</span>
        @if(isset($unreadCount) && $unreadCount > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-700">Tandai semua dibaca</button>
            </form>
        @endif
    </div>
    <div class="max-h-96 overflow-y-auto">
        @if(isset($recentNotifications) && $recentNotifications->count() > 0)
            @foreach($recentNotifications as $notif)
                <a href="{{ $notif->link ? route('notifications.read', $notif->id_notifikasi) : '#' }}" 
                   class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0 transition-colors {{ !$notif->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            @if($notif->type === 'success')
                                <span class="w-2 h-2 mt-2 rounded-full bg-emerald-500 block"></span>
                            @elseif($notif->type === 'error')
                                <span class="w-2 h-2 mt-2 rounded-full bg-red-500 block"></span>
                            @elseif($notif->type === 'warning')
                                <span class="w-2 h-2 mt-2 rounded-full bg-yellow-500 block"></span>
                            @else
                                <span class="w-2 h-2 mt-2 rounded-full bg-blue-500 block"></span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $notif->judul }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notif->pesan }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->tgl_kirim->diffForHumans() }}</p>
                        </div>
                        @if(!$notif->is_read)
                            <span class="ml-2 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="text-sm">Tidak ada notifikasi</p>
            </div>
        @endif
    </div>
    <a href="{{ route('notifications.index') }}" class="block px-4 py-3 text-center text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-gray-50 hover:bg-gray-100 transition-colors">
        Lihat semua notifikasi
    </a>
</div>

<script>
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('hidden');
        
        // Close on click outside
        document.addEventListener('click', function handler(e) {
            if (!dropdown.contains(e.target) && !e.target.closest('[onclick*="toggleNotificationDropdown"]')) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', handler);
            }
        });
    }
</script>
