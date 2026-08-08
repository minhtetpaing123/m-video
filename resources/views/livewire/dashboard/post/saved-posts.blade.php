<div class="min-h-screen bg-gray-50 dark:bg-gray-900" 
     x-data="{ 
         confirmClearAll: false, 
         confirmDeleteSelected: false,
         showSubHeader: true,
         lastScrollY: 0,
         handleScroll() {
             let currentScrollY = window.scrollY;
             // အောက်ကို ဆွဲရင် Hide လုပ်မည်၊ အပေါ်ပြန်ဆွဲရင် ချက်ချင်း Show မည်
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

    {{-- Smart Sticky Sub-Header (friend.blade.php ကဲ့သို့ top-14 နှင့် z-40 ပြောင်းထားပါသည်) --}}
    <div class="sticky top-14 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200/60 dark:border-gray-800 transition-all duration-300 transform"
         :class="showSubHeader ? 'translate-y-0 opacity-100' : '-translate-y-full opacity-0 pointer-events-none'">
        
        <div class="max-w-2xl mx-auto px-4 py-2.5">
            {{-- Header Bar (Title, Items Count, Sort Dropdown & Select All) --}}
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                        Saved Posts
                    </h1>
                </div>
                
                {{-- Item count + Sorting + Select All + Clear --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-medium px-2.5 py-1 bg-gray-200/70 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg">
                        {{ $savedPosts->count() }} {{ Str::plural('item', $savedPosts->count()) }}
                    </span>

                    {{-- Recently Saved / Oldest First Dropdown --}}
                    <select wire:model.live="sort" class="px-2 py-1 bg-gray-100 dark:bg-gray-800 border-0 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 focus:ring-1 focus:ring-blue-500 cursor-pointer">
                        <option value="latest">Recently Saved</option>
                        <option value="oldest">Oldest First</option>
                    </select>

                    @if($savedPosts->count() > 0)
                        {{-- Select All Checkbox Button --}}
                        <label class="flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-semibold cursor-pointer select-none">
                            <input type="checkbox" wire:model.live="selectAll" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            Select All
                        </label>

                        {{-- Select Mode Toggle --}}
                        <button wire:click="toggleSelectMode" class="px-2 py-1 text-xs font-semibold {{ $isSelectMode ? 'text-blue-600 bg-blue-100 dark:bg-blue-900/40' : 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800' }} rounded-lg transition">
                            {{ $isSelectMode ? 'Cancel' : 'Select' }}
                        </button>

                        <button @click="confirmClearAll = true"
                                class="text-xs font-semibold text-red-500 hover:text-red-700 dark:hover:text-red-400 px-1 py-1 transition">
                            Clear All
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-2xl mx-auto px-4 pt-4 pb-36 space-y-4">

        {{-- Selected Bar (Show when items selected) --}}
        @if(count($selectedPosts) > 0)
            <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/50 px-4 py-2 rounded-xl text-xs">
                <span class="font-medium text-blue-700 dark:text-blue-300">
                    Selected {{ count($selectedPosts) }} {{ Str::plural('item', count($selectedPosts)) }}
                </span>
                <button @click="confirmDeleteSelected = true" class="flex items-center gap-1 font-bold text-red-500 hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remove Selected
                </button>
            </div>
        @endif

        {{-- Search Input --}}
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Search saved posts..." 
                   class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>

        {{-- Skeleton Loader State --}}
        <div wire:loading wire:target="search, sort" class="w-full space-y-4">
            @for ($i = 0; $i < 2; $i++)
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700/60 space-y-3 animate-pulse">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                        <div class="space-y-1.5 flex-1">
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                            <div class="h-2.5 bg-gray-200 dark:bg-gray-700 rounded w-1/6"></div>
                        </div>
                    </div>
                    <div class="h-40 bg-gray-200 dark:bg-gray-700 rounded-xl w-full"></div>
                    <div class="flex justify-between items-center pt-2">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/5"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/5"></div>
                    </div>
                </div>
            @endfor
        </div>

        {{-- Main Saved Posts List (Mobile Margin ပြည့်စေရန် -mx-4 sm:mx-0 ထည့်ထားပါသည်) --}}
        <div wire:loading.remove wire:target="search, sort" class="-mx-4 sm:mx-0 space-y-4">
            @forelse($savedPosts as $post)
                <div wire:key="saved-post-{{ $post->id }}" class="relative group">
                    @if($isSelectMode || count($selectedPosts) > 0 || $selectAll)
                        <div class="absolute top-4 left-4 z-20">
                            <input type="checkbox" 
                                   wire:model.live="selectedPosts" 
                                   value="{{ $post->id }}" 
                                   class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 shadow-md cursor-pointer">
                        </div>
                    @endif
                    <div class="{{ ($isSelectMode || count($selectedPosts) > 0 || $selectAll) ? 'pl-8 transition-all' : '' }} w-full">
                        <livewire:dashboard.post.post-card :post="$post" :key="'saved-post-card-'.$post->id" />
                    </div>
                </div>
            @empty
                <div class="mx-4 text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 my-6">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">
                        {{ !empty($search) ? 'No posts match your search' : 'No saved posts yet' }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">
                        {{ !empty($search) ? 'Try searching with another keyword.' : 'Articles and videos you bookmark will be stored here for quick access.' }}
                    </p>
                </div>
            @endforelse
        </div>

    </main>

    {{-- Confirm Delete Selected Modal --}}
    <div x-show="confirmDeleteSelected" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-sm w-full mx-4 shadow-xl text-center" @click.away="confirmDeleteSelected = false">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Remove Selected Posts?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to remove the selected post(s) from your saved items?
            </p>
            <div class="flex items-center gap-3">
                <button @click="confirmDeleteSelected = false" class="flex-1 py-2.5 px-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button wire:click="deleteSelected" @click="confirmDeleteSelected = false" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                    Remove
                </button>
            </div>
        </div>
    </div>

    {{-- Confirm Clear All Modal --}}
    <div x-show="confirmClearAll" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-sm w-full mx-4 shadow-xl text-center" @click.away="confirmClearAll = false">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Clear All Saved Posts?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to remove all saved posts? This action cannot be undone.
            </p>
            <div class="flex items-center gap-3">
                <button @click="confirmClearAll = false" class="flex-1 py-2.5 px-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button wire:click="clearAll" @click="confirmClearAll = false" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                    Clear All
                </button>
            </div>
        </div>
    </div>

    <livewire:layout.nav />
</div>
