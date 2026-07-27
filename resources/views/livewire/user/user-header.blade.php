<header class="mv-header">
    <div class="mv-mobile-header">
        {{-- Top Bar --}}
        <div class="mv-top-bar">
            <div class="mv-top-left">
                {{-- MVideo Logo --}}
                <div class="logo-container">
                    <a href="/" class="logo-link" wire:navigate>
                        <div class="logo-icon-container">
                            <svg class="logo-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="28" height="28">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        
                        <div class="logo-text-container">
                            <span class="logo-main">M-VIDEO</span>
                            <span class="logo-sub">PREMIUM CONTENT</span>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="mv-top-right">
                {{-- Search Icon --}}
                <div class="mv-icon-circle mv-search-icon-btn" 
                     role="button" 
                     aria-label="Search" 
                     tabindex="0"
                     wire:click="toggleSearch">
                    <svg viewBox="0 0 28 28" width="22" height="22" fill="#65676B">
                        <path d="M12.5 3.5C7.81 3.5 4 7.31 4 12s3.81 8.5 8.5 8.5c1.89 0 3.63-.62 5.05-1.67l4.71 4.71c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-4.71-4.71c1.05-1.42 1.67-3.16 1.67-5.05 0-4.69-3.81-8.5-8.5-8.5zm0 2.5c3.32 0 6 2.68 6 6s-2.68 6-6 6-6-2.68-6-6 2.68-6 6-6z"/>
                    </svg>
                </div>
                
                {{-- Create/Add Icon --}}
                <a href="{{ route('post.create.post') }}" class="mv-icon-circle mv-add-icon-btn" 
                   role="button" 
                   aria-label="Create post" 
                   tabindex="0"
                   wire:navigate>
                    <svg viewBox="0 0 28 28" width="22" height="22" fill="#050505">
                        <path d="M14 3.5c.69 0 1.25.56 1.25 1.25v8h8c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25h-8v8c0 .69-.56 1.25-1.25 1.25s-1.25-.56-1.25-1.25v-8h-8c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25h8v-8c0-.69.56-1.25 1.25-1.25z"/>
                    </svg>
                </a>

                {{-- Messenger Icon --}}
                <a href="#" class="mv-icon-circle" aria-label="Messenger" wire:navigate>
                    <svg viewBox="0 0 28 28" width="22" height="22" fill="#050505">
                        <path d="M14 2.042c6.76 0 12 4.952 12 11.64S20.76 25.322 14 25.322a13.091 13.091 0 0 1-3.474-.461.959.959 0 0 0-.641.047L7.5 25.959a.961.961 0 0 1-1.348-.849l-.065-2.134a.957.957 0 0 0-.322-.684A11.389 11.389 0 0 1 2 13.682C2 6.994 7.24 2.042 14 2.042ZM6.794 17.086a.57.57 0 0 0 .827.758l3.786-2.874a.722.722 0 0 1 .868 0l2.8 2.1a1.8 1.8 0 0 0 2.6-.481l3.525-5.592a.57.57 0 0 0-.827-.758l-3.786 2.874a.722.722 0 0 1-.868 0l-2.8-2.1a1.8 1.8 0 0 0-2.6.481Z"/>
                    </svg>
                </a>

                {{-- User Profile Avatar --}}
                <a href="{{ route('profile.show', auth()->user()) }}" 
                   class="mv-avatar-container" 
                   wire:navigate>
                    <img src="{{ $avatar }}" 
                         alt="{{ auth()->user()->name ?? 'User' }}" 
                         class="mv-user-avatar">
                </a>
            </div>
        </div>

        {{-- Search Bar Expanded --}}
        @if($isSearchOpen)
            <div class="mv-search-bar-container" style="display: block;">
                <div class="mv-search-bar">
                    <button class="mv-search-back" 
                            aria-label="Go back"
                            wire:click="closeSearch">
                        <svg viewBox="0 0 28 28" width="22" height="22" fill="#1877F2">
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                        </svg>
                    </button>
                    <div class="mv-search-input-wrapper">
                        <div class="mv-search-icon">
                            <svg viewBox="0 0 28 28" width="18" height="18" fill="#65676B">
                                <path d="M12.5 3.5C7.81 3.5 4 7.31 4 12s3.81 8.5 8.5 8.5c1.89 0 3.63-.62 5.05-1.67l4.71 4.71c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-4.71-4.71c1.05-1.42 1.67-3.16 1.67-5.05 0-4.69-3.81-8.5-8.5-8.5zm0 2.5c3.32 0 6 2.68 6 6s-2.68 6-6 6-6-2.68-6-6 2.68-6 6-6z"/>
                            </svg>
                        </div>
                        <input 
                            type="search" 
                            class="mv-search-input" 
                            placeholder="Search MVideo"
                            aria-label="Search MVideo"
                            wire:model.live.debounce.300ms="searchQuery"
                            wire:keydown.enter="search"
                            autofocus
                        >
                    </div>
                </div>
            </div>
        @endif
    </div>
