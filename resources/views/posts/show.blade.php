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
                
                {{-- ============================================ --}}
                {{-- VIDEO PLAYER - Bunny CDN --}}
                {{-- ============================================ --}}
                @if($post->video_cdn_url)
                    <div class="block w-full overflow-hidden clear-both">
                        <x-common.video-player 
                            :src="$post->video_cdn_url"
                            :poster="$post->video_thumbnail_url"
                            :autoplay="false"
                            :title="$post->title ?? $post->content ?? 'Untitled'"
                            :views="number_format($post->views_count ?? 0)"
                            :time="$post->created_at->diffForHumans()"
                            :allow_download="false"
                        />
                    </div>
                    
                    {{-- 18+ WARNING OVERLAY --}}
                    @if($post->is_mature)
                        <div id="matureWarning" 
                             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.92); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; padding: 20px; backdrop-filter: blur(10px);">
                            
                            <div style="font-size: 80px; margin-bottom: 15px;">🔞</div>
                            <h2 style="color: #e74c3c; font-size: 28px; font-weight: 700; margin: 0;">18+ Content</h2>
                            <p style="color: #b0b3b8; font-size: 16px; text-align: center; max-width: 400px; margin: 15px 0 25px; line-height: 1.6;">
                                This video contains mature content and is intended for adults only.
                                Please confirm that you are 18 years or older.
                            </p>
                            <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
                                <button onclick="document.getElementById('matureWarning').style.display='none'" 
                                        style="background: #2d88ff; color: white; border: none; padding: 12px 40px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                    I'm 18+ Continue
                                </button>
                                <button onclick="window.history.back()" 
                                        style="background: #3e4042; color: #b0b3b8; border: none; padding: 12px 40px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                    Go Back
                                </button>
                            </div>
                        </div>
                    @endif
                    
                @elseif($post->link)
                    {{-- ============================================ --}}
                    {{-- LINK PLAYER (YouTube, Vimeo, TikTok, Instagram) --}}
                    {{-- ============================================ --}}
                    @php
                        $embedUrl = null;
                        $linkDomain = parse_url($post->link, PHP_URL_HOST);
                        $linkDomain = str_replace('www.', '', $linkDomain);
                        
                        // YouTube
                        if (str_contains($linkDomain, 'youtube.com') || str_contains($linkDomain, 'youtu.be')) {
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $post->link, $match);
                            if (isset($match[1])) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $match[1] . '?autoplay=0&rel=0&modestbranding=1&showinfo=0&controls=1&color=white&iv_load_policy=3';
                            }
                        }
                        // Vimeo
                        elseif (str_contains($linkDomain, 'vimeo.com')) {
                            preg_match('/(?:vimeo\.com\/(?:video\/|embed\/|)|player\.vimeo\.com\/video\/)(\d+)/', $post->link, $match);
                            if (isset($match[1])) {
                                $embedUrl = 'https://player.vimeo.com/video/' . $match[1] . '?autoplay=0&byline=0&portrait=0&title=0&badge=0';
                            }
                        }
                        // TikTok
                        elseif (str_contains($linkDomain, 'tiktok.com')) {
                            preg_match('/tiktok\.com\/@[\w.-]+\/video\/(\d+)/', $post->link, $match);
                            if (isset($match[1])) {
                                $embedUrl = 'https://www.tiktok.com/embed/v2/' . $match[1];
                            }
                        }
                        // Instagram
                        elseif (str_contains($linkDomain, 'instagram.com')) {
                            preg_match('/instagram\.com\/(?:p|reel)\/([^\/\?]+)/', $post->link, $match);
                            if (isset($match[1])) {
                                $embedUrl = 'https://www.instagram.com/p/' . $match[1] . '/embed';
                            }
                        }
                    @endphp
                    
                    @if($embedUrl)
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; background: #000; border-radius: 12px;">
                            <iframe 
                                src="{{ $embedUrl }}"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                                title="Video Player">
                            </iframe>
                        </div>
                    @else
                        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-2xl border border-gray-800 p-8 text-center">
                            <p class="text-gray-400">Video not available or unsupported link.</p>
                        </div>
                    @endif
                    
                @elseif($post->image)
                    {{-- Image Display --}}
                    <div class="bg-black rounded-xl overflow-hidden shadow-2xl">
                        <img src="{{ Storage::url($post->image) }}" 
                             alt="{{ $post->title ?? 'Post image' }}" 
                             class="w-full max-h-[600px] object-contain">
                    </div>
                @endif
                
                {{-- ============================================ --}}
                {{-- VIDEO INFO --}}
                {{-- ============================================ --}}
                <div class="mt-4">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 8px;">
                        <h1 class="text-white text-xl md:text-2xl font-bold flex-1 min-w-0">
                            {{ $post->title ?? $post->content ?? 'Untitled' }}
                        </h1>
                        
                        @if($post->is_mature)
                            <span style="background: #e74c3c; color: white; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px; flex-shrink: 0;">
                                🔞 18+
                            </span>
                        @endif
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        {{-- Views --}}
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <span>👁️ {{ number_format($post->views_count ?? 0) }} views</span>
                        </div>
                        
                        {{-- ⏱️ Video Duration --}}
                        @if($post->video_duration)
                            <div class="flex items-center gap-2 text-gray-400 text-sm">
                                <span>⏱️</span>
                                <span>
                                    @php
                                        $hours = floor($post->video_duration / 3600);
                                        $minutes = floor(($post->video_duration % 3600) / 60);
                                        $seconds = $post->video_duration % 60;
                                    @endphp
                                    @if($hours > 0)
                                        {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                    @else
                                        {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        
                        {{-- 💾 File Size (MB) --}}
                        @if($post->video_size)
                            <div class="flex items-center gap-2 text-gray-400 text-sm">
                                <span>💾</span>
                                <span>{{ number_format($post->video_size / 1048576, 2) }} MB</span>
                            </div>
                        @endif
                        
                        {{-- Date --}}
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <span>•</span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        
                        {{-- Category --}}
                        @if($post->category)
                            <span style="background: rgba(45, 136, 255, 0.2); color: #2d88ff; font-size: 12px; font-weight: 600; padding: 4px 14px; border-radius: 20px; border: 1px solid rgba(45, 136, 255, 0.3);">
                                {{ $post->category_label }}
                            </span>
                        @endif
                    </div>
                </div>
                
                {{-- ============================================ --}}
                {{-- ACTION BUTTONS --}}
                {{-- ============================================ --}}
                <div class="flex items-center gap-4 mt-4 flex-wrap">
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
                    
                    <button onclick="document.getElementById('comment-section').scrollIntoView({behavior: 'smooth'})" 
                            class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                        <span>{{ number_format($post->comments_count ?? 0) }}</span>
                    </button>
                    
                    <button onclick="shareVideo()" class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                        <svg class="w-5 h-5" fill"currentColor" viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                        Share
                    </button>

                    {{-- ============================================ --}}
                    {{-- ⬇️ DOWNLOAD BUTTON --}}
                    {{-- ============================================ --}}
                    @if($post->video_cdn_url)
                        <a href="{{ route('video.download.page', $post->id) }}" 
                           class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-full transition text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                            </svg>
                            Download
                        </a>
                    @endif

                    @if($post->description)
                        <a href="{{ route('posts.description', $post->id) }}" 
                           class="flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                            </svg>
                            Info
                        </a>
                    @endif
                    
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
                {{-- DESCRIPTION (Content) --}}
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
            </div>
            
            {{-- ============================================ --}}
            {{-- SIDEBAR - RIGHT SIDE (1/3) --}}
            {{-- ============================================ --}}
            <div class="lg:col-span-1">
                <h3 class="text-white text-lg font-bold mb-4">Recommended</h3>
                
                @php
                    $recommended = App\Models\Post::where('id', '!=', $post->id)
                        ->where('privacy', 'public')
                        ->where(function($q) {
                            $q->whereNotNull('video_cdn_url')
                              ->orWhereNotNull('link')
                              ->orWhereNotNull('image');
                        })
                        ->latest()
                        ->limit(10)
                        ->get();
                @endphp
                
                <div class="space-y-3">
                    @forelse($recommended as $recommend)
                        <a href="{{ route('posts.show', $recommend->id) }}" class="flex gap-3 group">
                            <div class="w-40 flex-shrink-0">
                                <div class="relative aspect-video bg-gray-800 rounded-lg overflow-hidden">
                                    @if($recommend->video_thumbnail_url)
                                        <img src="{{ $recommend->video_thumbnail_url }}" 
                                             alt="{{ $recommend->title ?? 'Video' }}" 
                                             class="w-full h-full object-cover">
                                    @elseif($recommend->video_thumbnail)
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
                        <p class="text-gray-400 text-sm">No recommendations</p>
                    @endforelse
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

@auth
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveBtn-{{ $post->id }}');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const postId = {{ $post->id }};
            fetch('/api/posts/' + postId + '/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
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