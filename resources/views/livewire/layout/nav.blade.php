<div>
    <!-- Navigation Bar -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-[#1a1a2e]/95 border-t border-gray-200 dark:border-[#2a2a3e] backdrop-blur-xl pt-1.5 pb-[calc(0.5rem+env(safe-area-inset-bottom))] sm:pb-2 transition-colors duration-200">
        <div class="flex items-center justify-around max-w-lg mx-auto px-1">
            
            {{-- Home --}}
            @php $isHome = request()->routeIs('dashboard') || request()->is('/') || request()->is('home'); @endphp
            <a href="{{ route('dashboard') }}" wire:navigate 
               class="relative flex flex-col items-center justify-center flex-1 py-1 text-xs font-medium transition-colors duration-200 group {{ $isHome ? 'text-[#2d88ff]' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                @if($isHome)
                    <span class="absolute -top-[6px] left-1/2 -translate-x-1/2 w-5 h-[3px] bg-[#2d88ff] rounded-b-sm"></span>
                @endif
                <div class="relative w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center">
                    <svg viewBox="0 0 28 28" class="w-6 h-6 sm:w-7 sm:h-7 fill-current">
                        <path d="M25.825 12.29c-.018-.019-.185-7.394-.185-7.394A1.815 1.815 0 0 0 23.824 3.5H16.5a.5.5 0 0 0-.5.5v4.75a.25.25 0 0 1-.25.25h-3.5a.25.25 0 0 1-.25-.25V4a.5.5 0 0 0-.5-.5H4.176a1.815 1.815 0 0 0-1.816 1.816c0 .122-.142 7.35-.16 7.37a1.867 1.867 0 0 0 1.45 3.21h.386c1.005.02 1.809.847 1.809 1.867v8.02c0 1.03.84 1.87 1.87 1.87h3.933a1.87 1.87 0 0 0 1.87-1.87v-5.1c0-.386.314-.7.7-.7h2.8c.386 0 .7.314.7.7v5.1c0 1.03.84 1.87 1.87 1.87h3.933a1.87 1.87 0 0 0 1.87-1.87v-8.04c0-1.02.804-1.847 1.81-1.867h.386a1.868 1.868 0 0 0 1.45-3.21z"/>
                    </svg>
                </div>
                <span class="text-[9px] sm:text-[10px] leading-tight mt-0.5">Home</span>
            </a>

            {{-- Friends --}}
            @php $isFriends = request()->is('friends*'); @endphp
            <a href="/friends" wire:navigate 
               class="relative flex flex-col items-center justify-center flex-1 py-1 text-xs font-medium transition-colors duration-200 group {{ $isFriends ? 'text-[#2d88ff]' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                @if($isFriends)
                    <span class="absolute -top-[6px] left-1/2 -translate-x-1/2 w-5 h-[3px] bg-[#2d88ff] rounded-b-sm"></span>
                @endif
                <div class="relative w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center">
                    <svg viewBox="0 0 28 28" class="w-6 h-6 sm:w-7 sm:h-7 fill-current">
                        <path d="M10.5 4.5c-2.272 0-4.5 1.636-4.5 4v1c0 1.295.669 2.581 2.051 3.46.89.569 1.67.865 2.449 1.06v3.08c0 1.295-.773 2.55-2.468 3.46-.444.24-.804.44-1.108.7a7.368 7.368 0 0 0-.76.8c-.25.308-.664.806-1.242.806h-.017a.494.494 0 0 1-.494-.494V24c0-.613.403-1.22 1.144-1.907.8-.739 1.665-1.341 2.497-1.797.901-.498 1.633-.77 2.095-.932.203-.069.353-.105.48-.126a.757.757 0 0 1 .21-.037h3c.07 0 .138.012.203.036.126.02.274.056.474.125.463.162 1.195.434 2.096.932.832.456 1.697 1.058 2.497 1.797.74.687 1.144 1.294 1.144 1.907v.035a.494.494 0 0 1-.494.494h-.017c-.578 0-.992-.498-1.242-.806a7.38 7.38 0 0 0-.76-.8c-.304-.26-.664-.46-1.108-.7-1.695-.91-2.468-2.165-2.468-3.46v-3.08c.78-.195 1.56-.491 2.45-1.06 1.381-.88 2.05-2.166 2.05-3.46v-1c0-2.364-2.228-4-4.5-4s-4.5 1.636-4.5 4v1c0 1.295.669 2.581 2.051 3.46C12.582 11.435 13.5 10.89 13.5 10v-1.5c0-2.364-2.228-4-4.5-4z"/>
                    </svg>
                </div>
                <span class="text-[9px] sm:text-[10px] leading-tight mt-0.5">Friends</span>
            </a>

            {{-- Chat --}}
            @php 
                $isChat = request()->is('chat*'); 
                $unreadMessagesCount = auth()->check() 
                    ? \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count() 
                    : 0;
            @endphp
            <a href="/chat" wire:navigate 
               class="relative flex flex-col items-center justify-center flex-1 py-1 text-xs font-medium transition-colors duration-200 group {{ $isChat ? 'text-[#2d88ff]' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                @if($isChat)
                    <span class="absolute -top-[6px] left-1/2 -translate-x-1/2 w-5 h-[3px] bg-[#2d88ff] rounded-b-sm"></span>
                @endif
                <div class="relative w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center">
                    <svg viewBox="0 0 28 28" class="w-6 h-6 sm:w-7 sm:h-7 fill-current">
                        <path d="M14 2C7.373 2 2 6.925 2 13c0 2.23.716 4.305 1.94 6.012L2.518 24.5a.75.75 0 0 0 .93.93l5.488-1.422C10.695 24.634 12.31 25 14 25c6.627 0 12-4.925 12-11S20.627 2 14 2zm-5 12a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                    </svg>

                    {{-- 💬 Unread Message Badge --}}
                    @if($unreadMessagesCount > 0)
                        <span class="absolute -top-1 -right-1.5 min-w-[18px] h-[18px] bg-red-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow-md border-2 border-white dark:border-[#1a1a2e] animate-pulse">
                            {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[9px] sm:text-[10px] leading-tight mt-0.5">Chat</span>
            </a>

            {{-- Saved Posts --}}
            @php $isSaved = request()->is('saved*') || request()->routeIs('saved*'); @endphp
            <a href="/saved" wire:navigate 
               class="relative flex flex-col items-center justify-center flex-1 py-1 text-xs font-medium transition-colors duration-200 group {{ $isSaved ? 'text-[#2d88ff]' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                @if($isSaved)
                    <span class="absolute -top-[6px] left-1/2 -translate-x-1/2 w-5 h-[3px] bg-[#2d88ff] rounded-b-sm"></span>
                @endif
                
                <div class="relative w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 sm:w-7 sm:h-7 fill-current">
                        <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2zm0 15l-5-2.18L7 18V5h10v13z"/>
                    </svg>
                </div>

                <span class="text-[9px] sm:text-[10px] leading-tight mt-0.5">Saved</span>
            </a>

            {{-- Profile --}}
            @php $isProfile = request()->is('profile*'); @endphp
            <a href="{{ auth()->check() ? route('profile.show', auth()->user()) : '#' }}" wire:navigate 
               class="relative flex flex-col items-center justify-center flex-1 py-1 text-xs font-medium transition-colors duration-200 group {{ $isProfile ? 'text-[#2d88ff]' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                @if($isProfile)
                    <span class="absolute -top-[6px] left-1/2 -translate-x-1/2 w-5 h-[3px] bg-[#2d88ff] rounded-b-sm"></span>
                @endif
                <div class="relative w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center">
                    <svg viewBox="0 0 28 28" class="w-6 h-6 sm:w-7 sm:h-7 fill-current">
                        <path d="M14 2.042c6.76 0 12 4.952 12 11.64S20.76 25.322 14 25.322a13.091 13.091 0 0 1-3.474-.461.959.959 0 0 0-.641.047L7.5 25.959a.961.961 0 0 1-1.348-.849l-.065-2.134a.957.957 0 0 0-.322-.684A11.389 11.389 0 0 1 2 13.682C2 6.994 7.24 2.042 14 2.042zm0 2.5c-2.83 0-5.1 1.83-5.1 4.058 0 1.5.83 2.833 2.1 3.668v2.774c0 .69.56 1.25 1.25 1.25h3.5c.69 0 1.25-.56 1.25-1.25v-2.774c1.27-.835 2.1-2.168 2.1-3.668 0-2.228-2.27-4.058-5.1-4.058z"/>
                    </svg>
                </div>
                <span class="text-[9px] sm:text-[10px] leading-tight mt-0.5">Profile</span>
            </a>

        </div>
    </nav>
</div>
