<div x-data="{ showUnblockModal: false }" class="min-h-[60px] w-full flex flex-col border-t border-gray-200/80 dark:border-gray-800/80 bg-white/95 dark:bg-[#121212]/95 backdrop-blur-md shrink-0 z-50">

    @if($this->iBlockedUser)
        {{-- 🔴 Modern Messenger Block Bar (For Blocker) --}}
        <div class="w-full p-3 flex justify-center items-center">
            <div class="w-full max-w-lg py-2.5 px-4 bg-gray-100/90 dark:bg-gray-800/90 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 flex items-center justify-between gap-3 shadow-xs transition-all">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-2 h-2 rounded-full bg-red-500 shrink-0 animate-pulse"></div>
                    <p class="text-[12px] font-medium text-gray-700 dark:text-gray-300 truncate">
                        You blocked <span class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</span>
                    </p>
                </div>
                
                {{-- Unblock Button opens Custom Modal --}}
                <button type="button" 
                        @click="showUnblockModal = true"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 shrink-0 flex items-center gap-1">
                    <span wire:loading.remove wire:target="unblockUser">Unblock</span>
                    <span wire:loading wire:target="unblockUser" class="flex items-center gap-1">
                        <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        {{-- ✨ Modern Custom Unblock Modal (FB Messenger Style) --}}
        <template x-teleport="body">
            <div x-show="showUnblockModal" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                
                <div @click.away="showUnblockModal = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-gray-700/60 text-center flex flex-col items-center">
                    
                    <!-- Icon Avatar -->
                    <div class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197M12 12a4 4 0 100-8 4 4 0 000 8zm0 0a6 6 0 00-6 6v1"/>
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                        Unblock {{ $user->name }}?
                    </h3>
                    
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6 px-2">
                        They will be able to message you and call you in this chat. They won't know you unblocked them unless you send a message.
                    </p>

                    <!-- Modal Actions -->
                    <div class="w-full flex flex-col gap-2">
                        <button type="button" 
                                @click="showUnblockModal = false; $wire.unblockUser()"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white font-semibold text-sm rounded-xl transition-all shadow-md">
                            Unblock
                        </button>
                        
                        <button type="button" 
                                @click="showUnblockModal = false"
                                class="w-full py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700/50 dark:hover:bg-gray-700 active:scale-98 text-gray-700 dark:text-gray-300 font-semibold text-sm rounded-xl transition-all">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </template>

    @elseif($this->iAmBlockedBy)
        {{-- 🔴 Modern Messenger Block Bar (For Blocked User) --}}
        <div class="w-full p-3 flex justify-center items-center">
            <div class="w-full max-w-lg py-2.5 px-4 bg-gray-100/90 dark:bg-gray-800/90 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 flex items-center justify-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                    You cannot reply to this conversation.
                </p>
            </div>
        </div>

    @else
        {{-- 🔥 Reply / Edit Preview Bar --}}
        @if($replyMessage || $editingMessage)
            <div class="flex items-center justify-between px-4 py-2 bg-gray-100 dark:bg-gray-800 text-xs border-b dark:border-gray-700">
                <div class="flex flex-col truncate pr-2">
                    @if($replyMessage)
                        <span class="font-semibold text-blue-500">Replying to {{ $replyMessage->sender_id === auth()->id() ? 'yourself' : $user->name }}</span>
                        <span class="text-gray-600 dark:text-gray-300 truncate">{{ $replyMessage->message }}</span>
                    @elseif($editingMessage)
                        <span class="font-semibold text-blue-500">Editing Message</span>
                        <span class="text-gray-600 dark:text-gray-300 truncate">{{ $editingMessage->message }}</span>
                    @endif
                </div>
                <button type="button" wire:click="cancelInput" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Main Chat Input Controls --}}
        <div class="w-full flex items-center px-2 py-2">
            <form wire:submit.prevent="sendMessage" @submit="hasText = false" class="w-full flex items-center gap-1.5">
                
                <button type="button" x-show="hasText" x-cloak class="p-1.5 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>

                <div x-show="!hasText" class="flex items-center gap-1 shrink-0">
                    <button type="button" class="p-1.5 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/>
                            <path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                        </svg>
                    </button>

                    <button type="button" class="p-1.5 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                    </button>

                    <button type="button" class="p-1.5 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 flex items-center bg-[#f0f2f5] dark:bg-[#242526] rounded-full px-3 py-1.5">
                    <input type="text" 
                           id="messageInput"
                           wire:model="message" 
                           x-on:keydown="
                               if (typeof Echo !== 'undefined') {
                                   Echo.private('chat.{{ $user->id }}')
                                       .whisper('typing', { typing: true });
                               }
                           "
                           x-on:focus="
                               window.scrollTo(0,0);
                               setTimeout(scrollToBottom, 150);
                           "
                           x-on:input="hasText = $event.target.value.trim().length > 0"
                           placeholder="Message" 
                           class="w-full bg-transparent border-none outline-none focus:ring-0 p-0 text-[16px] text-gray-900 dark:text-white placeholder-gray-500">
                    
                    <button type="button" class="text-blue-600 hover:opacity-80 transition ml-1 shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5s.67 1.5 1.5 1.5zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="p-1.5 text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition shrink-0">
                    <svg x-show="hasText" x-cloak class="w-6 h-6 transform rotate-90" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                    <svg x-show="!hasText" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.58 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                    </svg>
                </button>

            </form>
        </div>
    @endif
</div>
