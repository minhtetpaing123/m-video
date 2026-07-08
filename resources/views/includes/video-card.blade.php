{{-- includes/video-card.blade.php --}}
@props(['video', 'index'])

<div class="video-card" style="--delay: {{ $index * 0.1 }}s" data-video-id="{{ $video['id'] }}">
    <div class="card-thumbnail">
        <div class="thumbnail-image" style="background: {{ $video['thumbnail_bg'] }};"></div>
        @if(!$video['live'])
        <div class="duration-badge">{{ $video['duration'] }}</div>
        @endif
        @if($video['live'])
        <div class="live-badge" style="position: absolute; top: 8px; left: 8px;">
            <svg class="live-icon" width="8" height="8" viewBox="0 0 24 24" fill="red">
                <circle cx="12" cy="12" r="6"/>
            </svg>
            LIVE
        </div>
        @endif
        <div class="hover-overlay">
            <button class="play-button" aria-label="Play video">
                <svg class="play-icon" width="48" height="48" viewBox="0 0 24 24" fill="white">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="card-content">
        <div class="creator-info">
            <div class="creator-avatar" style="background: {{ $video['avatar_bg'] }};"></div>
            <div class="creator-details">
                <h4 class="creator-name">{{ $video['creator'] }}</h4>
                <span class="upload-time">{{ $video['time'] }}</span>
            </div>
            
            <div class="menu-button-wrapper">
                <button class="menu-button" aria-label="Video menu" data-video-id="{{ $video['id'] }}">
                    <svg class="menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                </button>
                
                <div class="menu-dropdown" id="dropdown-{{ $video['id'] }}">
                    @include('includes.menu-dropdown-desktop', ['video' => $video])
                    @include('includes.menu-dropdown-mobile', ['video' => $video])
                </div>
            </div>
        </div>
        
        <h3 class="video-title">{{ $video['title'] }}</h3>
        
        <div class="video-stats">
            @if($video['live'])
            <span class="stat-item live-badge">
                <svg class="live-icon" width="8" height="8" viewBox="0 0 24 24" fill="red">
                    <circle cx="12" cy="12" r="6"/>
                </svg>
                LIVE
            </span>
            <span class="stat-item" data-stat="views" data-video-id="{{ $video['id'] }}">
                <svg class="stat-icon eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
                <span class="stat-count">{{ $video['views'] }}</span>
            </span>
            @else
            <span class="stat-item" data-stat="views" data-video-id="{{ $video['id'] }}">
                <svg class="stat-icon eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
                <span class="stat-count">{{ $video['views'] }}</span>
            </span>
            
            <span class="stat-item comment-stat-item {{ !auth()->check() ? 'read-only' : '' }}" 
                  data-stat="comments" 
                  data-video-id="{{ $video['id'] }}">
                <button class="comment-icon-btn" data-video-id="{{ $video['id'] }}" aria-label="Show comments">
                    <svg class="stat-icon comment-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                    </svg>
                    <span class="stat-count">{{ $video['comments'] }}</span>
                </button>
            </span>
            
            <span class="stat-item" data-stat="likes" data-video-id="{{ $video['id'] }}">
                <svg class="stat-icon like-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                </svg>
                <span class="stat-count">{{ $video['likes'] }}</span>
            </span>
            @endif
        </div>
    </div>
</div>