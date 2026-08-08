@php
    $userAvatar = Auth::check() && Auth::user()->avatar 
        ? Auth::user()->avatar 
        : 'https://graph.facebook.com/' . Auth::id() . '/picture?type=square&width=40&height=40';
@endphp

<header 
    x-data="{ 
        isDark: document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark' 
    }" 
    x-init="
        const checkTheme = () => { isDark = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark'; };
        checkTheme();
        const observer = new MutationObserver(checkTheme);
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    "
    :style="isDark ? 'background-color: #1e2235 !important; color: #ffffff !important;' : ''"
    class="mv-header sticky top-0 bg-white dark:bg-[#1e2235] shadow-sm z-[999]"
>
    <div class="mv-mobile-header relative" :style="isDark ? 'background-color: #1e2235 !important;' : ''">
        <div class="mv-top-bar" :style="isDark ? 'background-color: #1e2235 !important;' : ''">
            <div class="mv-top-left">
                <div class="logo-container">
                    <a href="/" class="logo-link">
                        <div class="logo-icon-container">
                            <svg class="logo-svg" :style="isDark ? 'color: #ffffff !important;' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="28" height="28">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="logo-text-container">
                            <span class="logo-main" :style="isDark ? 'color: #ffffff !important;' : ''">M-VIDEO</span>
                            <span class="logo-sub" :style="isDark ? 'color: #9ca3af !important;' : ''">PREMIUM CONTENT</span>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="mv-top-right">
                {{-- Separate User Search Component Call --}}
                <livewire:dashboard.post.user-search />
                
                <div 
                    @click="$dispatch('open-create-post-modal')" 
                    class="mv-icon-circle mv-add-icon-btn cursor-pointer" 
                    :style="isDark ? 'background-color: #2a2f45 !important; color: #ffffff !important;' : ''"
                    role="button" 
                    aria-label="Create post" 
                    tabindex="0"
                >
                    <svg viewBox="0 0 28 28" width="22" height="22" :style="isDark ? 'fill: #ffffff !important;' : 'fill: #050505;'">
                        <path d="M14 3.5c.69 0 1.25.56 1.25 1.25v8h8c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25h-8v8c0 .69-.56 1.25-1.25 1.25s-1.25-.56-1.25-1.25v-8h-8c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25h8v-8c0-.69.56-1.25 1.25-1.25z"/>
                    </svg>
                </div>

                {{-- Replaced Messenger Icon with Notification Icon and Component --}}
                <a 
                    href="{{ Route::has('noti') ? route('noti') : '/noti' }}" 
                    class="mv-icon-circle relative" 
                    :style="isDark ? 'background-color: #2a2f45 !important; color: #ffffff !important;' : ''"
                    aria-label="Notifications"
                >
                    <livewire:notification.notification-badge />
                </a>

                <div 
                    @click="$dispatch('open-settings-modal')" 
                    class="mv-icon-circle cursor-pointer transition-colors" 
                    :style="isDark ? 'background-color: #2a2f45 !important; color: #ffffff !important;' : ''"
                    role="button" 
                    aria-label="Settings" 
                    tabindex="0"
                >
                    <svg class="w-5 h-5" :style="isDark ? 'color: #ffffff !important;' : 'color: #374151;'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</header>
