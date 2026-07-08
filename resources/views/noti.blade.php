<x-app-layout>
    <x-slot name="header">
        {{-- Empty --}}
    </x-slot>

    <x-user-header />
    <x-user.nav active="notifications" />
    
    <div style="background: #F0F2F5; min-height: 100vh; padding-bottom: 64px; padding-top: 12px;">
        <div style="max-width: 680px; margin: 0 auto;">
            
            {{-- Header --}}
            <div style="background: white; border-radius: 12px; padding: 16px 20px; margin-bottom: 12px;">
                <h2 style="font-size: 20px; font-weight: 700; margin: 0; color: #050505;">Notifications</h2>
            </div>

            @forelse($notifications as $notification)
                @php
                    $postId = $notification->post_id;
                    $fromUserName = $notification->fromUser->name ?? 'Someone';
                    $fromUserAvatar = $notification->fromUser->avatar ?? null;
                    $timeAgo = $notification->created_at->diffForHumans();
                    $isUnread = !$notification->is_read;
                    
                    // Get comment text if available
                    $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true) ?? [];
                    $commentText = $data['comment'] ?? '';
                    
                    // Set message based on type
                    if($notification->type == 'like') {
                        $message = 'liked your post';
                    } elseif($notification->type == 'comment') {
                        $message = 'commented: "' . \Str::limit($commentText, 40) . '"';
                    } elseif($notification->type == 'share') {
                        $message = 'shared your post';
                    } else {
                        $message = 'interacted with your post';
                    }
                @endphp

                {{-- Notification Item --}}
                <div class="notification-item" 
                     data-post-id="{{ $postId }}"
                     data-comment-id="{{ $notification->comment_id }}"
                     data-id="{{ $notification->id }}"
                     data-type="{{ $notification->type }}"
                     onclick="handleNotificationClick(this)"
                     style="background: white; border-radius: 12px; padding: 16px; margin-bottom: 8px; cursor: pointer; {{ $isUnread ? 'border-left: 4px solid #1877f2; background: #f0f7ff;' : '' }} transition: all 0.2s;">
                    
                    <div style="display: flex; align-items: center; gap: 12px;">
                        {{-- Avatar --}}
                        <div style="width: 52px; height: 52px; border-radius: 50%; overflow: hidden; flex-shrink: 0; position: relative;">
                            @if($fromUserAvatar)
                                <img src="{{ asset('storage/' . $fromUserAvatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1877f2, #45bd62); display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; font-weight: 600;">
                                    {{ substr($fromUserName, 0, 1) }}
                                </div>
                            @endif
                            
                            {{-- Type Badge --}}
                            <div style="position: absolute; bottom: -2px; right: -2px; width: 22px; height: 22px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                @if($notification->type == 'like')
                                    <span style="font-size: 12px;">👍</span>
                                @elseif($notification->type == 'comment')
                                    <span style="font-size: 12px;">💬</span>
                                @elseif($notification->type == 'share')
                                    <span style="font-size: 12px;">🔄</span>
                                @else
                                    <span style="font-size: 12px;">🔔</span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div style="flex: 1;">
                            <div style="font-size: 15px; color: #050505; line-height: 1.4; margin-bottom: 4px;">
                                <span style="font-weight: 600;">{{ $fromUserName }}</span> {{ $message }}
                            </div>
                            <div style="font-size: 13px; color: #65676b;">{{ $timeAgo }}</div>
                        </div>

                        {{-- Actions --}}
                        <div style="display: flex; gap: 8px;" onclick="event.stopPropagation()">
                            @if($isUnread)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        style="width: 36px; height: 36px; border-radius: 50%; border: none; background: #e4e6eb; color: #050505; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M6 12L2 8l1.4-1.4L6 9.2l6.6-6.6L14 4l-8 8z"/>
                                    </svg>
                                </button>
                            @endif
                            <button onclick="deleteNotification({{ $notification->id }})" 
                                    style="width: 36px; height: 36px; border-radius: 50%; border: none; background: #e4e6eb; color: #050505; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M5 3V2h2v1h2V2h2v1h2v2H3V3h2zm8 3v7a1 1 0 01-1 1H4a1 1 0 01-1-1V6h10z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div style="background: white; border-radius: 12px; padding: 60px 24px; text-align: center;">
                    <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.5;">🔔</div>
                    <h3 style="font-size: 20px; font-weight: 600; color: #050505; margin-bottom: 8px;">No notifications yet</h3>
                    <p style="font-size: 15px; color: #65676b;">When people interact with your posts, they'll appear here.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
    function handleNotificationClick(element) {
        const postId = element.getAttribute('data-post-id');
        const commentId = element.getAttribute('data-comment-id');
        const type = element.getAttribute('data-type');
        const id = element.getAttribute('data-id');
        
        console.log('Clicked:', {postId, commentId, type, id});
        
        if (!postId || postId === 'null' || postId === '0') {
            alert('Invalid post ID');
            return;
        }
        
        // Mark as read
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(() => {
            // Redirect to post
            let url = `/posts/${postId}`;
            if (type === 'comment' && commentId && commentId !== 'null') {
                url = `/posts/${postId}#comment-${commentId}`;
            }
            window.location.href = url;
        })
        .catch(() => {
            // Still redirect even if error
            let url = `/posts/${postId}`;
            if (type === 'comment' && commentId && commentId !== 'null') {
                url = `/posts/${postId}#comment-${commentId}`;
            }
            window.location.href = url;
        });
    }

    function markAsRead(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }

    function deleteNotification(id) {
        if (confirm('Delete this notification?')) {
            fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                location.reload();
            });
        }
    }
    </script>

    <style>
    .notification-item:hover {
        background: #f5f5f5 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    </style>
</x-app-layout>