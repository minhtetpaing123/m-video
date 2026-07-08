<?php
// setup.php - Run: php setup.php

$basePath = __DIR__;
$cssPath = $basePath . '/resources/css/user';
$viewPath = $basePath . '/resources/views/components/user-header';

// ============================================
// 1. user-header.css (Main File)
// ============================================
$mainCss = <<<'CSS'
/* Facebook Style Navigation - Mobile First */
:root {
    --primary: #1877F2;
    --primary-dark: #166FE5;
    --bg: #FFFFFF;
    --bg-secondary: #F0F2F5;
    --bg-hover: #E4E6E9;
    --text: #050505;
    --text-secondary: #65676B;
    --border: #DADDE1;
    --shadow: 0 1px 2px rgba(0,0,0,0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
    background: #F0F2F5;
    padding-top: 108px;
    padding-bottom: 56px;
}

/* Desktop Navigation */
.desktop-nav {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 56px;
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 0 16px;
    z-index: 1000;
}

.desktop-nav-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.nav-left {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 320px;
}

.logo svg {
    width: 40px;
    height: 40px;
    fill: var(--primary);
}

.search-box {
    display: flex;
    align-items: center;
    background: var(--bg-secondary);
    border-radius: 50px;
    padding: 0 12px;
    height: 40px;
    width: 240px;
}

.search-box svg {
    width: 16px;
    height: 16px;
    fill: var(--text-secondary);
    margin-right: 8px;
}

.search-box input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 15px;
    width: 100%;
}

.nav-center {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 1;
}

.nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 108px;
    height: 56px;
    border-radius: 8px;
    color: var(--text-secondary);
    position: relative;
}

.nav-icon:hover {
    background: var(--bg-hover);
}

.nav-icon.active {
    color: var(--primary);
}

.nav-icon.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 8px;
    right: 8px;
    height: 3px;
    background: var(--primary);
}

.nav-icon svg {
    width: 28px;
    height: 28px;
    fill: currentColor;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 320px;
    justify-content: flex-end;
}

.icon-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-secondary);
    cursor: pointer;
}

.icon-circle:hover {
    background: var(--bg-hover);
}

.icon-circle svg {
    width: 20px;
    height: 20px;
    fill: var(--text);
}

.profile {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px 4px 4px;
    border-radius: 50px;
    cursor: pointer;
}

.profile:hover {
    background: var(--bg-hover);
}

.profile img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.profile span {
    font-size: 15px;
    font-weight: 600;
    color: var(--text);
}

/* Mobile Header */
.mobile-header {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: white;
    border-bottom: 1px solid var(--border);
    z-index: 1000;
}

.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    height: 56px;
}

.mobile-logo {
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
}

.mobile-logo svg {
    width: 32px;
    height: 32px;
    fill: var(--primary);
}

.mobile-logo span {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
    letter-spacing: -0.5px;
}

.mobile-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.mobile-actions .icon-circle {
    width: 40px;
    height: 40px;
}

.mobile-actions .icon-circle svg {
    width: 22px;
    height: 22px;
}

/* Mobile Search */
.mobile-search {
    padding: 8px 12px;
    border-top: 1px solid var(--border);
}

.search-wrapper {
    position: relative;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-icon svg {
    width: 18px;
    height: 18px;
    fill: var(--text-secondary);
}

.search-wrapper input {
    width: 100%;
    height: 40px;
    padding: 0 12px 0 44px;
    background: var(--bg-secondary);
    border: none;
    border-radius: 50px;
    font-size: 16px;
    outline: none;
}

.search-wrapper input:focus {
    background: white;
    box-shadow: 0 0 0 1px var(--primary);
}

/* Bottom Navigation */
.bottom-nav {
    display: flex;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 56px;
    background: white;
    border-top: 1px solid var(--border);
    align-items: center;
    justify-content: space-around;
    z-index: 1000;
    padding: 0 4px;
}

.bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 4px 0;
    flex: 1;
}

.bottom-nav-item svg {
    width: 24px;
    height: 24px;
    fill: currentColor;
    margin-bottom: 2px;
}

