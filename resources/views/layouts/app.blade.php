<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'M-VIDEO') }}</title>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Vite Styles --}}
    @vite(['resources/css/app.css'])
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    <style>
        :root {
            --bg-primary: #0f172a;
        }
    </style>
</head>
<body>
    {{-- Content --}}
    {{ $slot }}
    
    {{-- Livewire Settings Modal Component --}}
    <livewire:dashboard.settings-modal />

    {{-- Vite Scripts --}}
    @vite(['resources/js/app.js'])
    
    {{-- Livewire Scripts --}}
    @livewireScripts
</body>
</html>
