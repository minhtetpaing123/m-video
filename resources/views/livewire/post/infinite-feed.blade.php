<div>
    <div class="space-y-6">
        @foreach($posts as $feedPost)
            <div wire:key="post-{{ $feedPost->id }}" class="bg-gray-900/30 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition">
                <a href="{{ route('posts.show', $feedPost->id) }}" class="block">
                    <div class="relative aspect-video bg-black overflow-hidden">
                        @if($feedPost->video_thumbnail_url)
                            <img src="{{ $feedPost->video_thumbnail_url }}" 
                                 alt="{{ $feedPost->title ?? 'Video' }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        @elseif($feedPost->image_url)
                            <img src="{{ $feedPost->image_url }}" 
                                 alt="{{ $feedPost->title ?? 'Image' }}" 
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/30 hover:bg-black/10 transition"></div>
                    </div>
                </a>
                
                <div class="p-4">
                    <a href="{{ route('posts.show', $feedPost->id) }}" class="text-white font-semibold hover:text-blue-400 transition line-clamp-2">
                        {{ $feedPost->title ?? $feedPost->content ?? 'Untitled' }}
                    </a>
                    
                    <div class="flex items-center gap-3 text-gray-400 text-sm mt-2 flex-wrap">
                        <span>👁️ {{ number_format($feedPost->views_count ?? 0) }}</span>
                        <span>• {{ $feedPost->created_at->diffForHumans() }}</span>
                        @if($feedPost->category)
                            <span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-0.5 rounded-full">
                                {{ $feedPost->category_label ?? $feedPost->category }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ substr($feedPost->user->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-gray-300 text-sm">{{ $feedPost->user->name ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Loading Indicator --}}
        <div wire:loading wire:target="loadMore" class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
            <p class="text-gray-400 text-sm mt-2">Loading more videos...</p>
        </div>

        {{-- Load More Trigger --}}
        @if($posts->hasMorePages())
            <div x-data x-init="
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        $wire.loadMore();
                    }
                }, { rootMargin: '200px' });
                observer.observe($el);
            " class="h-10"></div>
        @else
            <p class="text-gray-500 text-center py-4 text-sm">No more videos to load</p>
        @endif
    </div>
</div>