.bottom-nav-item span {
    font-size: 11px;
    font-weight: 500;
}

.bottom-nav-item.active {
    color: var(--primary);
}

/* Desktop Breakpoint */
@media (min-width: 901px) {
    body {
        padding-top: 56px;
        padding-bottom: 0;
    }
    
    .desktop-nav {
        display: block;
    }
    
    .mobile-header {
        display: none;
    }
    
    .bottom-nav {
        display: none;
    }
}

/* Tablet Breakpoint */
@media (min-width: 701px) and (max-width: 900px) {
    .nav-left, .nav-right {
        flex: 0 0 auto;
    }
    
    .profile span {
        display: none;
    }
    
    .search-box {
        width: 200px;
    }
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
    :root {
        --bg: #242526;
        --bg-secondary: #3A3B3C;
        --bg-hover: #4E4F50;
        --text: #E4E6EB;
        --text-secondary: #B0B3B8;
        --border: #3E4042;
    }
    
    body {
        background: #18191A;
    }
    
    .desktop-nav,
    .mobile-header,
    .bottom-nav {
        background: #242526;
        border-color: #3E4042;
    }
    
    .search-box input,
    .search-wrapper input {
        background: #3A3B3C;
        color: #E4E6EB;
    }
    
    .search-box input:focus,
    .search-wrapper input:focus {
        background: #242526;
    }
}
CSS;

// ============================================
// 2. nav.blade.php
// ============================================
$navBlade = <<<'BLADE'
{{-- Desktop Navigation --}}
<div class="desktop-nav">
    <div class="desktop-nav-content">
        {{-- Left --}}
        <div class="nav-left">
            <a href="/" class="logo">
                <svg viewBox="0 0 36 36">
                    <path d="M20.181 35.87C29.094 34.791 36 27.202 36 18c0-9.941-8.059-18-18-18S0 8.059 0 18c0 9.202 6.906 16.791 15.819 17.87v-12.94h-4.436V18h4.436v-3.012c0-6.786 2.624-9.62 8.109-9.62 1.464 0 2.376.119 2.82.173v3.594h-2.472c-1.752 0-2.336.83-2.336 2.403V18h4.684l-.758 4.93h-3.926v12.94z"/>
                </svg>
            </a>
            <div class="search-box">
                <svg viewBox="0 0 16 16">
                    <path d="M10.116 2.627a4.5 4.5 0 1 0-6.073 6.073 4.5 4.5 0 0 0 6.073-6.073zm-.884.884a3.5 3.5 0 1 1-4.95 4.95 3.5 3.5 0 0 1 4.95-4.95zM14 13.293l-3.538-3.538a.5.5 0 0 0-.708.708L13.293 14H14v-.707z"/>
                </svg>
                <input type="search" placeholder="Search Facebook">
            </div>
        </div>

        {{-- Center --}}
        <div class="nav-center">
            <a href="#" class="nav-icon active">
                <svg viewBox="0 0 28 28">
                    <path d="M25.825 12.29l-.004-.004-10-8.774a2.5 2.5 0 0 0-3.307.043L2.156 12.305a1 1 0 0 0-.156 1.406 1 1 0 0 0 1.406.156l1.094-.937V24a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V12.98l1.047.898a1 1 0 0 0 1.297-1.523l-.02-.065z"/>
                </svg>
            </a>
            <a href="#" class="nav-icon">
                <svg viewBox="0 0 28 28">
                    <path d="M8.75 11.5a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0zm10.5 0a2.75 2.75 0 1 1 5.5 0 2.75 2.75 0 0 1-5.5 0z"/>
                </svg>
            </a>
            <a href="#" class="nav-icon">
                <svg viewBox="0 0 28 28">
                    <path d="M8 6.5v15a2.5 2.5 0 0 0 2.5 2.5h7a2.5 2.5 0 0 0 2.5-2.5v-15a2.5 2.5 0 0 0-2.5-2.5h-7A2.5 2.5 0 0 0 8 6.5z"/>
                </svg>
            </a>
            <a href="#" class="nav-icon">
                <svg viewBox="0 0 28 28">
                    <path d="M7.5 7h13A2.5 2.5 0 0 1 23 9.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 5 18.5v-9A2.5 2.5 0 0 1 7.5 7z"/>
                </svg>
            </a>
            <a href="#" class="nav-icon">
                <svg viewBox="0 0 28 28">
                    <path d="M20.34 22.092c-1.318.414-2.853.647-4.484.647-2.465 0-4.653-.592-6.36-1.668.87-1.244 2.377-2.11 4.423-2.33.227.472.565.888.98 1.217-.836.251-1.753.387-2.663.387a9.5 9.5 0 0 1-1.87-.184c.916.993 2.386 1.74 4.11 1.74.875 0 1.688-.147 2.417-.403.417.276.898.49 1.413.63z"/>
                </svg>
            </a>
        </div>

        {{-- Right --}}
        <div class="nav-right">
            <div class="icon-circle">
                <svg viewBox="0 0 20 20">
                    <path d="M4 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm8 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
                </svg>
            </div>
            <div class="icon-circle">
                <svg viewBox="0 0 28 28">
                    <path d="M14 6.5c-4.694 0-8.5 3.533-8.5 7.89 0 2.368.99 4.497 2.628 6.057.18.173.293.4.314.642.04.49-.062 1.011-.285 1.637-.181.51-.484 1.067-.842 1.57.488-.041 1.013-.182 1.546-.4.746-.306 1.308-.579 1.808-.78.324-.13.634-.09.938.044 1.055.466 2.22.73 3.394.73 4.695 0 8.5-3.533 8.5-7.89S18.695 6.5 14 6.5z"/>
                </svg>
            </div>
            <div class="icon-circle">
                <svg viewBox="0 0 28 28">
                    <path d="M7.847 17.365l1.528-2.065a1.5 1.5 0 0 0 .294-.888V10.5c0-2.94 2.385-5.25 5.332-5.25 2.947 0 5.332 2.31 5.332 5.25v3.912c0 .32.106.631.298.885l1.54 2.068c.692.927.032 2.385-1.092 2.385H8.943c-1.128 0-1.788-1.458-1.096-2.385z"/>
                </svg>
            </div>
            <div class="profile">
                <img src="https://ui-avatars.com/api/?name=User&size=32" alt="profile">
                <span>User</span>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Header --}}