</header>

{{-- Styles --}}
<style>
    .mv-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: var(--bg-primary, #0f172a);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .mv-mobile-header {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .mv-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 56px;
    }

    .mv-top-left {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }

    .mv-top-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* Logo */
    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: inherit;
        flex-shrink: 0;
    }

    .logo-icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #1877F2, #42B72A);
        border-radius: 8px;
        flex-shrink: 0;
    }

    .logo-svg {
        width: 20px;
        height: 20px;
        color: white;
    }

    .logo-text-container {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
        flex-shrink: 0;
    }

    .logo-main {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary, #ffffff);
        letter-spacing: -0.3px;
    }

    .logo-sub {
        font-size: 8px;
        font-weight: 600;
        color: var(--text-muted, #6b7280);
        letter-spacing: 0.5px;
    }

    /* Icons */
    .mv-icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--bg-secondary, #1e293b);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        color: var(--text-primary, #ffffff);
        flex-shrink: 0;
    }

    .mv-icon-circle:hover {
        background: var(--bg-hover, #334155);
        transform: scale(1.05);
    }

    .mv-icon-circle svg {
        width: 22px;
        height: 22px;
        fill: var(--text-primary, #ffffff);
    }

    .mv-icon-circle.mv-search-icon-btn svg {
        fill: var(--text-muted, #6b7280);
    }

    .mv-icon-circle.mv-add-icon-btn {
        background: #1877F2;
    }

    .mv-icon-circle.mv-add-icon-btn svg {
        fill: white;
    }

    .mv-icon-circle.mv-add-icon-btn:hover {
        background: #1b7ff5;
    }

    /* User Avatar */
    .mv-avatar-container {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        display: block;
    }

    .mv-avatar-container:hover {
        border-color: #1877F2;
        transform: scale(1.05);
    }

    .mv-user-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Search Bar */
    .mv-search-bar-container {
        padding: 8px 0 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .mv-search-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--bg-secondary, #1e293b);
        border-radius: 24px;
        padding: 4px 16px;
    }

    .mv-search-back {
        background: none;
        border: none;
        padding: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .mv-search-back svg {
        width: 22px;
        height: 22px;
        fill: #1877F2;
    }

    .mv-search-input-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
    }

    .mv-search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .mv-search-icon svg {
        width: 18px;
        height: 18px;
        fill: var(--text-muted, #6b7280);
    }

    .mv-search-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: var(--text-primary, #ffffff);
        font-size: 15px;
        font-weight: 400;
        min-width: 0;
        width: 100%;
        padding: 4px 0;
    }

    .mv-search-input::placeholder {
        color: var(--text-muted, #6b7280);
    }

    /* Responsive */
    @media (max-width: 480px) {
        .logo-main {
            font-size: 14px;
        }
        
        .logo-sub {
            font-size: 7px;
        }
        
        .mv-icon-circle {
            width: 36px;
            height: 36px;
        }
        
        .mv-icon-circle svg {
            width: 18px;
            height: 18px;
        }
        
        .mv-avatar-container {
            width: 36px;
            height: 36px;
        }
    }
</style>