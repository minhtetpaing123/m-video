<div id="post-{{ $post->id }}" 
     wire:poll.15s
     class="border-t border-gray-100 dark:border-gray-800 px-4 py-2 relative select-none"
     x-data="{ 
        showModal: @entangle('showComments'),
        showReactions: false,
        pressTimer: null,
        showShareModal: false,
        shareUrl: '{{ url('/posts/' . $post->id) }}',
        postTitle: '{{ addslashes($post->caption ?? $post->title ?? 'Check out this post!') }}',
        copied: false,

        startPress() {
            this.pressTimer = setTimeout(() => {
                this.showReactions = true;
            }, 300);
        },
        endPress() {
            clearTimeout(this.pressTimer);
        },
        doShare() {
            this.showShareModal = true;
        },
        copyToClipboard() {
            navigator.clipboard.writeText(this.shareUrl);
            this.copied = true;
            $wire.sharePost();
            setTimeout(() => { this.copied = false; this.showShareModal = false; }, 1500);
        }
     }"
     x-init="
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_post') == '{{ $post->id }}') {
            showModal = true;
        }
     "
     @open-comments-modal-{{ $post->id }}.window="showModal = true"
     @close-comments-modal-{{ $post->id }}.window="showModal = false">

    {{-- Database Columns / Tables မှ တိုက်ရိုက် Count များ ယူခြင်း --}}
    @php
        // Reactions (Likes) Count
        $actualLikesCount = \DB::table('reactions')->where('post_id', $post->id)->count();
        if($actualLikesCount === 0 && isset($post->likes_count)) {
            $actualLikesCount = $post->likes_count;
        }

        // Top 2 Reaction Types
        $calculatedTopReactions = \DB::table('reactions')
            ->where('post_id', $post->id)
            ->select('type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(2)
            ->pluck('type')
            ->toArray();

        // Comments Count
        $actualCommentsCount = \DB::table('comments')->where('post_id', $post->id)->count();
        if($actualCommentsCount === 0 && isset($post->comments_count)) {
            $actualCommentsCount = $post->comments_count;
        }

        // Shares Count
        $actualSharesCount = $sharesCount ?? $post->shares_count ?? 0;
    @endphp

    <!-- Top 2 Reactions Bar Display (Facebook Style) -->
    @if($actualLikesCount > 0 || $actualCommentsCount > 0 || $actualSharesCount > 0)
        <div class="flex items-center justify-between pb-2 mb-1 border-b border-gray-100 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400">
            <!-- Left: Top 2 Reaction Icons & Count -->
            <div class="flex items-center gap-1.5 cursor-pointer">
                @if($actualLikesCount > 0)
                    <div class="flex -space-x-1 overflow-hidden items-center">
                        @if(count($calculatedTopReactions) > 0)
                            @foreach($calculatedTopReactions as $type)
                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full ring-2 ring-white dark:ring-gray-900 text-[10px] leading-none bg-gray-100 dark:bg-gray-700">
                                    @switch($type)
                                        @case('love') ❤️ @break
                                        @case('care') 🥰 @break
                                        @case('haha') 😆 @break
                                        @case('wow')  😮 @break
                                        @case('sad')  😢 @break
                                        @case('angry') 😡 @break
                                        @default 👍
                                    @endswitch
                                </span>
                            @endforeach
                        @else
                            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full ring-2 ring-white dark:ring-gray-900 text-[10px] leading-none bg-gray-100 dark:bg-gray-700">
                                👍
                            </span>
                        @endif
                    </div>
                    <span class="font-medium text-gray-600 dark:text-gray-300">{{ $actualLikesCount }}</span>
                @endif
            </div>

            <!-- Right: Comments & Shares Count -->
            <div class="flex items-center gap-3">
                @if($actualCommentsCount > 0)
                    <span>{{ $actualCommentsCount }} {{ Str::plural('Comment', $actualCommentsCount) }}</span>
                @endif
                @if($actualSharesCount > 0)
                    <span>{{ $actualSharesCount }} {{ Str::plural('Share', $actualSharesCount) }}</span>
                @endif
            </div>
        </div>
    @endif

    <!-- Action Buttons Bar -->
    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-xs font-semibold relative select-none">
        
        <!-- Like Button -->
        <div class="relative select-none" 
             @mouseenter="showReactions = true" 
             @mouseleave="showReactions = false">
            
            <!-- Floating Reactions Popup Box -->
            <div 
                x-show="showReactions"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2 scale-90"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-90"
                class="absolute bottom-full left-0 mb-2 flex items-center gap-1 bg-white dark:bg-gray-800 p-1.5 rounded-full shadow-xl border border-gray-100 dark:border-gray-700 z-30 select-none"
                x-cloak
            >
                @php
                    $reactions = [
                        'like' => '👍',
                        'love' => '❤️',
                        'care' => '🥰',
                        'haha' => '😆',
                        'wow'  => '😮',
                        'sad'  => '😢',
                        'angry'=> '😡',
                    ];
                @endphp

                @foreach($reactions as $key => $emoji)
                    <button 
                        wire:click="setReaction('{{ $key }}')" 
                        @click="showReactions = false"
                        type="button" 
                        class="hover:scale-125 transition-transform duration-200 p-1 text-xl select-none" 
                        title="{{ ucfirst($key) }}"
                    >{{ $emoji }}</button>
                @endforeach
            </div>

            <!-- Main Reaction Button -->
            <button 
                wire:click="toggleLike" 
                @touchstart="startPress()"
                @touchend="endPress()"
                @touchcancel="endPress()"
                @mousedown="startPress()"
                @mouseup="endPress()"
                @mouseleave="endPress()"
                @contextmenu.prevent
                type="button" 
                class="flex items-center gap-1.5 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors select-none touch-none
                    {{ $isLiked && $userReaction === 'like' ? 'text-blue-600 dark:text-blue-400' : '' }}
                    {{ $isLiked && $userReaction === 'love' ? 'text-red-500' : '' }}
                    {{ $isLiked && in_array($userReaction, ['care', 'haha', 'wow', 'sad']) ? 'text-amber-500' : '' }}
                    {{ $isLiked && $userReaction === 'angry' ? 'text-orange-600' : '' }}"
            >
                @if($isLiked && $userReaction)
                    @switch($userReaction)
                        @case('love') <span class="text-base">❤️</span> @break
                        @case('care') <span class="text-base">🥰</span> @break
                        @case('haha') <span class="text-base">😆</span> @break
                        @case('wow')  <span class="text-base">😮</span> @break
                        @case('sad')  <span class="text-base">😢</span> @break
                        @case('angry')<span class="text-base">😡</span> @break
                        @default
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                    @endswitch
                    <span class="capitalize">{{ $userReaction }}</span>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    <span>Like</span>
                @endif
            </button>
        </div>

        <!-- Comment Button -->
        <button @click="showModal = true" type="button" class="flex items-center gap-1.5 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors select-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>Comment</span>
        </button>

        <!-- Share Button -->
        <button @click="doShare()" type="button" class="flex items-center gap-1.5 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors select-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            <span>Share</span>
        </button>
    </div>

    <!-- Facebook Style Bottom Sheet Share Modal -->
    <div 
        x-show="showShareModal" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-full"
        @click.away="showShareModal = false"
        class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/60 backdrop-blur-sm"
        style="display: none;"
        x-cloak
    >
        <div class="bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-md w-full p-4 border border-gray-100 dark:border-gray-700 mb-0 sm:mb-auto">
            
            <!-- Modal Drag Handle & Header -->
            <div class="w-10 h-1 bg-gray-300 dark:bg-gray-600 rounded-full mx-auto mb-3 sm:hidden"></div>
            
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Share to</h3>
                <button @click="showShareModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg">✕</button>
            </div>

            <!-- Share Options Grid -->
            <div class="grid grid-cols-4 gap-4 py-5 text-center">
                
                <!-- Copy Link -->
                <button @click="copyToClipboard()" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400" x-text="copied ? 'Copied!' : 'Copy link'"></span>
                </button>

                <!-- Facebook -->
                <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl)" target="_blank" @click="$wire.sharePost(); showShareModal = false;" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">Facebook</span>
                </a>

                <!-- TikTok -->
                <button @click="copyToClipboard(); window.open('https://www.tiktok.com', '_blank');" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-black dark:bg-gray-900 flex items-center justify-center text-white group-hover:scale-105 transition-transform shadow-md">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.525.001c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.82.56-1.36 1.52-1.38 2.51-.01.53.11 1.07.36 1.54.49.95 1.53 1.57 2.6 1.61.85.05 1.72-.21 2.39-.74.72-.56 1.18-1.42 1.25-2.33.04-3.15.02-6.3.02-9.45.02-1.41.02-2.82.02-4.23z"/></svg>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">TikTok</span>
                </button>

                <!-- Telegram -->
                <a :href="'https://t.me/share/url?url=' + encodeURIComponent(shareUrl)" target="_blank" @click="$wire.sharePost(); showShareModal = false;" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center text-sky-500 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.56 8.16l-1.97 9.28c-.15.67-.54.83-1.1.52l-3.02-2.23-1.46 1.4c-.16.16-.3.3-.61.3l.22-3.1 5.64-5.1c.25-.22-.05-.34-.38-.12L7.87 13.4l-3-.94c-.65-.2-.66-.65.14-.97l11.73-4.52c.54-.2 1.02.13.82.82z"/></svg>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">Telegram</span>
                </a>

            </div>
        </div>
    </div>

    <!-- Comment Modal Container -->
    <template x-if="showModal">
        <livewire:dashboard.post.comment-section :post="$post" :wire:key="'comments-'.$post->id" />
    </template>
</div>
