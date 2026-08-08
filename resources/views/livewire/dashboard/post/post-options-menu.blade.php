<div class="relative" x-data="{ showDropdown: false }">
    {{-- Three-dots Toggle Button --}}
    <button @click="showDropdown = !showDropdown" 
            @click.away="showDropdown = false"
            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="showDropdown" 
         x-cloak
         x-transition
         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-1 z-50">
        
        {{-- Save / Unsave Post Option --}}
        @php
            $isSaved = auth()->check() ? $post->isSavedBy(auth()->user()) : false;
        @endphp

        @if($isSaved)
            <button type="button"
                    wire:click.prevent="savePost" 
                    @click="showDropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center gap-2 font-medium">
                {{-- Filled Bookmark Icon --}}
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v16l-7-3.5L5 21V5z"/>
                </svg>
                Saved (Remove)
            </button>
        @else
            <button type="button"
                    wire:click.prevent="savePost" 
                    @click="showDropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center gap-2">
                {{-- Outline Bookmark Icon --}}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                </svg>
                Save Post
            </button>
        @endif

        {{-- Author Only Options --}}
        @if(auth()->check() && auth()->id() === $post->user_id)
            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

            {{-- Edit Post --}}
            <button type="button"
                    wire:click="editPost"
                    @click="showDropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center gap-2">
                <span>✏️</span> Edit Post
            </button>

            {{-- Delete Post --}}
            <button type="button"
                    wire:click="deletePost" 
                    onclick="showLoading({{ $post->id }})"
                    @click="showDropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 font-medium">
                <span>🗑️</span> Delete Post
            </button>
        @else
            {{-- Other User's Post Options (Block User Option) --}}
            @auth
                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <div @click="showDropdown = false" class="w-full">
                    <livewire:friend.block-user :targetUser="$post->user" :key="'block-post-user-'.$post->id.'-'.$post->user_id" />
                </div>
            @endauth
        @endif
    </div>
</div>
