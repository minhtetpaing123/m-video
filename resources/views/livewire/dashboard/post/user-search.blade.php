<div 
    x-data="{ open: @entangle('isSearchOpen') }"
    x-init="
        $watch('open', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    "
>
    {{-- Search Trigger Icon --}}
    @if(!$isSearchOpen)
        <div wire:click="openSearch" class="mv-icon-circle mv-search-icon-btn cursor-pointer bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" role="button" aria-label="Search" tabindex="0">
            <svg viewBox="0 0 28 28" width="22" height="22" class="fill-gray-600 dark:fill-gray-200">
                <path d="M12.5 3.5C7.81 3.5 4 7.31 4 12s3.81 8.5 8.5 8.5c1.89 0 3.63-.62 5.05-1.67l4.71 4.71c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-4.71-4.71c1.05-1.42 1.67-3.16 1.67-5.05 0-4.69-3.81-8.5-8.5-8.5zm0 2.5c3.32 0 6 2.68 6 6s-2.68 6-6 6-6-2.68-6-6 2.68-6 6-6z"/>
            </svg>
        </div>
    @endif

    {{-- Body အောက်သို့ တိုက်ရိုက် Teleport လုပ်ထားသော Full-Screen Modal --}}
    @if($isSearchOpen)
        @teleport('body')
            <div 
                class="fixed inset-0 z-[99999999] bg-white dark:bg-gray-900 flex flex-col h-screen w-screen overflow-hidden"
                style="top: 0; left: 0; width: 100vw; height: 100vh; position: fixed;"
            >
                {{-- Search Header Bar --}}
                <div class="p-3 border-b border-gray-200 dark:border-gray-800 flex items-center gap-2 bg-white dark:bg-gray-900 flex-shrink-0 shadow-sm">
                    <button wire:click="closeSearch" class="p-2 text-blue-600 dark:text-blue-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg viewBox="0 0 28 28" width="22" height="22" fill="currentColor">
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                        </svg>
                    </button>

                    <div class="flex-1 flex items-center bg-gray-100 dark:bg-gray-800 rounded-full px-3 py-1.5">
                        <svg class="mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" viewBox="0 0 28 28" width="18" height="18" fill="currentColor">
                            <path d="M12.5 3.5C7.81 3.5 4 7.31 4 12s3.81 8.5 8.5 8.5c1.89 0 3.63-.62 5.05-1.67l4.71 4.71c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-4.71-4.71c1.05-1.42 1.67-3.16 1.67-5.05 0-4.69-3.81-8.5-8.5-8.5zm0 2.5c3.32 0 6 2.68 6 6s-2.68 6-6 6-6-2.68-6-6 2.68-6 6-6z"/>
                        </svg>
                        <input 
                            type="search" 
                            wire:model.live.debounce.300ms="search"
                            class="w-full bg-transparent border-none text-sm focus:outline-none focus:ring-0 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" 
                            placeholder="Search MVideo"
                            autofocus
                        >
                        @if(strlen(trim($search)) > 0)
                            <button wire:click="clearSearch" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 ml-1">
                                ✕
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Scrollable Results Area --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-white dark:bg-gray-900 overscroll-contain">
                    {{-- Loading State --}}
                    <div wire:loading class="w-full text-center py-6 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        Searching...
                    </div>

                    <div wire:loading.remove>
                        @if(strlen(trim($search)) > 1)
                            {{-- People / Users Section --}}
                            @if($users->isNotEmpty())
                                <div>
                                    <div class="px-2 py-1 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                        PEOPLE
                                    </div>
                                    <div class="mt-1 space-y-1">
                                        @foreach($users as $user)
                                            <div class="flex items-center justify-between p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                                                <a 
                                                    href="/profile/{{ $user->id }}" 
                                                    wire:click="closeSearch"
                                                    class="flex items-center space-x-3 flex-1 min-w-0 mr-2"
                                                >
                                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-base overflow-hidden flex-shrink-0">
                                                        @if($user->avatar)
                                                            <img src="{{ $user->avatar }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        @endif
                                                    </div>
                                                    <div class="truncate">
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">User Profile</p>
                                                    </div>
                                                </a>
                                                <button 
                                                    wire:click="toggleFollow({{ $user->id }})" 
                                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $user->is_following ? 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                                >
                                                    {{ $user->is_following ? 'Following' : '+ Follow' }}
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Posts Section --}}
                            @if($searchResults->isNotEmpty())
                                <div class="pt-3">
                                    <div class="px-2 py-1 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                        POSTS
                                    </div>
                                    <div class="mt-1 space-y-1">
                                        @foreach($searchResults as $post)
                                            <div 
                                                wire:click="selectPost({{ $post->id }})" 
                                                class="flex items-center space-x-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl cursor-pointer transition-colors"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center flex-shrink-0 text-gray-500 dark:text-gray-400">
                                                    @if(!empty($post->thumbnail))
                                                        <img src="{{ $post->thumbnail }}" class="w-10 h-10 rounded-lg object-cover">
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $post->title ?? $post->content ?? 'Untitled Post' }}</p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">Click to view post</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- No Results --}}
                            @if($users->isEmpty() && $searchResults->isEmpty())
                                <div class="text-center py-10 text-sm text-gray-500 dark:text-gray-400">
                                    No results found for "<span class="font-semibold text-gray-700 dark:text-gray-300">{{ $search }}</span>"
                                </div>
                            @endif
                        @else
                            <div class="text-center py-10 text-xs text-gray-400 dark:text-gray-500">
                                Type at least 2 characters to search...
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endteleport
    @endif
</div>