<div class="mobile-header">
    <div class="top-bar">
        <a href="/" class="mobile-logo">
            <svg viewBox="0 0 36 36">
                <path d="M20.181 35.87C29.094 34.791 36 27.202 36 18c0-9.941-8.059-18-18-18S0 8.059 0 18c0 9.202 6.906 16.791 15.819 17.87v-12.94h-4.436V18h4.436v-3.012c0-6.786 2.624-9.62 8.109-9.62 1.464 0 2.376.119 2.82.173v3.594h-2.472c-1.752 0-2.336.83-2.336 2.403V18h4.684l-.758 4.93h-3.926v12.94z"/>
            </svg>
            <span>facebook</span>
        </a>
        <div class="mobile-actions">
            <div class="icon-circle">
                <svg viewBox="0 0 20 20">
                    <path d="M4 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm8 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
                </svg>
            </div>
            <div class="icon-circle">
                <svg viewBox="0 0 28 28">
                    <path d="M14 6.5c-4.694 0-8.5 3.533-8.5 7.89 0 2.368.99 4.497 2.628 6.057.18.173.293.4.314.642.04.49-.062 1.011-.285 1.637-.181.51-.484 1.067-.842 1.57.488-.041 1.013-.182 1.546-.4.746-.306 1.308-.579 1.808-.78.324-.13.634-.09.938.044 1.055.466 2.22.73 3.394.73 4.695 0 8.5-3.533 8.5-7.89S18.695 6.5 14 6.5z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="mobile-search">
        <div class="search-wrapper">
            <span class="search-icon">
                <svg viewBox="0 0 16 16">
                    <path d="M10.116 2.627a4.5 4.5 0 1 0-6.073 6.073 4.5 4.5 0 0 0 6.073-6.073zm-.884.884a3.5 3.5 0 1 1-4.95 4.95 3.5 3.5 0 0 1 4.95-4.95zM14 13.293l-3.538-3.538a.5.5 0 0 0-.708.708L13.293 14H14v-.707z"/>
                </svg>
            </span>
            <input type="search" placeholder="Search Facebook">
        </div>
    </div>
