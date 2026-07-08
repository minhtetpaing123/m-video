
@props([
    'postId',
    'initialLiked' => false,
    'likesCount' => 0,
    'commentsCount' => 0,
    'sharesCount' => 0,
    'reactionType' => null,
    'postUrl' => null
])

@php
    $reactionEmojis = [
        'like' => '👍',
        'love' => '❤️',
        'care' => '🤗',
        'haha' => '😂',
        'wow' => '😮',
        'sad' => '😢',
        'angry' => '😠'
    ];
    
    $reactionAnimations = [
        'like' => 'like-animation infinite',
        'love' => 'heart-animation infinite',
        'care' => 'care-animation infinite',
        'haha' => 'laugh-animation infinite',
        'wow' => 'wow-animation infinite',
        'sad' => 'sad-animation infinite',
        'angry' => 'angry-animation infinite'
    ];
    
    $reactionColors = [
        'like' => 'text-blue-500',
        'love' => 'text-red-500',
        'care' => 'text-yellow-500',
        'haha' => 'text-yellow-500',
        'wow' => 'text-yellow-500',
        'sad' => 'text-blue-500',
        'angry' => 'text-orange-500'
    ];
    
    $currentEmoji = $reactionType ? $reactionEmojis[$reactionType] : '👍';
    $currentAnimation = $reactionType ? $reactionAnimations[$reactionType] : '';
    $currentColor = $reactionType ? $reactionColors[$reactionType] : 'text-blue-500';
    $shareUrl = $postUrl ?? url()->current();
@endphp

