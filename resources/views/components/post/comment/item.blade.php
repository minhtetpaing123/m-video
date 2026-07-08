@props(['comment', 'postId', 'level' => 0])

<div class="mb-3 comment-wrapper" data-comment-id="{{ $comment->id }}" id="comment-{{ $comment->id }}">
    {{-- Main Comment --}}
    <div class="flex items-start space-x-2 {{ $level > 0 ? 'reply-indent-' . min($level, 4) : '' }}">
        {{-- Avatar --}}
        <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0 overflow-hidden">
            @if($comment->user && $comment->user->avatar)
                <img src="{{ asset('storage/' . $comment->user->avatar) }}" 
                     alt="{{ $comment->user->name }}" 
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                    {{ substr($comment->user->name ?? 'U', 0, 1) }}
                </div>
            @endif
        </div>
        
        {{-- Comment Content --}}
        <div class="flex-1">
            <div class="bg-gray-100 rounded-2xl px-3 py-2 inline-block max-w-full" 
                 id="content-{{ $comment->id }}">
                <span class="font-semibold text-sm">{{ $comment->user->name ?? 'User' }}</span>
                
                @if($level > 0 && $comment->parent && $comment->parent->user)
                    <span class="text-xs text-blue-600 mx-1">→</span>
                    <span class="text-xs text-blue-600">@</span>
                    <span class="text-xs font-semibold text-blue-600">{{ $comment->parent->user->name }}</span>
                @endif
                
                <span class="text-sm ml-1 comment-text">{{ $comment->content }}</span>
            </div>
            
            {{-- Comment Actions --}}
            <x-post.comment.actions :comment="$comment" :post-id="$postId" />
        </div>
    </div>

    {{-- Edit Form --}}
    <x-post.comment.edit-form :comment="$comment" :post-id="$postId" />

    {{-- Reply Form --}}
    <x-post.comment.reply-form :comment="$comment" :post-id="$postId" :level="$level" />

    {{-- Nested Replies --}}
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="nested-replies mt-2" id="replies-{{ $comment->id }}">
            @foreach($comment->replies as $reply)
                <x-post.comment.item 
                    :comment="$reply" 
                    :post-id="$postId" 
                    :level="$level + 1" 
                />
            @endforeach
        </div>
    @endif
</div>