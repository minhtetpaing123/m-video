<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold flex items-center gap-2" style="color: var(--text-primary)">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ __('Settings') }}
        </h1>
        
        <button 
            wire:click="resetSettings"
            wire:confirm="{{ __('Are you sure?') }}"
            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-full transition duration-300 flex items-center gap-1.5">
            {{ __('Reset All') }}
        </button>
    </div>

    {{-- Flash Message --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 bg-green-600/20 border border-green-500/30 text-green-400 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    {{-- Settings Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        {{-- View Mode --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border" id="view-mode-section">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                {{ __('View Mode') }}
            </h3>
            <div class="flex gap-1.5 flex-wrap">
                <button wire:click="updateViewMode('grid')" id="view-grid-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px]
                        {{ $viewMode === 'grid' ? 'border-blue-500 bg-blue-500/20 text-blue-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    📐 {{ __('Grid') }}
                </button>
                <button wire:click="updateViewMode('list')" id="view-list-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px]
                        {{ $viewMode === 'list' ? 'border-blue-500 bg-blue-500/20 text-blue-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    📋 {{ __('List') }}
                </button>
                <button wire:click="updateViewMode('netflix')" id="view-netflix-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px]
                        {{ $viewMode === 'netflix' ? 'border-red-500 bg-red-500/20 text-red-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    🎬 {{ __('Netflix') }}
                </button>
                <button wire:click="updateViewMode('youtube')" id="view-youtube-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px]
                        {{ $viewMode === 'youtube' ? 'border-red-600 bg-red-600/20 text-red-500' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    ▶️ {{ __('YouTube') }}
                </button>
            </div>
            <div class="mt-1.5 text-xs" style="color: var(--text-secondary)">
                {{ __('Current') }}: <span style="color: var(--text-primary)" id="current-view-mode">{{ ucfirst(__($viewMode)) }}</span>
            </div>
        </div>

        {{-- Font Size --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border" id="font-size-section">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                </svg>
                {{ __('Font Size') }}
            </h3>
            <div class="flex gap-2">
                <button wire:click="updateFontSize('small')" id="font-size-small-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $fontSize === 'small' ? 'border-indigo-500 bg-indigo-500/20 text-indigo-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    <span class="text-[10px]">A</span> {{ __('Small') }}
                </button>
                <button wire:click="updateFontSize('medium')" id="font-size-medium-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $fontSize === 'medium' ? 'border-indigo-500 bg-indigo-500/20 text-indigo-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    <span class="text-xs">A</span> {{ __('Medium') }}
                </button>
                <button wire:click="updateFontSize('large')" id="font-size-large-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $fontSize === 'large' ? 'border-indigo-500 bg-indigo-500/20 text-indigo-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    <span class="text-sm">A</span> {{ __('Large') }}
                </button>
            </div>
            <div class="mt-1.5 text-xs" style="color: var(--text-secondary)">
                {{ __('Current') }}: <span style="color: var(--text-primary)" id="current-font-size">{{ ucfirst(__($fontSize)) }}</span>
            </div>
        </div>

        {{-- Theme --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border" id="theme-section">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{ __('Theme') }}
            </h3>
            <div class="flex gap-2">
                <button wire:click="updateTheme('dark')" id="theme-dark-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $theme === 'dark' ? 'border-purple-500 bg-purple-500/20 text-purple-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    🌙 {{ __('Dark') }}
                </button>
                <button wire:click="updateTheme('light')" id="theme-light-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $theme === 'light' ? 'border-purple-500 bg-purple-500/20 text-purple-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    ☀️ {{ __('Light') }}
                </button>
            </div>
            <div class="mt-1.5 text-xs" style="color: var(--text-secondary)">
                {{ __('Current') }}: <span style="color: var(--text-primary)" id="current-theme">{{ ucfirst(__($theme)) }}</span>
            </div>
        </div>

        {{-- Language --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border" id="language-section">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                {{ __('Language') }}
            </h3>
            <div class="flex gap-2">
                <button wire:click="updateLanguage('en')" id="lang-en-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $language === 'en' ? 'border-green-500 bg-green-500/20 text-green-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    🇬🇧 {{ __('English') }}
                </button>
                <button wire:click="updateLanguage('mm')" id="lang-mm-btn"
                    class="flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs
                        {{ $language === 'mm' ? 'border-green-500 bg-green-500/20 text-green-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                    🇲🇲 {{ __('Myanmar') }}
                </button>
            </div>
            <div class="mt-1.5 text-xs" style="color: var(--text-secondary)">
                {{ __('Current') }}: <span style="color: var(--text-primary)" id="current-language">{{ $language === 'en' ? __('English') : __('Myanmar') }}</span>
            </div>
        </div>

        {{-- Autoplay --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('Autoplay') }}
            </h3>
            <button wire:click="toggleAutoplay" id="autoplay-btn"
                class="w-full py-1.5 px-3 rounded-lg border-2 transition duration-300 flex items-center justify-between text-xs
                    {{ $autoplay ? 'border-yellow-500 bg-yellow-500/20 text-yellow-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                <span id="autoplay-status">{{ $autoplay ? __('Enabled') : __('Disabled') }}</span>
                <span>{{ $autoplay ? '▶️' : '⏸️' }}</span>
            </button>
        </div>

        {{-- Notifications --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                {{ __('Notifications') }}
            </h3>
            <button wire:click="toggleNotifications" id="notifications-btn"
                class="w-full py-1.5 px-3 rounded-lg border-2 transition duration-300 flex items-center justify-between text-xs
                    {{ $notifications ? 'border-pink-500 bg-pink-500/20 text-pink-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                <span id="notifications-status">{{ $notifications ? __('Enabled') : __('Disabled') }}</span>
                <span>{{ $notifications ? '🔔' : '🔕' }}</span>
            </button>
        </div>

        {{-- Video Quality --}}
        <div class="settings-card backdrop-blur-sm rounded-xl p-4 border md:col-span-2">
            <h3 class="font-medium mb-3 flex items-center gap-1.5 text-sm" style="color: var(--text-primary)">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                {{ __('Video Quality') }}
            </h3>
            <div class="flex gap-2 flex-wrap">
                @foreach(['auto', '1080p', '720p', '480p', '360p'] as $q)
                    <button wire:click="updateQuality('{{ $q }}')" id="quality-{{ $q }}-btn"
                        class="px-3 py-1.5 rounded-lg border-2 transition duration-300 text-center text-xs
                            {{ $quality === $q ? 'border-red-500 bg-red-500/20 text-red-400' : 'border-gray-600 text-gray-400 hover:border-gray-500' }}">
                        {{ $q === 'auto' ? __('Auto') : $q }}
                    </button>
                @endforeach
            </div>
            <div class="mt-1.5 text-xs" style="color: var(--text-secondary)">
                {{ __('Current') }}: <span style="color: var(--text-primary)" id="current-quality">{{ $quality === 'auto' ? __('Auto') : $quality }}</span>
            </div>
        </div>

    </div>

    {{-- Back Button --}}
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}" wire:navigate
            class="inline-block px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-full transition duration-300">
            ← {{ __('Back to Home') }}
        </a>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', function () {
        
        // ============================================
        // LANGUAGE LIVE UPDATE
        // ============================================
        Livewire.on('language-updated', (data) => {
            const lang = data.language;
            
            const enBtn = document.getElementById('lang-en-btn');
            const mmBtn = document.getElementById('lang-mm-btn');
            
            if (enBtn && mmBtn) {
                if (lang === 'en') {
                    enBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-green-500 bg-green-500/20 text-green-400';
                    mmBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                } else {
                    enBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                    mmBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-green-500 bg-green-500/20 text-green-400';
                }
            }
            
            const currentLang = document.getElementById('current-language');
            if (currentLang) {
                currentLang.textContent = lang === 'en' ? 'English' : 'မြန်မာ';
            }
            
            document.documentElement.lang = lang;
        });
        
        // ============================================
        // THEME LIVE UPDATE
        // ============================================
        Livewire.on('theme-updated', (data) => {
            const theme = data.theme;
            const html = document.documentElement;
            const root = document.documentElement.style;
            
            html.classList.remove('dark', 'light');
            html.classList.add(theme);
            
            if (theme === 'dark') {
                root.setProperty('--bg-primary', '#111827');
                root.setProperty('--bg-secondary', '#1f2937');
                root.setProperty('--bg-card', 'rgba(31, 41, 55, 0.5)');
                root.setProperty('--text-primary', '#ffffff');
                root.setProperty('--text-secondary', '#9ca3af');
                root.setProperty('--border-color', '#374151');
            } else {
                root.setProperty('--bg-primary', '#f3f4f6');
                root.setProperty('--bg-secondary', '#e5e7eb');
                root.setProperty('--bg-card', 'rgba(243, 244, 246, 0.8)');
                root.setProperty('--text-primary', '#1f2937');
                root.setProperty('--text-secondary', '#6b7280');
                root.setProperty('--border-color', '#d1d5db');
            }
            
            const darkBtn = document.getElementById('theme-dark-btn');
            const lightBtn = document.getElementById('theme-light-btn');
            
            if (darkBtn && lightBtn) {
                if (theme === 'dark') {
                    darkBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-purple-500 bg-purple-500/20 text-purple-400';
                    lightBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                } else {
                    darkBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                    lightBtn.className = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-purple-500 bg-purple-500/20 text-purple-400';
                }
            }
            
            const currentTheme = document.getElementById('current-theme');
            if (currentTheme) {
                currentTheme.textContent = theme.charAt(0).toUpperCase() + theme.slice(1);
            }
        });
        
        // ============================================
        // VIEW MODE LIVE UPDATE
        // ============================================
        Livewire.on('view-mode-changed', (data) => {
            const mode = data.mode;
            const gridBtn = document.getElementById('view-grid-btn');
            const listBtn = document.getElementById('view-list-btn');
            const netflixBtn = document.getElementById('view-netflix-btn');
            const youtubeBtn = document.getElementById('view-youtube-btn');
            
            if (gridBtn && listBtn && netflixBtn && youtubeBtn) {
                const defaultClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px] border-gray-600 text-gray-400 hover:border-gray-500';
                const activeGridClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px] border-blue-500 bg-blue-500/20 text-blue-400';
                const activeListClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px] border-blue-500 bg-blue-500/20 text-blue-400';
                const activeNetflixClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px] border-red-500 bg-red-500/20 text-red-400';
                const activeYoutubeClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs min-w-[50px] border-red-600 bg-red-600/20 text-red-500';
                
                gridBtn.className = defaultClass;
                listBtn.className = defaultClass;
                netflixBtn.className = defaultClass;
                youtubeBtn.className = defaultClass;
                
                if (mode === 'grid') {
                    gridBtn.className = activeGridClass;
                } else if (mode === 'list') {
                    listBtn.className = activeListClass;
                } else if (mode === 'netflix') {
                    netflixBtn.className = activeNetflixClass;
                } else if (mode === 'youtube') {
                    youtubeBtn.className = activeYoutubeClass;
                }
            }
            
            const currentViewMode = document.getElementById('current-view-mode');
            if (currentViewMode) {
                currentViewMode.textContent = mode.charAt(0).toUpperCase() + mode.slice(1);
            }
        });
        
        // ============================================
        // FONT SIZE LIVE UPDATE
        // ============================================
        Livewire.on('font-size-changed', (data) => {
            const size = data.size;
            
            const smallBtn = document.getElementById('font-size-small-btn');
            const mediumBtn = document.getElementById('font-size-medium-btn');
            const largeBtn = document.getElementById('font-size-large-btn');
            
            if (smallBtn && mediumBtn && largeBtn) {
                const defaultClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                const activeClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-indigo-500 bg-indigo-500/20 text-indigo-400';
                
                smallBtn.className = defaultClass;
                mediumBtn.className = defaultClass;
                largeBtn.className = defaultClass;
                
                if (size === 'small') {
                    smallBtn.className = activeClass;
                } else if (size === 'medium') {
                    mediumBtn.className = activeClass;
                } else if (size === 'large') {
                    largeBtn.className = activeClass;
                }
            }
            
            const currentFontSize = document.getElementById('current-font-size');
            if (currentFontSize) {
                currentFontSize.textContent = size.charAt(0).toUpperCase() + size.slice(1);
            }
            
            const root = document.documentElement.style;
            if (size === 'small') {
                root.setProperty('--font-size-base', '12px');
            } else if (size === 'large') {
                root.setProperty('--font-size-base', '18px');
            } else {
                root.setProperty('--font-size-base', '15px');
            }
        });
        
        // ============================================
        // AUTOPLAY, NOTIFICATIONS, QUALITY LIVE UPDATE
        // ============================================
        Livewire.on('settings-updated', (data) => {
            if (data.type === 'autoplay') {
                const statusEl = document.getElementById('autoplay-status');
                if (statusEl) {
                    statusEl.textContent = data.value ? 'Enabled' : 'Disabled';
                }
                const btn = document.getElementById('autoplay-btn');
                if (btn) {
                    if (data.value) {
                        btn.className = 'w-full py-1.5 px-3 rounded-lg border-2 transition duration-300 flex items-center justify-between text-xs border-yellow-500 bg-yellow-500/20 text-yellow-400';
                    } else {
                        btn.className = 'w-full py-1.5 px-3 rounded-lg border-2 transition duration-300 flex items-center justify-between text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                    }
                }
            }
            
            if (data.type === 'notifications') {
                const statusEl = document.getElementById('notifications-status');
                if (statusEl) {
                    statusEl.textContent = data.value ? 'Enabled' : 'Disabled';
                }
                const btn = document.getElementById('notifications-btn');
                if (btn) {
                    if (data.value) {
                        btn.className = 'w-full py-1.5 px-3 rounded-lg border-2 transition duration-300 flex items-center justify-between text-xs border-pink-500 bg-pink-500/20 text-pink-400';
                    } else {
                        btn.className = 'w-full py-1.5 px-3 rounded-lg border-2 transition duration-300 flex items-center justify-between text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                    }
                }
            }
            
            if (data.type === 'quality') {
                const qualities = ['auto', '1080p', '720p', '480p', '360p'];
                qualities.forEach(q => {
                    const btn = document.getElementById('quality-' + q + '-btn');
                    if (btn) {
                        if (data.value === q) {
                            btn.className = 'px-3 py-1.5 rounded-lg border-2 transition duration-300 text-center text-xs border-red-500 bg-red-500/20 text-red-400';
                        } else {
                            btn.className = 'px-3 py-1.5 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                        }
                    }
                });
                
                const currentQuality = document.getElementById('current-quality');
                if (currentQuality) {
                    currentQuality.textContent = data.value === 'auto' ? 'Auto' : data.value;
                }
            }
            
            if (data.type === 'font_size') {
                const size = data.value;
                const smallBtn = document.getElementById('font-size-small-btn');
                const mediumBtn = document.getElementById('font-size-medium-btn');
                const largeBtn = document.getElementById('font-size-large-btn');
                
                if (smallBtn && mediumBtn && largeBtn) {
                    const defaultClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-gray-600 text-gray-400 hover:border-gray-500';
                    const activeClass = 'flex-1 py-1.5 px-2 rounded-lg border-2 transition duration-300 text-center text-xs border-indigo-500 bg-indigo-500/20 text-indigo-400';
                    
                    smallBtn.className = defaultClass;
                    mediumBtn.className = defaultClass;
                    largeBtn.className = defaultClass;
                    
                    if (size === 'small') {
                        smallBtn.className = activeClass;
                    } else if (size === 'medium') {
                        mediumBtn.className = activeClass;
                    } else if (size === 'large') {
                        largeBtn.className = activeClass;
                    }
                }
                
                const currentFontSize = document.getElementById('current-font-size');
                if (currentFontSize) {
                    currentFontSize.textContent = size.charAt(0).toUpperCase() + size.slice(1);
                }
                
                const root = document.documentElement.style;
                if (size === 'small') {
                    root.setProperty('--font-size-base', '12px');
                } else if (size === 'large') {
                    root.setProperty('--font-size-base', '18px');
                } else {
                    root.setProperty('--font-size-base', '15px');
                }
            }
        });
    });
</script>