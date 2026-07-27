@php
    $userAvatar = Auth::check() && Auth::user()->avatar 
        ? Auth::user()->avatar 
        : 'https://graph.facebook.com/' . Auth::id() . '/picture?type=square&width=40&height=40';
@endphp

<header class="mv-header">
    <div x-data="{ isSearchOpen: false }" class="mv-mobile-header">
        
        {{-- Top Bar (Normal View) --}}
        <div x-show="!isSearchOpen" class="mv-top-bar">
            <div class="mv-top-left">
                {{-- MVideo Logo Component --}}
                <div class="logo-container">
                    <a href="/" class="logo-link">
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
                <div @click="isSearchOpen = true" class="mv-icon-circle mv-search-icon-btn" role="button" aria-label="Search" tabindex="0">
                    <svg viewBox="0 0 28 28" width="22" height="22" fill="#65676B">
                        <path d="M12.5 3.5C7.81 3.5 4 7.31 4 12s3.81 8.5 8.5 8.5c1.89 0 3.63-.62 5.05-1.67l4.71 4.71c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-4.71-4.71c1.05-1.42 1.67-3.16 1.67-5.05 0-4.69-3.81-8.5-8.5-8.5zm0 2.5c3.32 0 6 2.68 6 6s-2.68 6-6 6-6-2.68-6-6 2.68-6 6-6z"/>
                    </svg>
                </div>
                
                {{-- Create/Add Icon --}}
                <div @click="$dispatch('open-create-post-modal')" class="mv-icon-circle mv-add-icon-btn" role="button" aria-label="Create post" tabindex="0">
                    <svg viewBox="0 0 28 28" width="22" height="22" fill="#050505">
                        <path d="M14 3.5c.69 0 1.25.56 1.25 1.25v8h8c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25h-8v8c0 .69-.56 1.25-1.25 1.25s-1.25-.56-1.25-1.25v-8h-8c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25h8v-8c0-.69.56-1.25 1.25-1.25z"/>
                    </svg>
                </div>

                {{-- Messenger Icon --}}
                <a href="#" class="mv-icon-circle" aria-label="Messenger">
                    <svg viewBox="0 0 28 28" width="22" height="22" fill="#050505">
                        <path d="M14 2.042c6.76 0 12 4.952 12 11.64S20.76 25.322 14 25.322a13.091 13.091 0 0 1-3.474-.461.959.959 0 0 0-.641.047L7.5 25.959a.961.961 0 0 1-1.348-.849l-.065-2.134a.957.957 0 0 0-.322-.684A11.389 11.389 0 0 1 2 13.682C2 6.994 7.24 2.042 14 2.042ZM6.794 17.086a.57.57 0 0 0 .827.758l3.786-2.874a.722.722 0 0 1 .868 0l2.8 2.1a1.8 1.8 0 0 0 2.6-.481l3.525-5.592a.57.57 0 0 0-.827-.758l-3.786 2.874a.722.722 0 0 1-.868 0l-2.8-2.1a1.8 1.8 0 0 0-2.6.481Z"/>
                    </svg>
                </a>

                {{-- 🔥 Settings Icon Button (Purified Alpine Event Dispatcher) 🔥 --}}
                <div 
                    @click="$dispatch('open-settings-modal')" 
                    class="mv-icon-circle cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" 
                    role="button" 
                    aria-label="Settings" 
                    tabindex="0"
                >
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Expanded Search Bar --}}
        <div x-show="isSearchOpen" @click.outside="isSearchOpen = false" class="mv-search-bar-container" style="display: block;" x-cloak>
            <div class="mv-search-bar">
                <button @click="isSearchOpen = false" class="mv-search-back" aria-label="Go back">
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
                        x-ref="searchInput"
                        x-init="$watch('isSearchOpen', value => { if(value) setTimeout(() => $refs.searchInput.focus(), 100) })"
                    >
                </div>
            </div>
        </div>

    </div>
</header>
