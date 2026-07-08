
<!-- resources/views/components/post/stats.blade.php -->
@props([
    'likesCount', 
    'commentsCount', 
    'sharesCount',
    'reactionSummary' => [],
    'postId' => null
])

<div class="px-3 py-2 flex justify-between text-xs sm:text-sm text-gray-500 border-b border-gray-200">
    {{-- Left side - Like Icons and Count --}}
    <div class="flex items-center space-x-1">
        @if(!empty($reactionSummary))
            <div class="flex -space-x-1">
                @foreach(array_slice(array_keys($reactionSummary), 0, 3) as $type)
                    <span class="reaction-icon inline-block transform hover:scale-110 transition-transform text-sm sm:text-base
                        @if($type == 'like') text-blue-500
                        @elseif($type == 'love') text-red-500
                        @elseif($type == 'care') text-yellow-500
                        @elseif($type == 'haha') text-yellow-500
                        @elseif($type == 'wow') text-yellow-500
                        @elseif($type == 'sad') text-yellow-500
                        @elseif($type == 'angry') text-orange-500
                        @endif">
                        @if($type == 'like') 👍
                        @elseif($type == 'love') ❤️
                        @elseif($type == 'care') 🤗
                        @elseif($type == 'haha') 😂
                        @elseif($type == 'wow') 😮
                        @elseif($type == 'sad') 😢
                        @elseif($type == 'angry') 😠
                        @endif
                    </span>
                @endforeach
            </div>
            <span class="likes-count font-medium" data-post-id="{{ $postId }}">{{ $likesCount }}</span>
        @else
            <span class="likes-count" data-post-id="{{ $postId }}">{{ $likesCount }} like{{ $likesCount != 1 ? 's' : '' }}</span>
        @endif
    </div>

    {{-- Right side - Comments and Shares --}}
    <div class="flex items-center space-x-3 sm:space-x-4">
        {{-- Comments Count --}}
        @if($commentsCount > 0)
            <span class="comments-count hover:text-blue-500 cursor-pointer transition-colors" 
                  data-post-id="{{ $postId }}">
                <span class="font-medium">{{ $commentsCount }}</span>
                <span class="hidden xs:inline"> comment{{ $commentsCount != 1 ? 's' : '' }}</span>
            </span>
        @else
            <span class="text-gray-400">0 comments</span>
        @endif

        {{-- Shares Count --}}
        @if($sharesCount > 0)
            <span class="shares-count hover:text-green-500 cursor-pointer transition-colors" 
                  data-post-id="{{ $postId }}">
                <span class="font-medium">{{ $sharesCount }}</span>
                <span class="hidden xs:inline"> share{{ $sharesCount != 1 ? 's' : '' }}</span>
            </span>
        @else
            <span class="text-gray-400 hidden xs:inline">0 shares</span>
        @endif
    </div>
</div>

<style>
/* Responsive breakpoints */
@media (max-width: 480px) {
    .xs\:inline {
        display: inline;
    }
    
    .text-xs {
        font-size: 0.7rem;
    }
}

/* Hover effects */
.comments-count, .shares-count {
    transition: color 0.2s ease;
}

.comments-count:hover {
    color: #3b82f6;
}

.shares-count:hover {
    color: #10b981;
}

/* Reaction icons animation */
.reaction-icon {
    transition: transform 0.2s ease;
}

.reaction-icon:hover {
    transform: scale(1.2);
    z-index: 10;
}

/* Touch-friendly for mobile */
@media (max-width: 640px) {
    .comments-count, .shares-count {
        min-height: 32px;
        display: inline-flex;
        align-items: center;
    }
}
</style>