@props(['comment', 'postId', 'level'])

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
                   placeholder="Write a reply to {{ $comment->user->name ?? 'User' }}..."
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