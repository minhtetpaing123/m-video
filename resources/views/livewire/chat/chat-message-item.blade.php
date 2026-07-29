<div class="w-full flex flex-col">
    <div class="text-center my-1">
        <span class="text-[11px] font-semibold tracking-wider text-gray-400 dark:text-gray-500 uppercase"
              x-data="{ formattedTime: '' }"
              x-init="
                  const date = new Date('{{ $msg->created_at->toIso8601String() }}');
                  formattedTime = date.toLocaleDateString('en-US', { month: 'short', day: '2-digit' }).toUpperCase() 
                      + ' AT ' 
                      + date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
              "
              x-text="formattedTime">
            {{ $msg->created_at->format('M d \a\t g:i A') }}
        </span>
    </div>

    <div x-data="{ showTime: false, showMenu: false, showReactions: false }" class="w-full flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
        
        <div class="relative group flex items-end gap-1.5 max-w-[85%] sm:max-w-[75%] {{ $msg->sender_id === auth()->id() ? 'flex-row-reverse' : 'flex-row' }}">
            
            @if($msg->sender_id !== auth()->id())
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                     class="w-[28px] h-[28px] min-w-[28px] min-h-[28px] object-cover rounded-full shrink-0 mb-0.5">
            @endif

            <div class="flex flex-col relative {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                
                {{-- Emoji Reaction Bar --}}
                <div x-show="showReactions" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     @click.outside="showReactions = false"
                     x-cloak
                     class="absolute z-40 -top-11 {{ $msg->sender_id === auth()->id() ? 'right-0' : 'left-0' }} bg-white dark:bg-gray-800 shadow-lg rounded-full px-2 py-1 flex items-center gap-1.5 border border-gray-100 dark:border-gray-700">
                    @foreach(['❤️', '👍', '😂', '😮', '😢', '🙏'] as $emoji)
                        <button type="button"
                                @click.stop="
                                    if (window.navigator && window.navigator.vibrate) window.navigator.vibrate(15);
                                    $wire.reactToMessage('{{ $emoji }}');
                                    showReactions = false;
                                "
                                class="text-lg hover:scale-125 transform transition-transform duration-100 p-0.5 cursor-pointer">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>

                {{-- Reply Quote Box --}}
                @if($msg->replyTo)
                    <div class="text-[12px] text-gray-500 dark:text-gray-400 bg-gray-200/60 dark:bg-gray-800/80 px-3 py-1 rounded-t-xl border-l-2 border-blue-500 mb-0.5 max-w-full truncate">
                        <span class="font-bold text-[10px] text-blue-500 block">
                            {{ $msg->replyTo->sender_id === auth()->id() ? 'You' : $user->name }}
                        </span>
                        <span class="truncate block">{{ $msg->replyTo->message }}</span>
                    </div>
                @endif

                {{-- Message Bubble with Swipe & Long Press --}}
                <div x-data="{ 
                        startX: 0, 
                        startY: 0,
                        offsetX: 0, 
                        activeTouch: false,
                        pressTimer: null,
                        touchStart(e) {
                            this.startX = e.touches[0].clientX;
                            this.startY = e.touches[0].clientY;
                            this.activeTouch = true;

                            this.pressTimer = setTimeout(() => {
                                if (this.activeTouch && Math.abs(this.offsetX) < 3) {
                                    if (window.navigator && window.navigator.vibrate) window.navigator.vibrate(30);
                                    showReactions = true;
                                    this.activeTouch = false;
                                }
                            }, 350);
                        },
                        touchMove(e) {
                            if (!this.activeTouch) return;
                            let diffX = e.touches[0].clientX - this.startX;
                            let diffY = e.touches[0].clientY - this.startY;

                            if (Math.abs(diffY) > 4) {
                                clearTimeout(this.pressTimer);
                                this.activeTouch = false;
                                this.offsetX = 0;
                                return;
                            }

                            if (Math.abs(diffX) > 6) {
                                clearTimeout(this.pressTimer);
                                let maxPull = 50;
                                let sign = diffX < 0 ? -1 : 1;
                                this.offsetX = sign * Math.min(Math.abs(diffX), maxPull);
                            }
                        },
                        touchEnd() {
                            clearTimeout(this.pressTimer);
                            if (this.activeTouch && Math.abs(this.offsetX) >= 30) {
                                if (window.navigator && window.navigator.vibrate) window.navigator.vibrate(20);
                                $wire.setReply();
                            }
                            this.offsetX = 0;
                            this.activeTouch = false;
                        }
                     }"
                     @touchstart.passive="touchStart($event)"
                     @touchmove.passive="touchMove($event)"
                     @touchend="touchEnd()"
                     @contextmenu.prevent="showReactions = true"
                     @click="showTime = !showTime"
                     :style="offsetX !== 0 ? `transform: translate3d(${offsetX}px, 0, 0);` : `transform: translate3d(0, 0, 0); transition: transform 0.2s ease-out;`"
                     style="touch-action: pan-y;"
                     class="w-fit inline-block px-3.5 py-2 text-[15px] leading-normal break-words shadow-sm cursor-pointer select-none relative
                    @if($msg->deleted_for_everyone)
                        border border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-400 italic rounded-[18px] bg-transparent
                    @elseif($msg->sender_id === auth()->id())
                        bg-[#0084ff] text-white rounded-[18px] {{ $msg->replyTo ? 'rounded-tr-[4px]' : 'rounded-br-[4px]' }}
                    @else
                        bg-[#e4e6eb] dark:bg-[#303030] text-gray-900 dark:text-white rounded-[18px] {{ $msg->replyTo ? 'rounded-tl-[4px]' : 'rounded-bl-[4px]' }}
                    @endif">
                    <p class="whitespace-pre-line m-0 p-0 pointer-events-none">{{ $msg->message }}</p>

                    {{-- Reaction Display Badge --}}
                    @if($msg->reactions && $msg->reactions->count() > 0)
                        <div class="absolute -bottom-2.5 {{ $msg->sender_id === auth()->id() ? 'left-1' : 'right-1' }} bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-1.5 py-0.5 text-[11px] shadow-sm flex items-center gap-0.5">
                            @foreach($msg->reactions->pluck('emoji')->unique() as $emoji)
                                <span>{{ $emoji }}</span>
                            @endforeach
                            @if($msg->reactions->count() > 1)
                                <span class="text-[9px] text-gray-500 font-bold ml-0.5">{{ $msg->reactions->count() }}</span>
                            @endif
                        </div>
                    @endif
                </div>

            </div>

            {{-- Action Menu Button --}}
            @if(!$msg->deleted_for_everyone)
                <div class="relative opacity-0 group-hover:opacity-100 transition-opacity duration-150 shrink-0 self-center">
                    <button @click="showMenu = !showMenu" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </button>

                    <div x-show="showMenu" 
                         @click.outside="showMenu = false" 
                         x-cloak
                         class="absolute z-30 bottom-full mb-1 {{ $msg->sender_id === auth()->id() ? 'right-0' : 'left-0' }} bg-white dark:bg-gray-800 shadow-lg rounded-xl py-1 border border-gray-100 dark:border-gray-700 min-w-[120px] text-xs">
                        
                        <button @click="$wire.setReply(); showMenu = false" class="w-full text-left px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium">
                            Reply
                        </button>

                        @if($msg->sender_id === auth()->id())
                            <button @click="$wire.setEdit(); showMenu = false" class="w-full text-left px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium">
                                Edit
                            </button>
                            <button @click="$wire.deleteForEveryone(); showMenu = false" class="w-full text-left px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-500 font-medium">
                                Unsend
                            </button>
                        @endif

                        <button @click="$wire.deleteForMe(); showMenu = false" class="w-full text-left px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-500 font-medium">
                            Remove
                        </button>
                    </div>
                </div>
            @endif

        </div>

        {{-- Click Time Details --}}
        <div x-show="showTime" 
             x-transition
             x-cloak
             class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-1 px-1">
            @if($msg->sender_id === auth()->id())
                @if($msg->is_read)
                    <span x-data="{ formattedReadTime: '' }"
                          x-init="
                              const date = new Date('{{ ($msg->read_at ?? $msg->created_at)->toIso8601String() }}');
                              formattedReadTime = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                          "
                          x-text="'Seen ' + formattedReadTime">
                        Seen {{ $msg->read_at ? $msg->read_at->format('g:i A') : '' }}
                    </span>
                @else
                    <span x-data="{ formattedSentTime: '' }"
                          x-init="
                              const date = new Date('{{ $msg->created_at->toIso8601String() }}');
                              formattedSentTime = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                          "
                          x-text="'Sent ' + formattedSentTime">
                        Sent {{ $msg->created_at->format('g:i A') }}
                    </span>
                @endif
            @else
                <span x-data="{ formattedRecTime: '' }"
                      x-init="
                          const date = new Date('{{ $msg->created_at->toIso8601String() }}');
                          formattedRecTime = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                      "
                      x-text="'Received ' + formattedRecTime">
                    Received {{ $msg->created_at->format('g:i A') }}
                </span>
            @endif

            @if($msg->is_edited && !$msg->deleted_for_everyone)
                <span class="ml-1 text-[10px]">(Edited)</span>
            @endif
        </div>

        {{-- Last Message Status --}}
        @if ($isLast && $msg->sender_id === auth()->id() && !$msg->is_read)
            <span class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5 px-1">Sent</span>
        @elseif ($isLast && $msg->sender_id === auth()->id() && $msg->is_read)
            <span x-show="!showTime" 
                  x-data="{ formattedReadTime: '' }"
                  x-init="
                      const date = new Date('{{ ($msg->read_at ?? $msg->created_at)->toIso8601String() }}');
                      formattedReadTime = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                  "
                  x-text="'Seen ' + formattedReadTime"
                  class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5 px-1">
                Seen {{ $msg->read_at ? $msg->read_at->format('g:i A') : '' }}
            </span>
        @endif

    </div>
</div>
