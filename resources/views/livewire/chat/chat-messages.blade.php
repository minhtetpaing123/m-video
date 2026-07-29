<div id="chatMessages" 
     wire:ignore.self
     x-data="{
        scrollToBottom() {
            $nextTick(() => {
                $el.scrollTop = $el.scrollHeight;
            });
        }
     }"
     x-init="
        scrollToBottom();

        if (typeof Echo !== 'undefined') {
            Echo.private('chat.{{ auth()->id() }}')
                .listen('.message-sent', (e) => {
                    $wire.refreshMessages();
                })
                .listen('MessageSent', (e) => {
                    $wire.refreshMessages();
                })
                .listen('.messages-read', (e) => {
                    $wire.loadMessagesSilently();
                })
                .listen('MessageReacted', (e) => {
                    $wire.loadMessagesSilently();
                });
        }
     "
     @scroll-to-bottom.window="scrollToBottom()"
     class="flex-1 overflow-y-auto p-3 flex flex-col gap-3 min-h-0 overscroll-contain select-none"
     style="-webkit-overflow-scrolling: touch; touch-action: pan-y;">
    
    <div class="flex flex-col gap-3 w-full">
        @foreach($messages as $item)
            @if($item instanceof \App\Models\Message)
                @php
                    $reactionString = $item->reactions ? $item->reactions->pluck('emoji')->join('') : '';
                    $updatedTimestamp = $item->updated_at ? $item->updated_at->timestamp : ($item->created_at ? $item->created_at->timestamp : time());
                @endphp
                <livewire:chat.chat-message-item 
                    :msg="$item" 
                    :user="$user" 
                    :isLast="$loop->last" 
                    :key="'msg-'.$item->id.'-'.$updatedTimestamp" />
            @elseif($item instanceof \App\Models\Call)
                @php
                    $isOutgoing = ($item->caller_id === auth()->id());
                    $status = strtolower($item->status ?? '');
                    $endReason = strtolower($item->end_reason ?? '');
                    
                    // Reject ဖြစ်ခဲ့သည်ဟု စစ်ဆေးခြင်း
                    $isRejected = in_array($status, ['rejected', 'declined']) || in_array($endReason, ['rejected', 'declined']);
                    
                    // Answered (ပြောဆိုခဲ့သည်) ဟု စစ်ဆေးခြင်း
                    $isAnswered = ($status === 'accepted' || $status === 'ended' || $endReason === 'completed') && !empty($item->duration) && $item->duration !== '0' && $item->duration !== '00:00';
                @endphp
                
                <div class="flex justify-center my-2" wire:key="call-log-{{ $item->id }}">
                    <div class="bg-gray-100 dark:bg-gray-800 text-xs px-3.5 py-1.5 rounded-full flex items-center gap-2 shadow-sm border border-gray-200/50 dark:border-gray-700/50">
                        
                        @if($isAnswered)
                            {{-- 1. Answered Call (အပြာရောင်) --}}
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                @if($isOutgoing)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25" />
                                @endif
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">
                                Voice Call Ended • {{ $item->duration }}
                            </span>

                        @elseif($isRejected)
                            {{-- 2. Reject Call (အနီရောင်) --}}
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                @if($isOutgoing)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25" />
                                @endif
                            </svg>
                            <span class="text-red-500 font-medium">
                                {{ $isOutgoing ? 'Call declined' : 'You declined a voice call' }}
                            </span>

                        @else
                            {{-- 3. Missed / Unanswered Call (မီးခိုးရောင် / ပုံမှန်) --}}
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                @if($isOutgoing)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25" />
                                @endif
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400 font-medium">
                                {{ $isOutgoing ? 'Unanswered voice call' : 'Missed voice call' }}
                            </span>
                        @endif

                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Typing Indicator Box --}}
    <div x-data="{ isTyping: false, timer: null }" 
         x-init="
            if (typeof Echo !== 'undefined') {
                Echo.private('chat.{{ auth()->id() }}')
                    .listenForWhisper('typing', (e) => {
                        isTyping = true;
                        clearTimeout(timer);
                        timer = setTimeout(() => { isTyping = false; }, 2500);
                    });
            }
         "
         x-show="isTyping" 
         x-cloak 
         class="flex items-center gap-2 mt-1 shrink-0">
        <img src="{{ $user->avatar_url }}" class="w-[28px] h-[28px] rounded-full object-cover">
        <div class="bg-[#e4e6eb] dark:bg-[#303030] px-3.5 py-2.5 rounded-[18px] rounded-bl-[4px] flex items-center gap-1">
            <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></span>
            <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce [animation-delay:0.4s]"></span>
        </div>
    </div>

    @if($messages->isEmpty())
        <div class="flex flex-col items-center justify-center h-full text-gray-400 text-sm gap-2">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.477 2 2 6.145 2 11.259c0 2.913 1.454 5.512 3.729 7.21V22l3.391-1.861c.92.255 1.892.393 2.88.393 5.523 0 10-4.145 10-9.259C22 6.145 17.523 2 12 2z"/>
                </svg>
            </div>
            <span>Say hi to {{ $user->name }}!</span>
        </div>
    @endif
</div>
