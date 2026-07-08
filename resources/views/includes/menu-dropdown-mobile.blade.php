{{-- includes/menu-dropdown-mobile.blade.php --}}
@props(['video'])

<div class="mobile-menu-header">
    <h6>Video Options</h6>
    <button class="close-mobile-menu">×</button>
</div>

<div class="mobile-menu-items">
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