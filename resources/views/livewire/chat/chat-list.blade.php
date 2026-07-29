<div class="w-full min-h-screen bg-white dark:bg-[#121212] text-gray-900 dark:text-white px-4 pt-3 pb-20 transition-colors">

    {{-- Top Header Bar --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-3">
            <button onclick="window.history.back()" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-800 dark:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </button>
            <h1 class="text-2xl font-bold tracking-tight">Chats</h1>
        </div>
        
        <div class="flex items-center gap-2">
            <button class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center text-gray-800 dark:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </button>
            <button class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center text-gray-800 dark:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="relative mb-3">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
        </svg>
        <input type="text" 
               wire:model.live.debounce.300ms="search" 
               placeholder="Search" 
               class="w-full bg-[#f0f2f5] dark:bg-[#242526] text-gray-900 dark:text-white text-[15px] rounded-full pl-10 pr-4 py-2 border-none outline-none placeholder-gray-500 dark:placeholder-gray-400">
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 mb-3">
        <button class="px-4 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-semibold text-sm">
            Inbox
        </button>
        <button class="px-4 py-1.5 rounded-full text-gray-600 dark:text-gray-400 font-medium text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            Communities
        </button>
    </div>

    {{-- Chat List --}}
    @if($chatUsers->count() > 0)
        <div class="space-y-1">
            @foreach($chatUsers as $chatUser)
                @php
                    $isUserOnline = in_array($chatUser->id, $onlineUserIds);
                @endphp

                <a href="{{ route('chat.show', $chatUser->id) }}" 
                   class="flex items-center gap-3.5 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800/60 active:bg-gray-200 dark:active:bg-gray-800 transition">
                    
                    {{-- Avatar with Online/Offline Status --}}
                    <div class="relative flex-shrink-0 w-12 h-12" style="width: 48px; height: 48px;">
                        <img src="{{ $chatUser->avatar_url }}" alt="{{ $chatUser->name }}" 
                             class="w-12 h-12 rounded-full object-cover" 
                             style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">

                        @if($isUserOnline)
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-[#121212] rounded-full"></span>
                        @else
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-gray-400 border-2 border-white dark:border-[#121212] rounded-full"></span>
                        @endif
                    </div>

                    {{-- Info Section --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="text-[15px] font-semibold text-gray-900 dark:text-white truncate leading-snug">
                            {{ $chatUser->name }}
                        </h4>
                        
                        <div class="flex items-center justify-between gap-1 text-[13px] text-gray-500 dark:text-gray-400">
                            <p class="truncate {{ $chatUser->unread_count > 0 ? 'text-gray-900 dark:text-white font-bold' : '' }}">
                                @if($isUserOnline)
                                    <span class="text-green-500 font-medium">Active now</span>
                                @else
                                    <span>
                                        {{ $chatUser->last_seen ? $chatUser->last_seen->diffForHumans(null, true, true) . ' ago' : '@' . ($chatUser->username ?? 'user') }}
                                    </span>
                                @endif
                            </p>

                            @if($chatUser->unread_count > 0)
                                <span class="w-2.5 h-2.5 bg-blue-600 rounded-full flex-shrink-0"></span>
                            @endif
                        </div>
                    </div>

                    {{-- Unread Count Badge --}}
                    @if($chatUser->unread_count > 0)
                        <span class="bg-blue-600 text-white text-[11px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center flex-shrink-0">
                            {{ $chatUser->unread_count }}
                        </span>
                    @endif

                </a>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-16 text-gray-400 text-sm gap-2">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span>No chats found</span>
        </div>
    @endif

</div>

{{-- 🔥 Reverb Presence Channel Listener Script --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        if (typeof window.Echo !== 'undefined') {
            window.Echo.join('online')
                .here((users) => {
                    Livewire.dispatch('updateOnlineUsers', { users: users });
                })
                .joining((user) => {
                    window.Echo.join('online').here((users) => {
                        Livewire.dispatch('updateOnlineUsers', { users: users });
                    });
                })
                .leaving((user) => {
                    window.Echo.join('online').here((users) => {
                        Livewire.dispatch('updateOnlineUsers', { users: users });
                    });
                })
                .error((error) => {
                    console.error('Presence Channel Error:', error);
                });
        }
    });
</script>