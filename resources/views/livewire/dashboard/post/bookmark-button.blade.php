<button 
    wire:click="toggleBookmark" 
    class="flex items-center gap-1.5 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
    aria-label="Save Post"
>
    @if($isSaved)
        {{-- Filled Bookmark Icon --}}
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M5 5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v16l-7-3.5L5 21V5z"/>
        </svg>
        <span class="text-xs font-semibold text-blue-600 dark:text-blue-500">Saved</span>
    @else
        {{-- Outline Bookmark Icon --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
        </svg>
        <span class="text-xs font-medium">Save</span>
    @endif
</button>
