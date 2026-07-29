<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- Android Chrome Keyboard Resizing Fix --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, interactive-widget=resizes-content">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'M-VIDEO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-white dark:bg-[#121212] antialiased">
    {{ $slot }}

    <livewire:dashboard.settings-modal />

    @livewireScripts
</body>
</html>
