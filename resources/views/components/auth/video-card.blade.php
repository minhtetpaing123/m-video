{{-- resources/views/components/auth/video-card.blade.php --}}
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
        'thumbnail_bg' => $post->thumbnail_bg ?? 'linear-gradient(45deg, #667eea, #764ba2)',
        'avatar_bg' => $post->user->avatar_bg ?? 'linear-gradient(45deg, #4ecdc4, #44a08d)',
    ];
    
    // Get video URL for streaming
    $videoPath = $post->video;
    if (str_starts_with($videoPath, 'public/')) {
        $videoPath = substr($videoPath, 7);
    }
    $videoPath = ltrim($videoPath, '/');
    $videoUrl = route('video.stream', ['path' => $videoPath]);
    
    // Get thumbnail URL
    $thumbnailUrl = $post->video_thumbnail ? Storage::url($post->video_thumbnail) : null;
    
    // Get avatar URL
    $avatarUrl = $post->user->avatar ? Storage::url($post->user->avatar) : null;
    
    // Format numbers for display
    $viewsDisplay = $video['views'];
    if ($post->views_count >= 1000000) {
        $viewsDisplay = number_format($post->views_count / 1000000, 1) . 'M';
    } elseif ($post->views_count >= 1000) {
        $viewsDisplay = number_format($post->views_count / 1000, 1) . 'K';
    }
    
    $likesDisplay = $video['likes'];
    if ($post->likes_count >= 1000000) {
        $likesDisplay = number_format($post->likes_count / 1000000, 1) . 'M';
    } elseif ($post->likes_count >= 1000) {
        $likesDisplay = number_format($post->likes_count / 1000, 1) . 'K';
    }
    
    $commentsDisplay = $video['comments'];
    if ($post->comments_count >= 1000000) {
        $commentsDisplay = number_format($post->comments_count / 1000000, 1) . 'M';
    } elseif ($post->comments_count >= 1000) {
        $commentsDisplay = number_format($post->comments_count / 1000, 1) . 'K';
    }
@endphp

<div class="video-card" style="--delay: {{ $index * 0.1 }}s" data-video-id="{{ $post->id }}">
    <div class="card-thumbnail">
        {{-- Thumbnail --}}
        @if($thumbnailUrl)
            <img src="{{ $thumbnailUrl }}" alt="{{ $video['title'] }}" class="thumbnail-image">
        @else
            <div class="thumbnail-image" style="background: {{ $video['thumbnail_bg'] }};"></div>
        @endif
        
        {{-- Duration Badge --}}
        @if(!$video['live'] && $video['duration'])
            <div class="duration-badge">{{ $video['duration'] }}</div>
        @endif
        
        {{-- Live Badge --}}
        @if($video['live'])
            <div class="live-badge" style="position: absolute; top: 8px; left: 8px;">
                <svg class="live-icon" width="8" height="8" viewBox="0 0 24 24" fill="red">
                    <circle cx="12" cy="12" r="6"/>
                </svg>
                LIVE
            </div>
        @endif
        
        {{-- Hover Overlay with Play Button --}}
        <div class="hover-overlay">
            <a href="{{ route('posts.show', $post->id) }}" class="play-button" aria-label="Play video">
                <svg class="play-icon" width="48" height="48" viewBox="0 0 24 24" fill="white">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </a>
        </div>
    </div>
    
    <div class="card-content">
        <div class="creator-info">
            {{-- Creator Avatar --}}
            <div class="creator-avatar">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $video['creator'] }}" class="avatar-img">
                @else
                    <div class="avatar-placeholder" style="background: {{ $video['avatar_bg'] }};">
                        {{ substr($video['creator'], 0, 1) }}
                    </div>
                @endif
            </div>
            
            <div class="creator-details">
                <h4 class="creator-name">{{ $video['creator'] }}</h4>
                <span class="upload-time">{{ $video['time'] }}</span>
            </div>
            
            {{-- Auth User Menu Button (Interactive) --}}
            <div class="menu-button-wrapper">
                <button class="menu-button" aria-label="Video menu" data-video-id="{{ $post->id }}">
                    <svg class="menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                </button>
                
                <div class="menu-dropdown" id="dropdown-{{ $post->id }}">
                    @include('includes.menu-dropdown-desktop', ['video' => $video])
                    @include('includes.menu-dropdown-mobile', ['video' => $video])
                </div>
            </div>
        </div>
        
        {{-- Video Title --}}
        <h3 class="video-title">
            <a href="{{ route('posts.show', $post->id) }}" class="text-white hover:text-blue-400">
                {{ $video['title'] }}
            </a>
        </h3>
        
        {{-- Video Stats (Interactive for Auth User) --}}
        <div class="video-stats">
            @if($video['live'])
                <span class="stat-item live-badge">
                    <svg class="live-icon" width="8" height="8" viewBox="0 0 24 24" fill="red">
                        <circle cx="12" cy="12" r="6"/>
                    </svg>
                    LIVE
                </span>
                <span class="stat-item" data-stat="views" data-video-id="{{ $post->id }}">
                    <svg class="stat-icon eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                    <span class="stat-count">{{ $viewsDisplay }}</span>
                </span>
            @else
                <span class="stat-item" data-stat="views" data-video-id="{{ $post->id }}">
                    <svg class="stat-icon eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                    <span class="stat-count">{{ $viewsDisplay }}</span>
                </span>
                
                {{-- Auth User: Comments (Interactive) --}}
                <span class="stat-item comment-stat-item" data-stat="comments" data-video-id="{{ $post->id }}">
                    <button class="comment-icon-btn" data-video-id="{{ $post->id }}" aria-label="Show comments">
                        <svg class="stat-icon comment-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                        <span class="stat-count">{{ $commentsDisplay }}</span>
                    </button>
                </span>
                
                {{-- Auth User: Likes (Interactive) --}}
                <span class="stat-item" data-stat="likes" data-video-id="{{ $post->id }}">
                    <button class="like-btn" data-video-id="{{ $post->id }}" aria-label="Like video">
                        <svg class="stat-icon like-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                        </svg>
                        <span class="stat-count">{{ $likesDisplay }}</span>
                    </button>
                </span>
            @endif
        </div>
    </div>
</div>