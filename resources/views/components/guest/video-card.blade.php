{{-- resources/views/components/guest/video-card.blade.php --}}
@props(['post', 'index' => 0])

@php
    // Get video data from post
    $video = [
        'id' => $post->id,
        'title' => $post->title ?? $post->content ?? 'Untitled',
        'creator' => $post->user->name ?? 'Unknown',
        'time' => $post->created_at->diffForHumans(),
        'views' => number_format($post->views_count ?? 0),
        'comments' => number_format($post->comments_count ?? 0),
        'likes' => number_format($post->likes_count ?? 0),
        'duration' => $post->duration ?? '0:00',
        'live' => $post->is_live ?? false,
    ];
    
    // Get video URL for streaming
    $videoPath = $post->video;
    if (str_starts_with($videoPath, 'public/')) {
        $videoPath = substr($videoPath, 7);
    }
    $videoPath = ltrim($videoPath, '/');
    $videoUrl = route('video.stream', ['path' => $videoPath]);
    
    // Get avatar URL
    $avatarUrl = $post->user->avatar ? Storage::url($post->user->avatar) : null;
    
    // Get thumbnail - fallback to gradient
    $thumbnailUrl = $post->video_thumbnail ? Storage::url($post->video_thumbnail) : null;
    $thumbnailBg = 'linear-gradient(45deg, #667eea, #764ba2)';
    
    // Format numbers
    $viewsDisplay = $post->views_count ?? 0;
    if ($viewsDisplay >= 1000000) {
        $viewsDisplay = number_format($viewsDisplay / 1000000, 1) . 'M';
    } elseif ($viewsDisplay >= 1000) {
        $viewsDisplay = number_format($viewsDisplay / 1000, 1) . 'K';
    }
    
    $likesDisplay = $post->likes_count ?? 0;
    if ($likesDisplay >= 1000000) {
        $likesDisplay = number_format($likesDisplay / 1000000, 1) . 'M';
    } elseif ($likesDisplay >= 1000) {
        $likesDisplay = number_format($likesDisplay / 1000, 1) . 'K';
    }
    
    $commentsDisplay = $post->comments_count ?? 0;
    if ($commentsDisplay >= 1000000) {
        $commentsDisplay = number_format($commentsDisplay / 1000000, 1) . 'M';
    } elseif ($commentsDisplay >= 1000) {
        $commentsDisplay = number_format($commentsDisplay / 1000, 1) . 'K';
    }
@endphp

<div class="video-card bg-gray-800 rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" 
     data-video-id="{{ $post->id }}"
     onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
    
    {{-- Thumbnail --}}
    <div class="relative aspect-video bg-gray-900">
        @if($thumbnailUrl)
            <img src="{{ $thumbnailUrl }}" alt="{{ $video['title'] }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center" style="background: {{ $thumbnailBg }};">
                <svg class="w-16 h-16 text-white/50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </div>
        @endif
        
        {{-- Duration Badge --}}
        @if(!$video['live'] && $video['duration'] && $video['duration'] != '0:00')
            <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                {{ $video['duration'] }}
            </div>
        @endif
        
        {{-- Live Badge --}}
        @if($video['live'])
            <div class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded flex items-center gap-1">
                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                LIVE
            </div>
        @endif
        
        {{-- Play Button Overlay --}}
        <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition">
                <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </div>
        </div>
    </div>
    
    {{-- Content --}}
    <div class="p-3">
        {{-- Creator Info --}}
        <div class="flex items-start gap-2">
            {{-- Avatar --}}
            <div class="flex-shrink-0 mt-1">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $video['creator'] }}" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold bg-gradient-to-br from-blue-500 to-purple-600">
                        {{ substr($video['creator'], 0, 1) }}
                    </div>
                @endif
            </div>
            
            {{-- Creator Name & Time --}}
            <div class="flex-1 min-w-0">
                <h4 class="text-white text-sm font-semibold truncate">{{ $video['creator'] }}</h4>
                <span class="text-gray-400 text-xs">{{ $video['time'] }}</span>
            </div>
        </div>
        
        {{-- Title --}}
        <h3 class="text-white text-sm font-medium mt-1 line-clamp-2">
            {{ $video['title'] }}
        </h3>
        
        {{-- Stats --}}
        <div class="flex items-center gap-3 text-gray-400 text-xs mt-2">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
                {{ $viewsDisplay }}
            </span>
            
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                </svg>
                {{ $commentsDisplay }}
            </span>
            
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                </svg>
                {{ $likesDisplay }}
            </span>
        </div>
    </div>
</div>