<div class="flex justify-around border-t border-gray-200 pt-1 relative">
    {{-- Like Button with Always Animated Emoji --}}
    <div class="like-container relative flex-1">
        <button type="button"
                class="like-button w-full flex items-center justify-center py-2 sm:py-3 rounded hover:bg-gray-100 text-xs sm:text-sm text-gray-600 transition group touch-manipulation"
                data-post-id="{{ $postId }}"
                data-liked="{{ $initialLiked ? 'true' : 'false' }}"
                data-reaction="{{ $reactionType ?? 'like' }}">
            @if($initialLiked && $reactionType)
                <span class="mr-1 text-base sm:text-lg emoji-animation {{ $currentAnimation }}" style="display: inline-block;">{{ $currentEmoji }}</span>
                <span class="like-text {{ $currentColor }} hidden xs:inline">{{ ucfirst($reactionType) }}</span>
            @else
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 like-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
                <span class="like-text text-gray-600 hidden xs:inline">Like</span>
            @endif
            @if($likesCount > 0)
                <span class="ml-1 text-xs text-gray-500 like-count font-medium">{{ $likesCount }}</span>
            @endif
        </button>

        {{-- Emoji Picker --}}
        <div class="emoji-picker hidden absolute bottom-full left-0 sm:left-1/2 sm:transform sm:-translate-x-1/2 mb-2 bg-white/95 backdrop-blur-sm rounded-full shadow-xl px-2 py-1.5 sm:px-3 sm:py-2 border border-gray-100 space-x-1 sm:space-x-2 z-50">
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="like" title="Like">👍</button>
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="love" title="Love">❤️</button>
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="care" title="Care">🤗</button>
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="haha" title="Haha">😂</button>
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="wow" title="Wow">😮</button>
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="sad" title="Sad">😢</button>
            <button class="emoji-option hover:scale-150 transition-all duration-200 px-1 text-base sm:text-xl emoji-hover filter drop-shadow-lg" data-reaction="angry" title="Angry">😠</button>
        </div>
    </div>

    {{-- Comment Button --}}
    <button type="button"
            class="comment-toggle flex-1 flex items-center justify-center py-2 sm:py-3 rounded hover:bg-gray-100 text-gray-600 text-xs sm:text-sm touch-manipulation group"
            data-post-id="{{ $postId }}">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="hidden xs:inline group-hover:text-blue-500 transition-colors">Comment</span>
        @if($commentsCount > 0)
            <span class="ml-1 text-xs text-gray-500 comment-count font-medium">{{ $commentsCount }}</span>
        @endif
    </button>

    {{-- Share Button --}}
    <div class="share-container relative flex-1">
        <button type="button"
                class="share-button w-full flex items-center justify-center py-2 sm:py-3 rounded hover:bg-gray-100 text-gray-600 text-xs sm:text-sm touch-manipulation group"
                data-post-id="{{ $postId }}">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            <span class="hidden xs:inline group-hover:text-green-500 transition-colors">Share</span>
            @if($sharesCount > 0)
                <span class="ml-1 text-xs text-gray-500 share-count font-medium">{{ $sharesCount }}</span>
            @endif
        </button>

        {{-- Share Dropdown --}}
        <div class="share-dropdown hidden absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl py-2 border border-gray-100 z-50 w-44 sm:w-48 md:w-52 max-w-[90vw]">
            {{-- Facebook --}}
            <button class="share-option w-full text-left px-3 sm:px-4 py-2.5 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 flex items-center gap-2 sm:gap-3 transition-all duration-200" data-platform="facebook" data-post-id="{{ $postId }}">
                <span class="text-blue-600 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </span>
                <span class="text-xs sm:text-sm font-medium text-gray-700">Facebook</span>
            </button>
            
            {{-- X (Twitter) --}}
            <button class="share-option w-full text-left px-3 sm:px-4 py-2.5 hover:bg-gradient-to-r hover:from-gray-50 hover:to-slate-50 flex items-center gap-2 sm:gap-3 transition-all duration-200" data-platform="twitter" data-post-id="{{ $postId }}">
                <span class="text-gray-900 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </span>
                <span class="text-xs sm:text-sm font-medium text-gray-700">X (Twitter)</span>
            </button>
            
            {{-- WhatsApp --}}
            <button class="share-option w-full text-left px-3 sm:px-4 py-2.5 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 flex items-center gap-2 sm:gap-3 transition-all duration-200" data-platform="whatsapp" data-post-id="{{ $postId }}">
                <span class="text-green-500 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.125.554 4.118 1.525 5.85L.525 23.475l5.75-1.525C8.042 22.936 9.98 23.5 12 23.5c6.627 0 12-5.373 12-12S18.627 0 12 0z"/>
                    </svg>
                </span>
                <span class="text-xs sm:text-sm font-medium text-gray-700">WhatsApp</span>
            </button>
            
            {{-- Telegram --}}
            <button class="share-option w-full text-left px-3 sm:px-4 py-2.5 hover:bg-gradient-to-r hover:from-sky-50 hover:to-blue-50 flex items-center gap-2 sm:gap-3 transition-all duration-200" data-platform="telegram" data-post-id="{{ $postId }}">
                <span class="text-blue-400 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.125 10.004c-.158.728-.574.91-1.172.57l-3.22-2.38-1.555 1.5c-.172.172-.317.317-.653.317l.23-3.27 5.945-5.372c.26-.23-.057-.36-.402-.13l-7.36 4.63-3.17-1.02c-.69-.22-.704-.69.144-1.02l12.33-4.75c.575-.23 1.08.14.89 1.02z"/>
                    </svg>
                </span>
                <span class="text-xs sm:text-sm font-medium text-gray-700">Telegram</span>
            </button>
            
            {{-- TikTok --}}
            <button class="share-option w-full text-left px-3 sm:px-4 py-2.5 hover:bg-gradient-to-r hover:from-gray-50 hover:to-slate-50 flex items-center gap-2 sm:gap-3 transition-all duration-200" data-platform="tiktok" data-post-id="{{ $postId }}">
                <span class="text-black flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M19.321 5.562a5.122 5.122 0 0 1-3.414-1.326 5.13 5.13 0 0 1-1.5-2.892 5.01 5.01 0 0 1-.107-.994h-3.917v13.611a2.968 2.968 0 0 1-.887 2.102 2.983 2.983 0 0 1-4.228 0 2.985 2.985 0 0 1 0-4.225 2.987 2.987 0 0 1 3.327-.583V8.268a6.815 6.815 0 0 0-2.086.193 6.857 6.857 0 0 0-3.936 2.428 6.81 6.81 0 0 0-1.51 4.437c0 3.759 3.053 6.807 6.822 6.807 3.768 0 6.822-3.048 6.822-6.807V8.75a9.05 9.05 0 0 0 5.313 1.713V6.449c-.008 0-.016.002-.023.002-.106 0-.212-.017-.315-.028-.09-.01-.178-.021-.265-.034-.011 0-.021-.004-.031-.005-.066-.009-.13-.022-.194-.033-.007 0-.013-.002-.019-.003-.062-.011-.123-.025-.184-.039-.005 0-.01-.001-.014-.002-.058-.014-.114-.03-.17-.046-.003 0-.007-.001-.01-.002-.055-.017-.109-.035-.163-.054-.002 0-.004-.001-.006-.002-.052-.019-.104-.04-.156-.062l-.003-.001c-.048-.02-.095-.042-.142-.064-.002-.001-.003-.001-.005-.002-.046-.022-.092-.046-.138-.07-.001 0-.002-.001-.003-.001-.043-.023-.086-.048-.129-.074-.003-.001-.005-.002-.008-.003-.041-.024-.081-.05-.122-.076l-.01-.006c-.039-.025-.078-.052-.117-.079l-.014-.009c-.036-.025-.072-.052-.108-.079l-.018-.013c-.033-.025-.065-.051-.098-.077l-.021-.018c-.03-.025-.059-.05-.088-.076l-.024-.022c-.027-.024-.053-.049-.079-.074l-.027-.027z"/>
                    </svg>
                </span>
                <span class="text-xs sm:text-sm font-medium text-gray-700">TikTok</span>
            </button>
            
            <div class="border-t border-gray-100 my-1"></div>
            
            {{-- Copy Link --}}
            <button class="share-option w-full text-left px-3 sm:px-4 py-2.5 hover:bg-gradient-to-r hover:from-gray-50 hover:to-slate-50 flex items-center gap-2 sm:gap-3 transition-all duration-200" data-platform="copy" data-post-id="{{ $postId }}">
                <span class="text-gray-600 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                    </svg>
                </span>
                <span class="text-xs sm:text-sm font-medium text-gray-700">Copy Link</span>
            </button>
        </div>
    </div>
