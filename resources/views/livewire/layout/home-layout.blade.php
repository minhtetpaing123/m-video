<!DOCTYPE html>
<html lang="{{ session()->get('locale', 'en') }}" class="{{ session()->get('theme', 'dark') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>M-VIDEO - Home</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- CSS & JS (Vite handles Livewire & Alpine) -->
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="theme-transition">

    <!-- LIVEWIRE HEADER -->
    <livewire:header.header />

    <!-- CATEGORY NAVIGATION -->
    <livewire:layout.category />

    <!-- MAIN CONTENT -->
    <main>
        {{ $slot }}
    </main>

    <!-- SCRIPTS -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.classList.contains('light') ? 'light' : 'dark';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.classList.remove(currentTheme);
            html.classList.add(newTheme);
            
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
            
            Livewire.dispatch('theme-changed', { theme: newTheme });
            
            fetch('{{ route('settings.theme') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme: newTheme })
            });
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.textContent = theme === 'light' ? '🌙' : '☀️';
            }
        }

        function loadTheme() {
            const html = document.documentElement;
            const sessionTheme = '{{ session('theme', '') }}';
            
            if (sessionTheme) {
                html.classList.remove('dark', 'light');
                html.classList.add(sessionTheme);
                updateThemeIcon(sessionTheme);
                return;
            }
            
            const savedTheme = localStorage.getItem('theme');
            html.classList.remove('dark', 'light');
            
            if (savedTheme) {
                html.classList.add(savedTheme);
                updateThemeIcon(savedTheme);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                html.classList.add('light');
                updateThemeIcon('light');
            } else {
                html.classList.add('dark');
                updateThemeIcon('dark');
            }
            
            document.dispatchEvent(new CustomEvent('themeChanged', {
                detail: { theme: html.classList.contains('light') ? 'light' : 'dark' }
            }));
        }

        function applyFontSize() {
            const fontSize = '{{ session()->get('font_size', 'medium') }}';
            const root = document.documentElement.style;
            
            if (fontSize === 'small') {
                root.setProperty('--font-size-base', '12px');
            } else if (fontSize === 'large') {
                root.setProperty('--font-size-base', '18px');
            } else {
                root.setProperty('--font-size-base', '15px');
            }
        }

        document.addEventListener('livewire:navigated', function() {
            loadTheme();
            applyFontSize();
        });

        Livewire.on('font-size-changed', (data) => {
            const size = data.size;
            const root = document.documentElement.style;
            
            if (size === 'small') {
                root.setProperty('--font-size-base', '12px');
            } else if (size === 'large') {
                root.setProperty('--font-size-base', '18px');
            } else {
                root.setProperty('--font-size-base', '15px');
            }
        });

        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', (e) => {
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

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            applyFontSize();
        });
        
        window.toggleTheme = toggleTheme;
    </script>

    @stack('scripts')
</body>
</html>