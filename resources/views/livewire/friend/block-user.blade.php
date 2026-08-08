<div x-data="{ showModal: false }" class="w-full">
    {{-- Trigger Button --}}
    <button type="button" 
            @click="showModal = true"
            class="w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 font-medium transition">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
        </svg>
        <span>Block User</span>
    </button>

    {{-- ✨ Modern Facebook Style Modal --}}
    <template x-teleport="body">
        <div x-show="showModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            
            <div @click.away="showModal = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-gray-700/60 text-center flex flex-col items-center">
                
                <div class="w-14 h-14 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center mb-4 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    Block {{ $targetUser->name }}?
                </h3>
                
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6 px-1">
                    Their posts and profile will no longer be visible. They won't be notified that you blocked them.
                </p>

                <div class="w-full flex flex-col gap-2">
                    <button type="button" 
                            @click="showModal = false; $wire.block()"
                            class="w-full py-3 bg-red-600 hover:bg-red-700 active:scale-98 text-white font-semibold text-sm rounded-xl transition-all shadow-md">
                        Block
                    </button>
                    
                    <button type="button" 
                            @click="showModal = false"
                            class="w-full py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700/50 dark:hover:bg-gray-700 active:scale-98 text-gray-700 dark:text-gray-300 font-semibold text-sm rounded-xl transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