</div>

<style>
/* Animation Keyframes - Always Running */
@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

@keyframes heartBeat {
    0% { transform: scale(1); }
    14% { transform: scale(1.3); }
    28% { transform: scale(1); }
    42% { transform: scale(1.3); }
    70% { transform: scale(1); }
}

@keyframes laugh {
    0%, 100% { transform: translateY(0) scale(1); }
    25% { transform: translateY(-3px) scale(1.1); }
    50% { transform: translateY(0) scale(1); }
    75% { transform: translateY(-2px) scale(1.05); }
}

@keyframes wow {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.3); }
}

@keyframes sad {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-2px); }
}

@keyframes angry {
    0%, 100% { transform: translateX(0) scale(1); }
    25% { transform: translateX(-2px) scale(1.05); }
    75% { transform: translateX(2px) scale(1.05); }
}

@keyframes care {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); }
}

/* Animation Classes - Infinite by default */
.emoji-animation {
    display: inline-block;
    animation-duration: 1.5s;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}

.like-animation {
    animation-name: bounce;
}

.heart-animation {
    animation-name: heartBeat;
    color: #f43f5e;
}

.laugh-animation {
    animation-name: laugh;
}

.wow-animation {
    animation-name: wow;
}

.sad-animation {
    animation-name: sad;
}

.angry-animation {
    animation-name: angry;
    color: #dc2626;
}

.care-animation {
    animation-name: care;
}

/* Existing styles */
@media (max-width: 480px) {
    .xs\:inline {
        display: inline;
    }
}

.touch-manipulation {
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}

.like-container, .share-container {
    position: relative;
}

.emoji-picker {
    position: absolute;
    bottom: 100%;
    left: 0;
    margin-bottom: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border-radius: 9999px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    padding: 6px 12px;
    border: 1px solid rgba(255, 255, 255, 0.5);
    display: flex;
    gap: 4px;
    z-index: 50;
}

@media (min-width: 640px) {
    .emoji-picker {
        left: 50%;
        transform: translateX(-50%);
        padding: 8px 16px;
        gap: 8px;
    }
}

.share-dropdown {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    margin-bottom: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    padding: 8px 0;
    border: 1px solid rgba(255, 255, 255, 0.5);
    width: 180px;
    max-width: 90vw;
    z-index: 50;
}

@media (min-width: 640px) {
    .share-dropdown {
        width: 200px;
    }
}

.emoji-picker.hidden, .share-dropdown.hidden {
    display: none;
}

.emoji-option {
    transition: transform 0.2s;
    cursor: pointer;
    background: none;
    border: none;
    padding: 4px 8px;
    font-size: 1.25rem;
}

.emoji-option:hover {
    transform: scale(1.3);
}

