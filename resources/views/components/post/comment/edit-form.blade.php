@props(['comment', 'postId'])

<div class="edit-form hidden mt-2 ml-12" id="edit-form-{{ $comment->id }}">
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
                   class="edit-input w-full text-sm bg-gray-100 border border-gray-300 rounded-full px-4 py-2 pr-24 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                   value="{{ $comment->content }}"
                   data-comment-id="{{ $comment->id }}">
            <div class="absolute right-2 top-1/2 transform -translate-y-1/2 flex space-x-2">
                <button class="cancel-edit text-xs text-gray-500 hover:text-gray-700 px-2"
                        data-comment-id="{{ $comment->id }}">
                    Cancel
                </button>
                <button class="save-edit text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600"
                        data-comment-id="{{ $comment->id }}"
                        data-post-id="{{ $postId }}">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>