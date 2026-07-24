<!DOCTYPE html>
<html lang="{{ session()->get('locale', 'en') }}" class="{{ session()->get('theme', 'dark') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Settings' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        :root {
            --bg-primary: {{ session()->get('theme', 'dark') === 'dark' ? '#111827' : '#f3f4f6' }};
            --bg-secondary: {{ session()->get('theme', 'dark') === 'dark' ? '#1f2937' : '#e5e7eb' }};
            --bg-card: {{ session()->get('theme', 'dark') === 'dark' ? 'rgba(31, 41, 55, 0.5)' : 'rgba(243, 244, 246, 0.8)' }};
            --text-primary: {{ session()->get('theme', 'dark') === 'dark' ? '#ffffff' : '#1f2937' }};
            --text-secondary: {{ session()->get('theme', 'dark') === 'dark' ? '#9ca3af' : '#6b7280' }};
            --border-color: {{ session()->get('theme', 'dark') === 'dark' ? '#374151' : '#d1d5db' }};
        }
        
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .settings-card {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="min-h-screen">
        <main>
            {{ $slot }}
        </main>
        <livewire:layout.guest-nav />
    </div>
    
    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>