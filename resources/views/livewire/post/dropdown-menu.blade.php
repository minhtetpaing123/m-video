<div class="relative inline-block text-left" 
     x-data="{ isOpen: false }"
     @click.outside="isOpen = false">
    
    <!-- Dropdown Toggle Button -->
    <button @click="isOpen = !isOpen" 
            class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition focus:outline-none">
        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
            <path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-52 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl z-50 overflow-hidden"
         style="display: none;">
        
        <div class="p-2.5 flex flex-col gap-2">
            <!-- Save Button (Light Gray Background) -->
            <button type="button" class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/80 transition-all shadow-sm">
                <span class="text-base">🔖</span>
                <span>Save Post</span>
            </button>

            <!-- Edit & Delete Buttons (only for authorized users) -->
            @if(auth()->check() && (auth()->id() === $post->user_id || auth()->user()->is_admin))
                
                <!-- Edit Button (Soft Blue Background) -->
                <a href="{{ route('posts.edit', $post->id) }}" 
                   wire:navigate
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 rounded-xl hover:bg-blue-100/70 dark:hover:bg-blue-950/80 transition-all no-underline shadow-sm">
                    <span class="text-base">✏️</span>
                    <span>Edit Post</span>
                </a>

                <!-- Livewire Delete Component Container (Soft Red Background) -->
                <div class="w-full border border-red-200 dark:border-red-900/60 rounded-xl overflow-hidden bg-red-50 dark:bg-red-950/30 hover:bg-red-100/60 dark:hover:bg-red-950/60 transition-all shadow-sm">
                    <livewire:post.delete :post="$post" :key="'delete-'.$post->id" />
                </div>
            @endif
        </div>
    </div>
</div>
