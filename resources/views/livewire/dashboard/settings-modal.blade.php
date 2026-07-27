<div 
    x-data="{ open: @entangle('isOpen') }" 
    @open-settings-modal.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
>
    <!-- Modal Container -->
    <div 
        @click.outside="open = false; $wire.closeModal()" 
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full p-5 shadow-2xl border border-gray-100 dark:border-gray-700"
    >
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Display Settings</h3>
            </div>

            <button 
                @click="open = false; $wire.closeModal()" 
                class="w-7 h-7 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
                ✕
            </button>
        </div>

        <!-- Layout Options -->
        <div class="space-y-2">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block px-1">Feed Layout Style</span>

            <!-- 1. YouTube Grid (Default) -->
            <button 
                wire:click="changeLayout('grid')"
                class="w-full p-2.5 rounded-2xl border text-left flex items-center justify-between transition-all duration-150 {{ $layoutMode === 'grid' ? 'border-red-500 bg-red-50/50 dark:bg-red-950/20 ring-2 ring-red-500/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="text-xl">🎬</span>
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">YouTube Grid (Default)</h4>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">Multi-column responsive video grid</p>
                    </div>
                </div>
                @if($layoutMode === 'grid')
                    <div class="w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
                @endif
            </button>

            <!-- 2. Netflix Layout (New) -->
            <button 
                wire:click="changeLayout('netflix')"
                class="w-full p-2.5 rounded-2xl border text-left flex items-center justify-between transition-all duration-150 {{ $layoutMode === 'netflix' ? 'border-red-600 bg-red-900/10 dark:bg-red-950/40 ring-2 ring-red-600/30' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="text-xl">🍿</span>
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">Netflix Style</h4>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">Cinematic Dark Poster Cards</p>
                    </div>
                </div>
                @if($layoutMode === 'netflix')
                    <div class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">✓</div>
                @endif
            </button>

            <!-- 3. Mobile Compact -->
            <button 
                wire:click="changeLayout('normal')"
                class="w-full p-2.5 rounded-2xl border text-left flex items-center justify-between transition-all duration-150 {{ $layoutMode === 'normal' ? 'border-red-500 bg-red-50/50 dark:bg-red-950/20 ring-2 ring-red-500/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="text-xl">📱</span>
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">Mobile Compact</h4>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">Single column traditional feed</p>
                    </div>
                </div>
                @if($layoutMode === 'normal')
                    <div class="w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
                @endif
            </button>

            <!-- 4. Expanded Wide -->
            <button 
                wire:click="changeLayout('wide')"
                class="w-full p-2.5 rounded-2xl border text-left flex items-center justify-between transition-all duration-150 {{ $layoutMode === 'wide' ? 'border-red-500 bg-red-50/50 dark:bg-red-950/20 ring-2 ring-red-500/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750' }}"
            >
                <div class="flex items-center gap-3">
                    <span class="text-xl">🖥️</span>
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">Expanded Wide</h4>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">Centered wide stream layout</p>
                    </div>
                </div>
                @if($layoutMode === 'wide')
                    <div class="w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
                @endif
            </button>
        </div>

        <!-- Footer -->
        <div class="mt-4">
            <button 
                @click="open = false; $wire.closeModal()" 
                class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold shadow-md transition-all"
            >
                Close
            </button>
        </div>
    </div>
</div>