.share-option {
    transition: all 0.2s;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.share-option:hover {
    background: linear-gradient(to right, #f9fafb, #f3f4f6);
}

@media (max-width: 640px) {
    .like-button, .comment-toggle, .share-button {
        min-height: 48px;
    }
    
    .share-dropdown {
        max-height: 70vh;
        overflow-y: auto;
    }
}
</style>

<script>
$(document).ready(function() {
    console.log('Post ID: {{ $postId }}, Reaction: {{ $reactionType }}');
    
    // Remove existing handlers
    $(document).off('click', '.like-button');
    $(document).off('click', '.emoji-option');
    $(document).off('click', '.share-button');
    $(document).off('click', '.share-option');
    
    // ========== LIKE FUNCTIONALITY ==========
    $(document).on('click', '.like-button', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const container = $(this).closest('.like-container');
        const picker = container.find('.emoji-picker');
        
        $('.emoji-picker').not(picker).addClass('hidden');
        $('.share-dropdown').addClass('hidden');
        picker.toggleClass('hidden');
    });
    
    $(document).on('click', '.emoji-option', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const reaction = $(this).data('reaction');
        const container = $(this).closest('.like-container');
        const button = container.find('.like-button');
        const postId = button.data('post-id');
        
        container.find('.emoji-picker').addClass('hidden');
        
        // Add click animation
        $(this).addClass('scale-150');
        setTimeout(() => $(this).removeClass('scale-150'), 200);
        
        $.ajax({
            url: `/posts/${postId}/react`,
            method: 'POST',
            data: {
                type: reaction,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    updateLikeButton(button, reaction, response.likes_count);
                }
            },
            error: function(xhr) {
                console.error('Reaction error:', xhr);
            }
        });
    });
    
    // ========== SHARE FUNCTIONALITY ==========
    $(document).on('click', '.share-button', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const container = $(this).closest('.share-container');
        const dropdown = container.find('.share-dropdown');
        
        $('.share-dropdown').not(dropdown).addClass('hidden');
        $('.emoji-picker').addClass('hidden');
        dropdown.toggleClass('hidden');
    });
    
    $(document).on('click', '.share-option', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const platform = $(this).data('platform');
        const postId = $(this).data('post-id');
        const shareUrl = window.location.href;
        const postText = 'Check out this post!';
        
        $(this).closest('.share-dropdown').addClass('hidden');
        
        shareToPlatform(platform, shareUrl, postText, postId);
    });
    
    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.like-container').length && 
            !$(e.target).closest('.share-container').length) {
            $('.emoji-picker').addClass('hidden');
            $('.share-dropdown').addClass('hidden');
        }
    });
    
    // ========== HELPER FUNCTIONS ==========
    function updateLikeButton(button, reaction, count) {
        const emojis = {
            'like': '👍', 'love': '❤️', 'care': '🤗',
            'haha': '😂', 'wow': '😮', 'sad': '😢', 'angry': '😠'
        };
        
        const animations = {
            'like': 'like-animation infinite',
            'love': 'heart-animation infinite',
            'care': 'care-animation infinite',
            'haha': 'laugh-animation infinite',
            'wow': 'wow-animation infinite',
            'sad': 'sad-animation infinite',
            'angry': 'angry-animation infinite'
        };
        
        const colors = {
            'like': 'text-blue-500',
            'love': 'text-red-500',
            'care': 'text-yellow-500',
            'haha': 'text-yellow-500',
            'wow': 'text-yellow-500',
            'sad': 'text-blue-500',
            'angry': 'text-orange-500'
        };
        
        button.data('liked', 'true');
        button.data('reaction', reaction);
        
        const reactionText = reaction.charAt(0).toUpperCase() + reaction.slice(1);
        button.html(`
            <span class="mr-1 text-base sm:text-lg emoji-animation ${animations[reaction]}" style="display: inline-block;">${emojis[reaction]}</span>
            <span class="like-text ${colors[reaction]} hidden xs:inline">${reactionText}</span>
            ${count > 0 ? `<span class="ml-1 text-xs text-gray-500 like-count font-medium">${count}</span>` : ''}
        `);
    }
    
    function shareToPlatform(platform, url, text, postId) {
        let shareLink = '';
        
        switch(platform) {
            case 'facebook':
                shareLink = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                break;
            case 'twitter':
                shareLink = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                break;
            case 'whatsapp':
                shareLink = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
                break;
            case 'telegram':
                shareLink = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
                break;
            case 'tiktok':
                shareLink = `https://www.tiktok.com/share?url=${encodeURIComponent(url)}`;
                break;
            case 'copy':
                copyToClipboard(url);
                showToast('Link copied to clipboard!');
                return;
        }
        
        if (shareLink) {
            window.open(shareLink, '_blank', 'width=600,height=400');
            
            $.ajax({
                url: `/posts/${postId}/share`,
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        updateShareCount(postId, response.shares_count);
                        showToast('Shared successfully!');
                    }
                },
                error: function(xhr) {
                    console.error('Share count error:', xhr);
                    showToast('Share failed', 'error');
                }
            });
        }
    }
    
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }
    
    function showToast(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const toast = $(`<div class="fixed top-5 left-1/2 transform -translate-x-1/2 ${bgColor} text-white px-6 py-3 rounded-xl shadow-2xl z-[10001] animate-bounce font-medium">${message}</div>`);
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, function() { $(this).remove(); }), 2000);
    }
    
    function updateShareCount(postId, count) {
        const shareButton = $(`.share-button[data-post-id="${postId}"]`);
        let countSpan = shareButton.find('.share-count');
        
        if (count > 0) {
            if (countSpan.length) {
                countSpan.text(count);
            } else {
                shareButton.append(`<span class="ml-1 text-xs text-gray-500 share-count font-medium">${count}</span>`);
            }
        } else {
            countSpan.remove();
        }
    }
});
</script>