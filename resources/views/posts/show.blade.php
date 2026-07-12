{{-- resources/views/posts/show.blade.php --}}
@extends('layouts.home-layout')

@section('content')
<div class="bg-black min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- ============================================ --}}
            {{-- MAIN CONTENT - LEFT SIDE (2/3) --}}
            {{-- ============================================ --}}
            <div class="lg:col-span-2">
                @if($post->video)
                    {{-- ============================================ --}}
                    {{-- VIDEO PLAYER --}}
                    {{-- ============================================ --}}
                    <div class="block w-full overflow-hidden clear-both">
                        <x-common.video-player 
                            :src="$post->video" 
                            :src_1080="$post->video_1080 ?? null"
                            :src_720="$post->video_720 ?? null"
                            :src_480="$post->video_480 ?? null"
                            :src_360="$post->video_360 ?? null"
                            :src_240="$post->video_240 ?? null"
                            :src_144="$post->video_144 ?? null"
                            :poster="$post->video_thumbnail ? Storage::url($post->video_thumbnail) : ''"
                            :autoplay="false"
                            :title="$post->title ?? $post->content ?? 'Untitled'"
                            :views="number_format($post->views_count ?? 0)"
                            :time="$post->created_at->diffForHumans()"
                        />
                    </div>
                    
                    {{-- ============================================ --}}
                    {{-- VIDEO INFO --}}
                    {{-- ============================================ --}}
                    <div class="mt-4">
                        <h1 class="text-white text-xl md:text-2xl font-bold">
                            {{ $post->title ?? $post->content ?? 'Untitled' }}
                        </h1>
                        <div class="flex items-center gap-2 text-gray-400 text-sm mt-1">
                            <span>{{ number_format($post->views_count ?? 0) }} views</span>
                            <span>•</span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    
                    {{-- ============================================ --}}
                    {{-- ACTION BUTTONS (Like, Comment, Share, Save) --}}
                    {{-- ============================================ --}}
                    <div class="flex items-center gap-4 mt-4 flex-wrap">
                        {{-- Like Button --}}
                        @auth
                            <button class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                </svg>
                                <span>{{ number_format($post->likes_count ?? 0) }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                                </svg>
                                <span>{{ number_format($post->likes_count ?? 0) }}</span>
                            </a>
                        @endauth
                        
                        {{-- Comment Button --}}
                        <button onclick="document.getElementById('comment-section').scrollIntoView({behavior: 'smooth'})" 
                                class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                            </svg>
                            <span>{{ number_format($post->comments_count ?? 0) }}</span>
                        </button>
                        
                        {{-- Share Button --}}
                        <button onclick="shareVideo()" class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                            </svg>
                            Share
                        </button>
                        
                        {{-- Save Button --}}
                        @auth
                            <button id="saveBtn-{{ $post->id }}" 
                                    class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                                </svg>
                                Save
                            </button>
                        @endauth
                    </div>
                    
                    {{-- ============================================ --}}
                    {{-- CHANNEL INFO --}}
                    {{-- ============================================ --}}
                    <div class="flex items-center justify-between py-4 border-y border-gray-800 mt-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($post->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">{{ $post->user->name ?? 'Unknown' }}</h3>
                                <span class="text-gray-400 text-sm">{{ number_format($post->user->subscribers_count ?? 0) }} subscribers</span>
                            </div>
                        </div>
                        
                        @auth
                            <button class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full transition">
                                Subscribe
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full transition">
                                Subscribe
                            </a>
                        @endauth
                    </div>
                    
                    {{-- ============================================ --}}
                    {{-- DESCRIPTION --}}
                    {{-- ============================================ --}}
                    @if($post->content)
                        <div class="mt-4 p-4 bg-gray-800/50 rounded-xl">
                            <p class="text-gray-300 whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>
                    @endif
                    
                    {{-- ============================================ --}}
                    {{-- COMMENTS SECTION --}}
                    {{-- ============================================ --}}
                    <div id="comment-section" class="mt-8">
                        <h3 class="text-white text-xl font-bold mb-4">
                            Comments ({{ number_format($post->comments_count ?? 0) }})
                        </h3>
                        
                        {{-- Comment Form --}}
                        @auth
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-3 mb-6">
                                @csrf
                                <div class="flex-1">
                                    <input type="text" 
                                           name="comment" 
                                           placeholder="Write a comment..."
                                           class="w-full bg-gray-800 text-white rounded-full px-6 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           required>
                                </div>
                                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full transition">
                                    Comment
                                </button>
                            </form>
                        @else
                            <p class="text-gray-400 text-sm mb-6">
                                <a href="{{ route('login') }}" class="text-blue-400 hover:underline">Login</a> to comment
                            </p>
                        @endauth
                        
                        {{-- Comments List --}}
                        <div class="space-y-4">
                            @forelse($post->comments as $comment)
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ substr($comment->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-white font-semibold text-sm">{{ $comment->user->name ?? 'Unknown' }}</span>
                                            <span class="text-gray-500 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-300 text-sm mt-1">{{ $comment->comment }}</p>
                                        
                                        @auth
                                            <div class="flex items-center gap-4 mt-1">
                                                <button class="text-gray-500 hover:text-white text-xs transition">Like</button>
                                                <button class="text-gray-500 hover:text-white text-xs transition">Reply</button>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center py-8">No comments yet. Be the first to comment!</p>
                            @endforelse
                        </div>
                    </div>
                    
                @elseif($post->image)
                    {{-- ============================================ --}}
                    {{-- IMAGE DISPLAY --}}
                    {{-- ============================================ --}}
                    <div class="bg-black rounded-xl overflow-hidden shadow-2xl">
                        <img src="{{ Storage::url($post->image) }}" 
                             alt="{{ $post->title ?? 'Post image' }}" 
                             class="w-full max-h-[600px] object-contain">
                    </div>
                @endif
            </div>
            
            {{-- ============================================ --}}
            {{-- SIDEBAR - RIGHT SIDE (1/3) --}}
            {{-- ============================================ --}}
            <div class="lg:col-span-1">
                <h3 class="text-white text-lg font-bold mb-4">Recommended Videos</h3>
                
                {{-- Recommended Videos List --}}
                @php
                    $recommended = App\Models\Post::where('id', '!=', $post->id)
                        ->where('privacy', 'public')
                        ->whereNotNull('video')
                        ->latest()
                        ->limit(10)
                        ->get();
                @endphp
                
                <div class="space-y-3">
                    @forelse($recommended as $recommend)
                        <a href="{{ route('posts.show', $recommend->id) }}" class="flex gap-3 group">
                            <div class="w-40 flex-shrink-0">
                                <div class="relative aspect-video bg-gray-800 rounded-lg overflow-hidden">
                                    @if($recommend->video_thumbnail)
                                        <img src="{{ Storage::url($recommend->video_thumbnail) }}" 
                                             alt="{{ $recommend->title ?? 'Video' }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white text-sm font-medium line-clamp-2 group-hover:text-blue-400 transition">
                                    {{ $recommend->title ?? $recommend->content ?? 'Untitled' }}
                                </h4>
                                <p class="text-gray-400 text-xs mt-1">{{ $recommend->user->name ?? 'Unknown' }}</p>
                                <p class="text-gray-500 text-xs">{{ number_format($recommend->views_count ?? 0) }} views</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-400 text-sm">No recommended videos</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================ --}}
<script>
// Share function
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
            // Fallback
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

// Save button
@auth
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveBtn-{{ $post->id }}');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const postId = {{ $post->id }};
            fetch('/api/posts/' + postId + '/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.saved) {
                    saveBtn.innerHTML = `
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                        </svg>
                        Saved
                    `;
                    saveBtn.classList.add('text-red-500');
                } else {
                    saveBtn.innerHTML = `
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                        </svg>
                        Save
                    `;
                    saveBtn.classList.remove('text-red-500');
                }
            })
            .catch(() => {});
        });
    }
});
@endauth
</script>
@endsection