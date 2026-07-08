@props(['notifications' => [], 'unreadCount' => 0])

<div class="max-w-2xl mx-auto py-4 px-4">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Notifications</h1>
        @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator && $notifications->total() > 0)
            <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                Mark all as read
            </button>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="space-y-2" id="notifications-list">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-lg shadow p-4 flex items-start gap-3 {{ !$notification->is_read ? 'border-l-4 border-blue-500' : '' }}"
                 data-id="{{ $notification->id }}">
                
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xl">
                    @switch($notification->type)
                        @case('like')
                            👍
                            @break
                        @case('comment')
                            💬
                            @break
                        @case('share')
                            🔄
                            @break
                        @case('follow')
                            👤
                            @break
                        @default
                            🔔
                    @endswitch
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <p class="text-sm">
                        <span class="font-semibold">{{ $notification->fromUser->name ?? 'Someone' }}</span>
                        {{ $notification->message }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notification->time_ago }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    @if(!$notification->is_read)
                        <button onclick="markAsRead({{ $notification->id }})" 
                                class="text-xs text-blue-600 hover:text-blue-800">
                            Mark read
                        </button>
                    @endif
                    <button onclick="deleteNotification({{ $notification->id }})" 
                            class="text-xs text-red-600 hover:text-red-800">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-6xl mb-4">🔔</div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No notifications yet</h3>
                <p class="text-gray-500">When you get notifications, they'll appear here.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator && $notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const noti = document.querySelector(`[data-id="${id}"]`);
            if (noti) {
                noti.classList.remove('border-l-4', 'border-blue-500');
            }
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('[data-id]').forEach(noti => {
                noti.classList.remove('border-l-4', 'border-blue-500');
            });
        }
    });
}

function deleteNotification(id) {
    if (confirm('Delete this notification?')) {
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const noti = document.querySelector(`[data-id="${id}"]`);
                if (noti) {
                    noti.remove();
                }
            }
        });
    }
}
</script>