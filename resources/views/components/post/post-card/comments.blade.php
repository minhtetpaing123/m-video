@props(['post'])

<div class="mt-2">
    {{-- Comment Form --}}
    @auth
        <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-2 mb-3">
            @csrf
            <input type="text" name="comment" placeholder="Write a comment..."
                   class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full">Post</button>
        </form>
    @else
        <p class="text-sm text-gray-500 mb-3"><a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to comment</p>
    @endauth

    {{-- Comments List --}}
    <div class="space-y-3">
        @forelse($post->comments as $comment)
            <div class="flex gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($comment->user && $comment->user->avatar)
                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ substr($comment->user->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="bg-gray-100 rounded-xl px-3 py-2">
                        <span class="font-semibold text-xs text-gray-800">{{ $comment->user->name ?? 'Unknown' }}</span>
                        <p class="text-sm text-gray-700">{{ $comment->comment }}</p>
                    </div>
                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                        @auth
                            <button class="hover:text-blue-600">Like</button>
                            <button class="hover:text-blue-600">Reply</button>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">No comments yet. Be the first!</p>
        @endforelse
    </div>
</div>