@php
    $type = $notification->type;
    $reactionType = $notification->reaction_type;
    $fromUser = $notification->fromUser;
    $fromUserName = $fromUser->name ?? 'Someone';
    $fromUserAvatar = $fromUser->avatar_url ?? $notification->image_url ?? null;
    $contentSnippet = $notification->content_snippet;
    $isRead = (bool) $notification->is_read;
    $isVerified = $fromUser->is_verified ?? $fromUser->badge ?? false;

    $badgeType = ($type === 'reaction' && $reactionType) ? 'reaction_' . $reactionType : $type;
@endphp

<div x-data="{ 
        showDeleteConfirm: false, 
        showFollowConfirm: false, 
        swipeX: 0, 
        startX: 0, 
        isSwiping: false, 
        openReply: false,
        toast: { show: false, message: '', type: 'success' } 
     }" 
     x-on:show-toast.window="
        toast.message = $event.detail.message; 
        toast.type = $event.detail.type || 'success'; 
        toast.show = true; 
        setTimeout(() => toast.show = false, 3000);
     "
     x-on:play-notification-sound.window="new Audio('/sounds/notification.mp3').play()"
     class="relative overflow-hidden rounded-xl my-0.5">

    <!-- 🔔 Toast Notification Banner (Success / Error Message) -->
    <template x-teleport="body">
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-5 right-5 z-[200] flex items-center gap-2 px-4 py-2.5 rounded-xl shadow-xl text-white text-xs font-semibold"
             :class="{
                'bg-emerald-600': toast.type === 'success',
                'bg-red-600': toast.type === 'error',
                'bg-blue-600': toast.type === 'info'
             }"
             style="display: none;">
            <span x-text="toast.message"></span>
        </div>
    </template>

    <!-- Swipe Delete Background -->
    <div x-show="swipeX < 0" 
         x-cloak
         class="absolute inset-y-0 right-0 w-16 bg-red-600 flex items-center justify-center text-white z-0 rounded-r-xl">
        <button @click="showDeleteConfirm = true" type="button" class="w-full h-full flex items-center justify-center text-white active:scale-90 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>

    <!-- Notification Item Container -->
    <div @touchstart="startX = $event.touches[0].clientX; isSwiping = true"
         @touchmove="if (isSwiping) { 
            let diff = $event.touches[0].clientX - startX; 
            if (diff < 0 && diff > -100) swipeX = diff; 
            if (diff > 0) swipeX = 0;
         }"
         @touchend="isSwiping = false; if (swipeX < -40) swipeX = -64; else swipeX = 0;"
         :style="`transform: translateX(${swipeX}px)`"
         class="relative z-10 group flex flex-col px-3 py-2.5 transition-all duration-200 ease-out {{ !$isRead ? 'bg-rose-50 dark:bg-rose-950/40' : 'bg-white dark:bg-[#18191a] hover:bg-gray-100/80 dark:hover:bg-[#242526]' }}">
        
        <div class="flex items-center justify-between cursor-pointer" wire:click="openNotification">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                
                <!-- Profile Picture & Badge Icon -->
                <div class="relative flex-shrink-0">
                    @if($fromUserAvatar)
                        <img src="{{ $fromUserAvatar }}" class="w-12 h-12 rounded-full object-cover">
                    @else
                        <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-900/60 flex items-center justify-center font-bold text-rose-600 dark:text-rose-300 text-base">
                            {{ strtoupper(substr($fromUserName, 0, 2)) }}
                        </div>
                    @endif

                    <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full flex items-center justify-center border-2 border-white dark:border-[#18191a] text-white text-[10px] shadow-sm
                        {{ in_array($badgeType, ['like', 'reaction_like', 'share', 'follow']) ? 'bg-[#1877f2]' : '' }}
                        {{ $badgeType === 'friend_request_accepted' ? 'bg-emerald-500' : '' }}
                        {{ $badgeType === 'unfollow' ? 'bg-gray-500' : '' }}
                        {{ $badgeType === 'reaction_love' ? 'bg-[#f33e58]' : '' }}
                        {{ in_array($badgeType, ['reaction_care', 'reaction_haha', 'reaction_wow', 'reaction_sad', 'reaction_angry']) ? 'bg-[#f7b125]' : '' }}
                        {{ in_array($badgeType, ['reply', 'comment_reply', 'reply_post_owner']) ? 'bg-[#10b981]' : '' }}
                        {{ $badgeType === 'comment' ? 'bg-[#2ea343]' : '' }}">
                        
                        @switch($badgeType)
                            @case('follow')
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6; 9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                @break
                            @case('friend_request_accepted')
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                @break
                            @case('unfollow')
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M14 8c0-2.21-1.79-4-4-4S6 5.79 6 8s1.79 4 4 4 4-1.79 4-4zm3 2v-2h-2V6h2V4h2v2h2v2h-2v2h-2zM10 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                @break
                            @case('reaction_love') ❤️ @break
                            @case('reaction_care') 🥰 @break
                            @case('reaction_haha') 😆 @break
                            @case('reaction_wow') 😮 @break
                            @case('reaction_sad') 😢 @break
                            @case('reaction_angry') 😡 @break
                            @case('comment')
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2z"/></svg>
                                @break
                            @default
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        @endswitch
                    </div>
                </div>

                <!-- Content Rendering -->
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] sm:text-sm leading-snug">
                        <span class="inline-flex items-center gap-1 font-bold text-black dark:text-white hover:underline">
                            {{ $fromUserName }}
                            @if($isVerified)
                                <svg class="w-4 h-4 text-blue-500 fill-current inline-block" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                            @endif
                        </span>

                        @php
                            $actionText = match($badgeType) {
                                'follow' => 'started following you.',
                                'unfollow' => 'unfollowed you.',
                                'friend_request' => 'sent you a friend request.',
                                'friend_request_accepted' => 'accepted your friend request.',
                                'reaction_haha' => 'reacted 😆 to your post.',
                                'reaction_care' => 'reacted 🥰 to your post.',
                                'reaction_wow' => 'reacted 😮 to your post.',
                                'reaction_love' => 'reacted ❤️ to your post.',
                                'reaction_sad' => 'reacted 😢 to your post.',
                                'reaction_angry' => 'reacted 😡 to your post.',
                                'reaction_like' => 'reacted 👍 to your post.',
                                'share' => 'shared your post.',
                                'reply', 'comment_reply' => 'replied to your comment:',
                                'reply_post_owner' => 'replied to a comment on your post:',
                                'comment' => 'commented on your post:',
                                default => 'reacted to your post.'
                            };
                        @endphp

                        <span class="{{ !$isRead ? 'font-semibold text-gray-900 dark:text-gray-100' : 'font-normal text-gray-600 dark:text-gray-300' }}"> 
                            {{ $actionText }}
                        </span>

                        @if(in_array($type, ['reply', 'comment_reply', 'reply_post_owner', 'comment']) && $contentSnippet)
                            <span class="block text-xs truncate {{ !$isRead ? 'font-medium text-gray-700 dark:text-gray-300' : 'font-normal text-gray-500 dark:text-gray-400' }}">
                                "{{ $contentSnippet }}"
                            </span>
                        @endif
                    </p>

                    <span class="text-[11px] mt-0.5 block {{ !$isRead ? 'font-bold text-rose-600 dark:text-rose-400' : 'font-normal text-gray-400 dark:text-gray-500' }}">
                        {{ $notification->created_at ? $notification->created_at->diffForHumans(null, true, true) : '1d' }}
                    </span>
                </div>

            </div>

            <!-- Unread Pink Dot & Delete Trigger Button -->
            <div class="flex items-center gap-2 ml-2 flex-shrink-0" @click.stop>
                @if(!$isRead)
                    <span class="w-3 h-3 rounded-full bg-rose-500 dark:bg-rose-400 block ring-4 ring-rose-200/70 dark:ring-rose-900/50 shadow-sm animate-pulse"></span>
                @endif

                <button @click.stop="showDeleteConfirm = true" 
                        title="Delete notification"
                        type="button"
                        class="p-1.5 rounded-full hover:bg-rose-100 dark:hover:bg-[#3a3b3c] text-gray-400 hover:text-red-500 transition active:scale-90">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Direct Action Buttons UI -->
        @if(in_array($type, ['follow', 'friend_request', 'comment', 'reply', 'comment_reply', 'reply_post_owner']))
            <div class="flex items-center gap-2 mt-2 pl-15" @click.stop>
                
                @if($type === 'friend_request')
                    <button wire:click.stop="acceptFriendRequest" type="button" class="px-3 py-1 bg-[#2d88ff] hover:bg-[#1b6cd8] text-white text-xs font-semibold rounded-lg transition active:scale-95 shadow-sm">
                        Confirm
                    </button>
                    <button wire:click.stop="declineFriendRequest" type="button" class="px-3 py-1 bg-gray-200 dark:bg-[#3a3b3c] hover:bg-gray-300 dark:hover:bg-[#4e4f50] text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-lg transition active:scale-95">
                        Delete
                    </button>

                @elseif($type === 'follow')
                    <button @click.stop="showFollowConfirm = true" type="button" 
                            class="px-3 py-1 text-xs font-semibold rounded-lg transition active:scale-95 flex items-center gap-1 shadow-sm {{ $isFollowing ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-[#2d88ff] hover:bg-[#1b6cd8] text-white' }}">
                        @if($isFollowing)
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Following</span>
                        @else
                            <span>Follow Back</span>
                        @endif
                    </button>

                @elseif(in_array($type, ['comment', 'reply', 'comment_reply', 'reply_post_owner']) && $notification->post_id)
                    <div class="w-full">
                        <button @click.stop="openReply = !openReply" type="button" class="text-xs text-[#2d88ff] hover:underline font-semibold">
                            Quick Reply
                        </button>
                        <div x-show="openReply" x-cloak class="mt-2 flex items-center gap-2">
                            <input type="text" wire:model.defer="quickReplyText" placeholder="Write a reply..." 
                                   class="flex-1 bg-gray-100 dark:bg-[#242526] text-xs text-gray-900 dark:text-white px-3 py-1.5 rounded-lg border border-gray-300 dark:border-[#3a3b3c] focus:outline-none focus:border-[#2d88ff]">
                            <button wire:click.stop="sendQuickReply" @click="openReply = false" type="button" 
                                    class="px-3 py-1.5 bg-[#2d88ff] hover:bg-[#1b6cd8] text-white text-xs font-semibold rounded-lg transition active:scale-95">
                                Send
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        @endif

    </div>

    <!-- Modals -->
    <template x-teleport="body">
        <div x-show="showFollowConfirm" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
             style="display: none;"
             @click.self="showFollowConfirm = false">
            <div class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-2xl shadow-2xl p-5 text-center">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                    {{ $isFollowing ? 'Unfollow User?' : 'Follow User?' }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">
                    {{ $isFollowing ? "{$fromUserName} ကို Unfollow ပြုလုပ်ရန် သေချာပါသလား။" : "{$fromUserName} ကို Follow ပြုလုပ်ရန် သေချာပါသလား။" }}
                </p>
                <div class="flex items-center gap-2">
                    <button @click="showFollowConfirm = false" type="button" class="flex-1 py-2 bg-gray-100 dark:bg-[#3a3b3c] text-gray-800 dark:text-gray-200 font-semibold text-xs rounded-xl">Cancel</button>
                    <button wire:click="toggleFollow" @click="showFollowConfirm = false" type="button" class="flex-1 py-2 bg-[#2d88ff] hover:bg-[#1b6cd8] text-white font-semibold text-xs rounded-xl shadow-md">Confirm</button>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="showDeleteConfirm" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-0"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
             style="display: none;"
             @click.self="showDeleteConfirm = false">
            <div class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-2xl shadow-2xl p-5 text-center">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Delete Notification?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">ဒီ နိုတီဖီကေးရှင်းကို ဖျက်ပစ်ဖို့ သေချာပါသလား။</p>
                <div class="flex items-center gap-2">
                    <button @click="showDeleteConfirm = false" type="button" class="flex-1 py-2 bg-gray-100 dark:bg-[#3a3b3c] text-gray-800 dark:text-gray-200 font-semibold text-xs rounded-xl">Cancel</button>
                    <button wire:click="deleteNotification" @click="showDeleteConfirm = false" type="button" class="flex-1 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl shadow-md">Remove</button>
                </div>
            </div>
        </div>
    </template>

</div>
