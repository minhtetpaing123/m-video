@props(['post'])

<div class="flex items-center justify-between px-4 pt-3 pb-1">
    <div class="flex items-center space-x-2.5">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                @if($post->user && $post->user->avatar)
                    <img src="{{ asset('storage/' . $post->user->avatar) }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr($post->user->name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>
        </div>
        <div>
            <div class="font-semibold text-sm text-gray-900">{{ $post->user->name ?? 'Unknown' }}</div>
            <div class="flex items-center gap-1 text-xs text-gray-500">
                <span>{{ $post->created_at->diffForHumans() }}</span>
                <span>·</span>
                <span>🌐</span>
                @if($post->privacy === 'private') <span>🔒</span> @endif
            </div>
        </div>
    </div>

    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="5" r="2"/>
            <circle cx="12" cy="12" r="2"/>
            <circle cx="12" cy="19" r="2"/>
        </svg>
    </button>
</div>