<div class="bg-black min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('posts.show', $post->id) }}" 
               wire:navigate
               class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Video
            </a>
        </div>
        
        {{-- Main Card --}}
        <div class="bg-gray-900/50 rounded-2xl border border-gray-800/50 overflow-hidden">
            
            {{-- Header --}}
            <div class="p-6 md:p-8 border-b border-gray-800/50">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white text-2xl font-bold">Video Info</h1>
                        <p class="text-gray-500 text-sm">Details about this video</p>
                    </div>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="p-6 md:p-8 space-y-6">
                
                {{-- Video Preview --}}
                <div class="flex items-center gap-4 p-4 bg-gray-800/30 rounded-xl">
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-800 flex-shrink-0">
                        {{-- ✅ Thumbnail အတွက် ပြင်ဆင်ထားတယ် --}}
                        @if($post->video_thumbnail_url)
                            <img src="{{ $post->video_thumbnail_url }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover">
                        @elseif($post->video_thumbnail)
                            <img src="{{ Storage::url($post->video_thumbnail) }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-white font-semibold text-lg truncate">
                            {{ $post->title ?? $post->content ?? 'Untitled' }}
                        </h2>
                        <p class="text-gray-400 text-sm">{{ $post->user->name ?? 'Unknown' }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-gray-500 text-xs">{{ $post->created_at->format('M d, Y') }}</span>
                            @if($post->category)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400">
                                    {{ $post->category_label }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Description --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                        About this video
                    </h3>
                    <div class="bg-gray-800/30 p-5 rounded-xl border border-gray-700/30">
                        <p class="text-gray-300 text-base leading-relaxed whitespace-pre-wrap">
                            {{ $post->description }}
                        </p>
                    </div>
                </div>
                
                {{-- Video Details Grid --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-3">Video Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="bg-gray-800/30 p-4 rounded-xl border border-gray-700/30">
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Category</p>
                            <p class="text-white font-medium">{{ $post->category_label ?? 'Uncategorized' }}</p>
                        </div>
                        <div class="bg-gray-800/30 p-4 rounded-xl border border-gray-700/30">
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Uploaded</p>
                            <p class="text-white font-medium">{{ $post->created_at->format('F j, Y') }}</p>
                        </div>
                        <div class="bg-gray-800/30 p-4 rounded-xl border border-gray-700/30">
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Views</p>
                            <p class="text-white font-medium">{{ number_format($post->views_count ?? 0) }}</p>
                        </div>
                        <div class="bg-gray-800/30 p-4 rounded-xl border border-gray-700/30">
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Likes</p>
                            <p class="text-white font-medium">{{ number_format($post->likes_count ?? 0) }}</p>
                        </div>
                        @if($post->is_mature)
                            <div class="bg-gray-800/30 p-4 rounded-xl border border-red-500/30 col-span-1 sm:col-span-2">
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Content Rating</p>
                                <p class="text-red-400 font-medium flex items-center gap-2">
                                    <span>🔞</span>
                                    18+ Mature Content
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Footer Actions --}}
            <div class="p-6 md:p-8 border-t border-gray-800/50 bg-gray-900/30">
                <div class="flex items-center gap-4 flex-wrap">
                    <a href="{{ route('posts.show', $post->id) }}" 
                       wire:navigate
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        Watch Video
                    </a>
                    <button onclick="window.print()" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                        </svg>
                        Print
                    </button>
                    <button onclick="shareVideo()" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareVideo() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: '{{ $post->title ?? "Video" }}',
            text: 'Check out this video!',
            url: url
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copied to clipboard!');
        }).catch(() => {
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Link copied to clipboard!');
        });
    }
}
</script>