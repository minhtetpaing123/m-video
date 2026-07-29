<div class="min-h-[60px] w-full flex flex-col border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-[#121212] shrink-0 z-50">

    {{-- 🔥 Reply သို့မဟုတ် Edit ပြုလုပ်နေပါက ပေါ်လာမည့် Preview Bar --}}
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
</div>
