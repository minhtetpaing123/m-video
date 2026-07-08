<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- jQuery ထည့်ရန် (Vite နဲ့အပြိုင်) --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            {{-- Navigation include ကိုဖယ်ရှားလိုက်ပါ --}}
            {{-- @include('layouts.navigation') --}}

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <script>
        // CSRF Token setup for jQuery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('Service Worker registered with scope:', registration.scope);
                        
                        // Check if there's a controller already
                        if (navigator.serviceWorker.controller) {
                            console.log('Service Worker controller active');
                        } else {
                            console.log('Waiting for Service Worker controller...');
                        }
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
                
                // Listen for controller changes
                navigator.serviceWorker.addEventListener('controllerchange', function() {
                    console.log('Service Worker controller changed');
                });
            });
        }

        // Function to check cache status
        function checkVideoCache(videoUrl) {
            if ('caches' in window) {
                caches.open('video-cache-v1').then(cache => {
                    cache.match(videoUrl).then(response => {
                        if (response) {
                            console.log('Video found in cache:', videoUrl);
                        } else {
                            console.log('Video not in cache:', videoUrl);
                        }
                    });
                });
            }
        }
        </script>
    </body>
</html>