</div>

{{-- Bottom Navigation --}}
<div class="bottom-nav">
    <a href="#" class="bottom-nav-item active">
        <svg viewBox="0 0 28 28">
            <path d="M25.825 12.29l-.004-.004-10-8.774a2.5 2.5 0 0 0-3.307.043L2.156 12.305a1 1 0 0 0-.156 1.406 1 1 0 0 0 1.406.156l1.094-.937V24a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V12.98l1.047.898a1 1 0 0 0 1.297-1.523l-.02-.065z"/>
        </svg>
        <span>Home</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <svg viewBox="0 0 28 28">
            <path d="M8.75 11.5a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0z"/>
        </svg>
        <span>Friends</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <svg viewBox="0 0 28 28">
            <path d="M8 6.5v15a2.5 2.5 0 0 0 2.5 2.5h7a2.5 2.5 0 0 0 2.5-2.5v-15a2.5 2.5 0 0 0-2.5-2.5h-7A2.5 2.5 0 0 0 8 6.5z"/>
        </svg>
        <span>Watch</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <svg viewBox="0 0 28 28">
            <path d="M14 6.5c-4.694 0-8.5 3.533-8.5 7.89 0 2.368.99 4.497 2.628 6.057.18.173.293.4.314.642.04.49-.062 1.011-.285 1.637-.181.51-.484 1.067-.842 1.57.488-.041 1.013-.182 1.546-.4.746-.306 1.308-.579 1.808-.78.324-.13.634-.09.938.044 1.055.466 2.22.73 3.394.73 4.695 0 8.5-3.533 8.5-7.89S18.695 6.5 14 6.5z"/>
        </svg>
        <span>Messages</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <svg viewBox="0 0 28 28">
            <path d="M7.847 17.365l1.528-2.065a1.5 1.5 0 0 0 .294-.888V10.5c0-2.94 2.385-5.25 5.332-5.25 2.947 0 5.332 2.31 5.332 5.25v3.912c0 .32.106.631.298.885l1.54 2.068c.692.927.032 2.385-1.092 2.385H8.943c-1.128 0-1.788-1.458-1.096-2.385z"/>
        </svg>
        <span>Notifications</span>
    </a>
</div>
BLADE;

// ============================================
// Create Directories & Write Files
// ============================================
echo "\n📦 Facebook Style Navigation Setup\n";
echo "================================\n\n";

// Create CSS directory
if (!is_dir($cssPath)) {
    mkdir($cssPath, 0755, true);
    echo "📁 Created: resources/css/user/\n";
}

// Write CSS file
file_put_contents($cssPath . '/user-header.css', $mainCss);
echo "✅ Created: resources/css/user/user-header.css\n";

// Create Blade directory
if (!is_dir($viewPath)) {
    mkdir($viewPath, 0755, true);
    echo "📁 Created: resources/views/components/user-header/\n";
}

// Write Blade file
file_put_contents($viewPath . '/nav.blade.php', $navBlade);
echo "✅ Created: resources/views/components/user-header/nav.blade.php\n";

echo "\n================================\n";
echo "✅ Setup Complete!\n";
echo "================================\n\n";
echo "📌 Next Steps:\n";
echo "1. Add CSS to your layout:\n";
echo "   <link rel=\"stylesheet\" href=\"{{ asset('css/user/user-header.css') }}\">\n";
echo "   or @vite('resources/css/user/user-header.css')\n\n";
echo "2. Add navigation to your layout:\n";
echo "   <x-user-header.nav />\n\n";
echo "3. Run: php artisan storage:link (if using avatars)\n\n";