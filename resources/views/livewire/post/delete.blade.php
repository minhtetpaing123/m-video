<div>
    {{-- ⚡ Delete Button (opens modal) --}}
    <button type="button" 
            wire:click="openModal"
            class="w-full flex items-center gap-3 px-3.5 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
        <span class="text-base">🗑️</span>
        <span>Delete post</span>
    </button>

    {{-- ⚡ Confirm Modal --}}
    <div id="delete-modal-{{ $post->id }}" 
         class="{{ $isOpen ? '' : 'hidden' }} fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
         wire:click.self="closeModal">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700">
            
            {{-- Icon --}}
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            {{-- Title & Message --}}
            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">Delete Post?</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-6">
                Are you sure you want to delete this post?<br>
                <span class="text-red-500 font-medium">This action cannot be undone.</span>
            </p>
            
            {{-- Buttons --}}
            <div class="flex justify-center gap-3">
                {{-- Cancel Button --}}
                <button type="button" 
                        wire:click="closeModal"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition">
                    Cancel
                </button>
                
                {{-- ⚡ Delete Button --}}
                <button type="button" 
                        wire:click="deletePost"
                        onclick="
                            // Close modal
                            document.getElementById('delete-modal-{{ $post->id }}').classList.add('hidden');
                            // Show loading on post card
                            showLoading({{ $post->id }});
                        "
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md shadow-red-600/20 transition">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>