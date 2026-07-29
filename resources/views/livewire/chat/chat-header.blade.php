<div wire:poll.30s="checkOnlineStatus"
     x-data="{ 
        isOnline: @json($isUserOnline),
        deviceOnline: navigator.onLine 
     }"
     x-init="
        const updateOnlineStatus = () => {
            deviceOnline = navigator.onLine;
        };

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);

        const initEcho = () => {
            if (typeof Echo !== 'undefined') {
                Echo.join('chat')
                    .here((users) => {
                        isOnline = users.some(u => Number(u.id) === Number({{ $user->id }}));
                    })
                    .joining((user) => {
                        if (Number(user.id) === Number({{ $user->id }})) {
                            isOnline = true;
                        }
                    })
                    .leaving((user) => {
                        if (Number(user.id) === Number({{ $user->id }})) {
                            isOnline = false;
                        }
                    });
            } else {
                setTimeout(initEcho, 500);
            }
        };

        initEcho();
     "
     @sync-online-status.window="isOnline = $event.detail.online"
     class="h-[56px] min-h-[56px] w-full flex items-center justify-between px-3 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-[#121212] shrink-0 z-50">
    
    <div class="flex items-center gap-2">
        {{-- Back Button --}}
        <a href="{{ url()->previous() }}" 
           onclick="if (document.referrer && document.referrer !== location.href) { history.back(); return false; }"
           class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-blue-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
        </a>
        
        <a href="{{ route('profile.show', $user->id) }}" class="flex items-center gap-2.5 group">
            {{-- User Avatar & Online Badge --}}
            <div class="relative">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                     class="w-[38px] h-[38px] min-w-[38px] min-h-[38px] object-cover rounded-full">
                
                {{-- Online/Offline Dynamic Dot --}}
                <span x-show="isOnline && deviceOnline" class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-[#121212] rounded-full"></span>
                <span x-show="!isOnline || !deviceOnline" class="absolute bottom-0 right-0 w-3 h-3 bg-gray-400 border-2 border-white dark:border-[#121212] rounded-full"></span>
            </div>

            <div>
                <h4 class="font-bold text-[16px] leading-tight group-hover:underline">{{ $user->name }}</h4>
                <p class="text-[12px] text-gray-500 dark:text-gray-400">
                    <span x-show="isOnline && deviceOnline" class="text-green-500 font-semibold">Active now</span>
                    <span x-show="!isOnline || !deviceOnline">Active {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'recently' }}</span>
                </p>
            </div>
        </a>
    </div>

    <div class="flex items-center gap-3 text-blue-600">
        {{-- Voice Call Button --}}
        <button type="button" 
                x-on:click="$dispatch('start-voice-call', { userId: {{ $user->id }}, callType: 'voice' })"
                class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition active:scale-95">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6.62 10.79c.54 1.36 1.34 2.58 2.28 3.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C9.48 21 3 14.52 3 6c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
        </button>

        {{-- Video Call Button --}}
        <button type="button" 
                x-on:click="$dispatch('start-voice-call', { userId: {{ $user->id }}, callType: 'video' })"
                class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition active:scale-95">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
            </svg>
        </button>
    </div>
</div>
