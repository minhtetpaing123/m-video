<div class="border-t border-gray-100 dark:border-gray-800 pt-3 mt-3 px-4"
     wire:key="post-footer-{{ $post->id }}"
     x-data="{ 
         openReactions: false,
         timeout: null,
         startPress() {
             this.timeout = setTimeout(() => {
                 this.openReactions = true;
             }, 400);
         },
         endPress() {
             clearTimeout(this.timeout);
         }
     }">

    <!-- Custom Animation CSS for Facebook-style Emoji Popup -->
    <style>
        @keyframes fbPopUp {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.3);
            }
            70% {
                transform: translateY(-8px) scale(1.1);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .animate-fb-pop {
            animation: fbPopUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
    </style>
     
    <!-- Count Info Section -->
    <div class="flex items-center justify-between text-xs text-gray-500 mb-2 select-none">
        <div class="flex items-center space-x-1">
            <span class="bg-blue-500 text-white rounded-full p-1 text-[10px]">
                {{ match($userReaction ?? null) {
                    'love' => '❤️',
                    'care' => '🥰',
                    'haha' => '😆',
                    'wow' => '😮',
                    'sad' => '😢',
                    'angry' => '😡',
                    default => ($isLiked ? '👍' : '👍')
                } }}
            </span>
            <span>{{ $likesCount }} {{ Str::plural('Like', $likesCount) }}</span>
        </div>
        <div class="flex space-x-3 items-center">
            <button wire:click="toggleComments" class="hover:underline">
                {{ $commentsCount }} {{ Str::plural('Comment', $commentsCount) }}
            </button>
            <span>{{ $sharesCount }} {{ Str::plural('Share', $sharesCount) }}</span>
            
            <!-- Views Count ထည့်သွင်းထားသောနေရာ -->
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ $post->views_count ?? 0 }}
            </span>
        </div>
    </div>

    <!-- Action Buttons Section -->
    <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-1 text-sm font-medium relative select-none">
        
        <!-- Like Button with Facebook-style Reaction Popup -->
        <div class="flex-1 relative select-none" @click.outside="openReactions = false">
            
            <!-- Floating Emoji Reactions Box with Live Animation -->
            <div 
                x-show="openReactions"
                class="absolute bottom-full left-0 mb-3 bg-white dark:bg-gray-800 shadow-2xl rounded-full px-3 py-2 flex items-center gap-2.5 border border-gray-200 dark:border-gray-700 z-50 shadow-black/15 select-none"
                style="display: none;"
            >
                <button wire:click="react('like')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.05s;" title="Like">👍</button>
                <button wire:click="react('love')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.1s;" title="Love">❤️</button>
                <button wire:click="react('care')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.15s;" title="Care">🥰</button>
                <button wire:click="react('haha')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.2s;" title="Haha">😆</button>
                <button wire:click="react('wow')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.25s;" title="Wow">😮</button>
                <button wire:click="react('sad')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.3s;" title="Sad">😢</button>
                <button wire:click="react('angry')" @click="openReactions = false;" class="animate-fb-pop hover:scale-140 hover:-translate-y-3 transition-transform text-2xl origin-bottom" style="animation-delay: 0.35s;" title="Angry">😡</button>
            </div>

            <!-- Main Like Button -->
            <button 
                @mousedown="startPress()" 
                @mouseup="endPress()" 
                @touchstart="startPress()" 
                @touchend="endPress()"
                @click="if(!openReactions) { $wire.toggleLike(); }"
                class="w-full flex items-center justify-center space-x-2 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors select-none {{ $isLiked ? 'text-blue-600 font-semibold' : 'text-gray-600 dark:text-gray-300' }}"
                style="-webkit-touch-callout: none; -webkit-user-select: none;"
            >
                <span class="text-base">
                    {{ match($userReaction ?? null) {
                        'love' => '❤️',
                        'care' => '🥰',
                        'haha' => '😆',
                        'wow' => '😮',
                        'sad' => '😢',
                        'angry' => '😡',
                        default => ($isLiked ? '👍' : '')
                    } }}
                </span>
                
                @if(!$isLiked)
                    <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                @endif

                <span>
                    {{ match($userReaction ?? null) {
                        'love' => 'Love',
                        'care' => 'Care',
                        'haha' => 'Haha',
                        'wow' => 'Wow',
                        'sad' => 'Sad',
                        'angry' => 'Angry',
                        default => 'Like'
                    } }}
                </span>
            </button>
        </div>

        <!-- Comment Button -->
        <button wire:click="toggleComments" class="flex-1 flex items-center justify-center space-x-2 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-600 dark:text-gray-300 select-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <span>Comment</span>
        </button>

        <!-- Share Button -->
        <button 
            type="button"
            @click="
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $post->title ?? 'Post' }}',
                        url: '{{ route('posts.show', $post->id) }}'
                    }).then(() => {
                        $wire.sharePost();
                    }).catch(() => {});
                } else {
                    navigator.clipboard.writeText('{{ route('posts.show', $post->id) }}');
                    alert('Link copied to clipboard!');
                    $wire.sharePost();
                }
            "
            class="flex-1 flex items-center justify-center space-x-2 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-600 dark:text-gray-300 select-none"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
            </svg>
            <span>Share</span>
        </button>
    </div>

    <!-- Comment Section Container -->
    @if($showComments)
        <div class="fixed inset-0 z-[9999]">
            <livewire:dashboard.post.comment-section :post="$post" :key="'comments-'.$post->id" />
        </div>
    @endif
</div>
