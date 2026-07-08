{{-- includes/menu-dropdown-desktop.blade.php --}}
@props(['video'])

<div class="menu-desktop">
    <div class="menu-header">
        <div class="video-info">
            <div class="video-thumb-preview">
                <div class="thumbnail-image" style="background: {{ $video['thumbnail_bg'] }}; width: 100%; height: 100%;"></div>
            </div>
            <div class="video-preview-info">
                <h4 class="video-preview-title">{{ Str::limit($video['title'], 50) }}</h4>
                <span class="video-preview-creator">{{ $video['creator'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="menu-items">
        <button class="menu-item" data-action="share" data-video-id="{{ $video['id'] }}">
            <span class="menu-item-icon">↗️</span>
            <span class="menu-item-text">Share</span>
        </button>
        
        <button class="menu-item" data-action="save" data-video-id="{{ $video['id'] }}">
            <span class="menu-item-icon">🔖</span>
            <span class="menu-item-text">Save</span>
        </button>
        
        <button class="menu-item" data-action="watch-later" data-video-id="{{ $video['id'] }}">
            <span class="menu-item-icon">⏱️</span>
            <span class="menu-item-text">Watch Later</span>
        </button>
        
        <div class="menu-divider"></div>
        
        <button class="menu-item" data-action="download" data-video-id="{{ $video['id'] }}">
            <span class="menu-item-icon">⬇️</span>
            <span class="menu-item-text">Download</span>
        </button>
        
        <button class="menu-item" data-action="not-interested" data-video-id="{{ $video['id'] }}">
            <span class="menu-item-icon">🙈</span>
            <span class="menu-item-text">Not interested</span>
        </button>
        
        <button class="menu-item" data-action="report" data-video-id="{{ $video['id'] }}">
            <span class="menu-item-icon">⚠️</span>
            <span class="menu-item-text">Report</span>
        </button>
    </div>
</div>