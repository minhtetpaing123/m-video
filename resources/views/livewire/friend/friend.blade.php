<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-28"
     x-data="{ 
         showSubHeader: true,
         lastScrollY: 0,
         handleScroll() {
             let currentScrollY = window.scrollY;
             if (currentScrollY > this.lastScrollY && currentScrollY > 60) {
                 this.showSubHeader = false;
             } else {
                 this.showSubHeader = true;
             }
             this.lastScrollY = currentScrollY;
         }
     }"
     @scroll.window="handleScroll()">

    {{-- Main Top Header --}}
    <div class="sticky top-0 z-50 bg-white dark:bg-gray-900 shadow-sm">
        <livewire:dashboard.user-header />
    </div>

    {{-- Smart Hide/Show Sub-header on Scroll --}}
    <div class="sticky top-14 z-40 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-all duration-300 transform shadow-xs"
         :class="showSubHeader ? 'translate-y-0 opacity-100' : '-translate-y-full opacity-0 pointer-events-none'">
        
        <div class="max-w-2xl mx-auto px-4 py-3">
            {{-- Filter Chips --}}
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
                {{-- 1. Follow Posts --}}
                <button wire:click="$set('tab', 'posts')" 
                        class="px-3.5 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $tab === 'posts' ? 'bg-blue-600 text-white shadow-xs' : 'bg-gray-200/70 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300/70' }}">
                    Follow Posts
                </button>

                {{-- 2. Following --}}
                <button wire:click="$set('tab', 'friends')" 
                        class="px-3.5 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $tab === 'friends' ? 'bg-blue-600 text-white shadow-xs' : 'bg-gray-200/70 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300/70' }}">
                    Following ({{ $friends->count() }})
                </button>

                {{-- 3. Suggestions --}}
                <button wire:click="$set('tab', 'suggestions')" 
                        class="px-3.5 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $tab === 'suggestions' ? 'bg-blue-600 text-white shadow-xs' : 'bg-gray-200/70 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300/70' }}">
                    Suggestions
                </button>
            </div>
        </div>
    </div>

    <main class="max-w-2xl mx-auto px-4 pt-3 space-y-3">

        {{-- TAB 1: FOLLOW POSTS (LIVEWIRE COMPONENT) --}}
        @if($tab === 'posts')
            <livewire:friend.friend-post />
        @endif

        {{-- TAB 2: ALL FOLLOWING --}}
        @if($tab === 'friends')
            <div class="flex items-center justify-between pt-1">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    All Following <span class="text-gray-500 text-xs font-normal">({{ $friends->count() }})</span>
                </h2>
            </div>

            <div class="space-y-2.5">
                @forelse($friends as $friend)
                    <div class="flex items-center justify-between p-2.5 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ $friend->avatar_url }}" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 dark:text-white">{{ $friend->name }}</h4>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Follows you</p>
                            </div>
                        </div>
                        <button wire:click="removeFriend({{ $friend->id }})" 
                                class="px-3 py-1 bg-gray-100 dark:bg-gray-700/80 hover:bg-red-50 hover:text-red-600 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold transition">
                            Remove
                        </button>
                    </div>
                @empty
                    <div class="text-center py-14 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700/50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200">No Followers Found</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Check suggestions to follow new creators.</p>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- TAB 3: SUGGESTIONS --}}
        @if($tab === 'suggestions')
            <div class="flex items-center justify-between pt-1">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Suggested Accounts
                </h2>
            </div>

            <div class="space-y-3">
                @forelse($suggestions as $person)
                    <div class="flex items-start gap-3 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
                        <img src="{{ $person->avatar_url }}" class="w-16 h-16 rounded-full object-cover shadow-xs flex-shrink-0">
                        
                        <div class="flex-1 min-w-0 pt-0.5">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $person->name }}
                            </h3>
                            
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-2">
                                Suggested for you
                            </p>

                            <div class="flex items-center gap-2">
                                <button wire:click="sendRequest({{ $person->id }})" 
                                        class="flex-1 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-bold rounded-xl transition shadow-xs">
                                    Follow
                                </button>
                                <button wire:click="removeFriend({{ $person->id }})" 
                                        class="flex-1 py-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 active:scale-95 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-14 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200">No Suggestions Available</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">We couldn't find any new creators to suggest right now.</p>
                    </div>
                @endforelse
            </div>
        @endif

    </main>

    <livewire:layout.nav />
</div>
