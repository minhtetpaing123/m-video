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
         x-transition
         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-1 z-50">
        
        {{-- Save Post Option --}}
        <button wire:click="savePost" 
                @click="showDropdown = false"
                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center gap-2">
            <span>🔖</span> Save Post
        </button>

        {{-- Author Only Options --}}
        @if(auth()->check() && auth()->id() === $post->user_id)
            {{-- Edit Post --}}
            <button wire:click="editPost"
                    @click="showDropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center gap-2">
                <span>✏️</span> Edit Post
            </button>

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

            {{-- Delete Post --}}
            <button wire:click="deletePost" 
                    onclick="showLoading({{ $post->id }})"
                    @click="showDropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 font-medium">
                <span>🗑️</span> Delete Post
            </button>
        @endif
    </div>
</div>
