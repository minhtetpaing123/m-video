@props(['comment'])

<div class="flex items-start space-x-2 comment-item" data-comment-id="{{ $comment->id }}">
    {{-- Avatar --}}
    <div class="flex-shrink-0">
        <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden">
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
    </div>
    
    {{-- Comment Content --}}
    <div class="flex-1">
        <div class="bg-gray-100 rounded-2xl px-3 py-2 inline-block max-w-full">
            <span class="font-semibold text-sm">{{ $comment->user->name ?? 'User' }}</span>
            <span class="text-sm ml-1">{{ $comment->content }}</span>
        </div>
        
        {{-- Comment Actions --}}
        <div class="flex items-center space-x-4 mt-1 ml-2">
            <button class="text-xs font-semibold text-gray-500 hover:text-gray-700">Like</button>
            <button class="text-xs font-semibold text-gray-500 hover:text-gray-700">Reply</button>
            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>