<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'M-VIDEO') }} - Dashboard</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    {{-- Additional Styles --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-900">
    
    {{-- Dashboard Header (သီးသန့်) --}}
    @auth
        <livewire:user.user-header />
    @endauth

    {{-- Dashboard Sidebar (Optional) --}}
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 min-h-screen bg-gray-800/50 border-r border-gray-700/50 hidden lg:block">
            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700/50 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-gray-700/50 text-white' : '' }}" wire:navigate>
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700/50 rounded-lg transition" wire:navigate>
                    <i class="fas fa-home w-5"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('post.create.post') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700/50 rounded-lg transition" wire:navigate>
                    <i class="fas fa-plus-circle w-5"></i>
                    <span>Create Post</span>
                </a>
                <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700/50 rounded-lg transition" wire:navigate>
                    <i class="fas fa-user w-5"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('settings') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700/50 rounded-lg transition" wire:navigate>
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 min-h-screen">
            {{ $slot }}
        </main>
    </div>

    {{-- Livewire Scripts --}}
    @livewireScripts
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>