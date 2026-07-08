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
            <div class="bg-gray-100 rounded-2xl px-3 py-2 inline-block max-w-full">
                <span class="font-semibold text-sm">{{ $comment->user->name ?? 'User' }}</span>
                
                @if($level > 0 && $comment->parent && $comment->parent->user)
                    <span class="text-xs text-blue-600 mx-1">→</span>
                    <span class="text-xs text-blue-600">@</span>
                    <span class="text-xs font-semibold text-blue-600">{{ $comment->parent->user->name }}</span>
                @endif
                
                <span class="text-sm ml-1">{{ $comment->content }}</span>
            </div>
            
            {{-- Comment Actions --}}
            <div class="flex items-center space-x-4 mt-1 ml-2">
                <button class="like-comment text-xs font-semibold text-gray-500 hover:text-blue-600" 
                        data-comment-id="{{ $comment->id }}">
                    Like
                </button>
                <button class="reply-button text-xs font-semibold text-gray-500 hover:text-blue-600" 
                        data-comment-id="{{ $comment->id }}"
                        data-username="{{ $comment->user->name ?? 'User' }}">
                    Reply
                </button>
                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Reply Form --}}
    <div class="reply-form hidden mt-2 {{ $level > 0 ? 'reply-indent-' . min($level + 1, 4) : 'ml-12' }}" 
         id="reply-form-{{ $comment->id }}">
        <div class="flex items-start space-x-2">
            <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0 overflow-hidden">
                @if(auth()->user() && auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 relative">
                <input type="text" 
                       class="reply-input w-full text-sm bg-gray-100 border border-gray-300 rounded-full px-4 py-2 pr-12 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Write a reply..."
                       data-comment-id="{{ $comment->id }}">
                <button class="reply-submit absolute right-2 top-1/2 transform -translate-y-1/2 text-blue-500 font-semibold text-sm px-3 hover:text-blue-700"
                        data-comment-id="{{ $comment->id }}"
                        data-parent-id="{{ $comment->id }}"
                        data-username="{{ $comment->user->name ?? 'User' }}">
                    Reply
                </button>
            </div>
        </div>
    </div>

    {{-- Nested Replies --}}
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="nested-replies mt-2" id="replies-{{ $comment->id }}">
            @foreach($comment->replies as $reply)
                <x-post.comment-thread 
                    :comment="$reply" 
                    :post-id="$postId" 
                    :level="$level + 1" 
                />
            @endforeach
        </div>
    @endif
</div>