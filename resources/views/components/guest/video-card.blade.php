{{-- resources/views/components/guest/video-card.blade.php --}}
@props(['post', 'index' => 0])

@php
    $isVideo = $post->video_cdn_url ? true : false;
    $isLink = $post->link ? true : false;
    $isImage = $post->image ? true : false;
    
    $title = $post->title ?? $post->content ?? 'Untitled';
    $creator = $post->user->name ?? 'Unknown';
    $time = $post->created_at->diffForHumans();
    
    $linkDomain = $post->link ? parse_url($post->link, PHP_URL_HOST) : null;
    if ($linkDomain) {
        $linkDomain = str_replace('www.', '', $linkDomain);
        $linkDomain = str_replace('m.', '', $linkDomain);
    }
    
    $linkTitle = $post->link_title ?? 'External Link';
    
    $thumbnailUrl = $post->video_thumbnail_url ?? null;
    if (!$thumbnailUrl && $post->video_thumbnail) {
        $thumbnailUrl = Storage::url($post->video_thumbnail);
    }
@endphp

<div class="video-card" onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
    
    {{-- Thumbnail --}}
    <div class="video-card-thumbnail">
        @if($isVideo)
            @if($thumbnailUrl)
                <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" loading="lazy">
            @else
                <div class="video-card-placeholder">
                    <span>🎬</span>
                </div>
            @endif
            
            {{-- Duration Badge --}}
            @if($post->video_duration)
                <span class="video-card-duration">
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
            @endif
            
        @elseif($isLink)
            <div class="video-card-placeholder">
                @php
                    $platformIcons = [
                        'youtube.com' => '▶️',
                        'youtu.be' => '▶️',
                        'vimeo.com' => '🎥',
                        'tiktok.com' => '🎵',
                        'instagram.com' => '📸',
                        'facebook.com' => '👥',
                        'twitter.com' => '🐦',
                        'x.com' => '🐦',
                    ];
                    $icon = '🔗';
                    foreach ($platformIcons as $domain => $platformIcon) {
                        if (str_contains($linkDomain ?? '', $domain)) {
                            $icon = $platformIcon;
                            break;
                        }
                    }
                @endphp
                <span style="font-size: 40px;">{{ $icon }}</span>
                <span style="font-size: 11px; color: #666; margin-top: 4px;">{{ $linkTitle }}</span>
            </div>
            
        @elseif($isImage)
            <img src="{{ Storage::url($post->image) }}" alt="{{ $title }}" loading="lazy">
        @endif
        
        {{-- Badges --}}
        <div class="video-card-badges">
            @if($post->category)
                <span class="badge badge-category">{{ $post->category_label }}</span>
            @endif
            
            @if($post->is_mature)
                <span class="badge badge-mature">🔞 18+</span>
            @endif
            
            @if($isLink)
                <span class="badge badge-link">🔗 Link</span>
            @endif
        </div>
    </div>
    
    {{-- Content --}}
    <div class="video-card-content">
        <div class="video-card-user">
            <div class="video-card-avatar">
                {{ substr($creator, 0, 1) }}
            </div>
            <div>
                <div class="video-card-username">{{ $creator }}</div>
                <span class="video-card-time">{{ $time }}</span>
            </div>
        </div>
        
        <h3 class="video-card-title">{{ $title }}</h3>
        
        @if($isLink)
            <p class="video-card-link-title">{{ $linkTitle }}</p>
        @endif
        
        <div class="video-card-stats">
            <span>{{ number_format($post->views_count ?? 0) }} views</span>
            <span>{{ number_format($post->likes_count ?? 0) }} likes</span>
        </div>
    </div>
</div>

<style>
.video-card {
    background: #1a1a2e;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #2a2a3e;
}

.video-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(229, 9, 20, 0.2);
}

/* ============================================ */
/* THUMBNAIL */
/* ============================================ */
.video-card-thumbnail {
    position: relative;
    aspect-ratio: 16/9;
    background: #000;
    overflow: hidden;
}

.video-card-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-card-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a1a2e, #2a2a3e);
    color: #666;
    font-size: 48px;
}

/* Duration Badge */
.video-card-duration {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0,0,0,0.85);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
}

/* Badges */
.video-card-badges {
    position: absolute;
    top: 8px;
    left: 8px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.badge {
    font-size: 10px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 12px;
    color: #fff;
}

.badge-category {
    background: rgba(45, 136, 255, 0.9);
}

.badge-mature {
    background: rgba(231, 76, 60, 0.9);
}

.badge-link {
    background: rgba(46, 204, 113, 0.9);
}

/* ============================================ */
/* CONTENT */
/* ============================================ */
.video-card-content {
    padding: 12px;
}

.video-card-user {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.video-card-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
}

.video-card-username {
    color: #fff;
    font-size: 13px;
    font-weight: 600;
}

.video-card-time {
    color: #888;
    font-size: 11px;
}

.video-card-title {
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    margin: 0 0 4px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.video-card-link-title {
    color: #888;
    font-size: 12px;
    margin: 0 0 4px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.video-card-stats {
    display: flex;
    gap: 12px;
    color: #666;
    font-size: 12px;
    margin-top: 4px;
}

/* ============================================ */
/* RESPONSIVE */
/* ============================================ */
@media (max-width: 640px) {
    .video-card-title {
        font-size: 13px;
    }
    .video-card-username {
        font-size: 12px;
    }
    .video-card-content {
        padding: 10px;
    }
    .video-card-avatar {
        width: 24px;
        height: 24px;
        font-size: 10px;
    }
}
</style>