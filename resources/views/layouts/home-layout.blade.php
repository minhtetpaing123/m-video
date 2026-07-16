<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      class="{{ session('theme', 'dark') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>M-VIDEO - Home</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- CSS -->
    @vite(['resources/css/app.css','resources/js/app.js'])
    
    <style>
        /* ============================================ */
        /* THEME VARIABLES */
        /* ============================================ */
        :root {
            /* Dark Theme (Default) */
            --bg-primary: #0d0d0d;
            --bg-secondary: #1a1a2e;
            --bg-card: #1a1a2e;
            --bg-card-hover: #2a2a3e;
            --bg-input: #18191a;
            --text-primary: #ffffff;
            --text-secondary: #b0b3b8;
            --text-muted: #6b6f76;
            --border-color: #2a2a3e;
            --shadow-color: rgba(229, 9, 20, 0.2);
            --nav-bg: #1a1a2e;
        }

        /* Light Theme */
        html.light {
            --bg-primary: #f0f2f5;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-card-hover: #f0f2f5;
            --bg-input: #f0f2f5;
            --text-primary: #1a1a2e;
            --text-secondary: #4a4a5a;
            --text-muted: #8a8d91;
            --border-color: #e4e6eb;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --nav-bg: #ffffff;
        }

        /* ============================================ */
        /* GLOBAL STYLES */
        /* ============================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Figtree', sans-serif; 
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
            min-height: 100vh;
        }

        /* Theme Transition */
        .theme-transition {
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Line clamp for title */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ============================================ */
        /* HEADER STYLES */
        /* ============================================ */
        .header-container {
            background: var(--nav-bg);
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        /* ============================================ */
        /* CATEGORY NAV STYLES */
        /* ============================================ */
        .category-nav {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s ease, border-color 0.3s ease;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        
        .category-nav::-webkit-scrollbar {
            display: none;
        }

        .category-nav a {
            display: inline-block;
            padding: 8px 16px;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .category-nav a:hover {
            color: var(--text-primary);
            border-bottom-color: #e50914;
        }

        .category-nav a.active {
            color: var(--text-primary);
            border-bottom-color: #e50914;
        }

        /* ============================================ */
        /* FOOTER STYLES */
        /* ============================================ */
        .footer-container {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        /* ============================================ */
        /* RESPONSIVE */
        /* ============================================ */
        @media (max-width: 640px) {
            .category-nav a {
                font-size: 11px;
                padding: 6px 12px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="theme-transition">

    <!-- ============================================ -->
    <!-- HEADER -->
    <!-- ============================================ -->
    <x-header />

    <!-- ============================================ -->
    <!-- CATEGORY NAVIGATION -->
    <!-- ============================================ -->
    @include('layouts.category')

    <!-- ============================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================ -->
    <main>
        @yield('content')
    </main>

    <!-- ============================================ -->
    <!-- FOOTER -->
    <!-- ============================================ -->
    @include('layouts.footer')

    <!-- ============================================ -->
    <!-- THEME TOGGLE SCRIPT -->
    <!-- ============================================ -->
    <script>
        // ============================================
        // THEME MANAGEMENT
        // ============================================
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.classList.contains('light') ? 'light' : 'dark';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            // Update class
            html.classList.remove(currentTheme);
            html.classList.add(newTheme);
            
            // Save to localStorage
            localStorage.setItem('theme', newTheme);
            
            // Update icon if exists
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.textContent = theme === 'light' ? '🌙' : '☀️';
            }
        }

        function loadTheme() {
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('theme');
            
            // Remove default class first
            html.classList.remove('dark', 'light');
            
            if (savedTheme) {
                // Use saved theme
                html.classList.add(savedTheme);
                updateThemeIcon(savedTheme);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                // System preference: Light
                html.classList.add('light');
                updateThemeIcon('light');
            } else {
                // Default: Dark
                html.classList.add('dark');
                updateThemeIcon('dark');
            }
            
            // Dispatch event for other components
            document.dispatchEvent(new CustomEvent('themeChanged', {
                detail: { theme: html.classList.contains('light') ? 'light' : 'dark' }
            }));
        }

        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', (e) => {
                // Only change if user hasn't manually saved a theme
                if (!localStorage.getItem('theme')) {
                    const theme = e.matches ? 'light' : 'dark';
                    document.documentElement.classList.remove('dark', 'light');
                    document.documentElement.classList.add(theme);
                    updateThemeIcon(theme);
                    
                    document.dispatchEvent(new CustomEvent('themeChanged', {
                        detail: { theme: theme }
                    }));
                }
            });
        }

        // Load theme on page load
        document.addEventListener('DOMContentLoaded', loadTheme);
        
        // Expose toggle function globally
        window.toggleTheme = toggleTheme;
    </script>

    @stack('scripts')
</body>
</html>