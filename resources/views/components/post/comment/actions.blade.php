@props(['comment', 'postId'])

<div class="flex items-center space-x-2 mt-1 ml-2">
    <button class="like-comment text-xs font-semibold text-gray-500 hover:text-blue-600" 
            data-comment-id="{{ $comment->id }}">
        Like
    </button>
    
    <button class="reply-button text-xs font-semibold text-gray-500 hover:text-blue-600" 
            data-comment-id="{{ $comment->id }}"
            data-username="{{ $comment->user->name ?? 'User' }}">
        Reply
    </button>
    
    @if(auth()->id() === $comment->user_id)
        <button class="edit-comment text-xs font-semibold text-gray-500 hover:text-green-600"
                data-comment-id="{{ $comment->id }}">
            Edit
        </button>
        
        <button class="delete-comment text-xs font-semibold text-gray-500 hover:text-red-600"
                data-comment-id="{{ $comment->id }}">
            Delete
        </button>
    @endif
    
    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
</div>