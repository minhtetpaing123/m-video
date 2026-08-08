<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- Android Chrome Keyboard Resizing Fix --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, interactive-widget=resizes-content">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'M-VIDEO') }}</title>

    {{-- ⚡ Dark Mode / Light Mode Persist Script (Reload & Livewire Navigate တွင် မပျောက်အောင် ထိန်းပေးမည်) --}}
    <script>
        (function() {
            function applyTheme() {
                const savedTheme = localStorage.getItem('theme');
                const userDbTheme = "{{ auth()->check() ? auth()->user()->theme : '' }}";
                const theme = savedTheme || userDbTheme;

                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else if (theme === 'system' || !theme) {
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }

            // Initial load
            applyTheme();

            // Livewire Page Navigation
            document.addEventListener('livewire:navigated', applyTheme);
        })();
    </script>

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
<body class="bg-white dark:bg-[#121212] text-gray-900 dark:text-white antialiased transition-colors duration-200">

    {{-- 🔔 Screen ပေါ်တွင် ပေါ်လာမည့် Visual In-App Notification Banner --}}
    <div id="inAppNotiBanner" class="fixed top-4 left-4 right-4 z-[9999] hidden max-w-md mx-auto transition-all duration-300 transform -translate-y-10 opacity-0">
        <div class="bg-gray-900/95 dark:bg-white/95 text-white dark:text-gray-900 p-4 rounded-2xl shadow-2xl backdrop-blur-md border border-gray-700/50 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 overflow-hidden cursor-pointer" onclick="window.location.href=this.dataset.url" id="inAppNotiContent" data-url="#">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 text-white font-bold text-lg">
                    🔔
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-bold text-sm truncate" id="inAppNotiTitle">အကြောင်းကြားစာ</h4>
                    <p class="text-xs opacity-80 truncate" id="inAppNotiBody">စာတိုအသစ် ရောက်ရှိလာပါသည်။</p>
                </div>
            </div>
            <button onclick="hideInAppBanner()" class="text-gray-400 hover:text-white dark:hover:text-black p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>

    {{ $slot }}

    <livewire:dashboard.settings-modal />

    {{-- 🔊 Sound Settings & Audio Element (Sound Mute/Unmute ကို Real-time ထိန်းချုပ်ပေးမည်) --}}
    <livewire:notification.noti-sound />

    @livewireScripts

    {{-- 🟢 Global Reverb Real-time Sound & Force System Push Notification Script --}}
    <script>
    let lastNotiTime = 0; // Noti ထပ်မတက်အောင် တားဆီးပေးမည့် Tracker Variable

    function requestNotiPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Screen ပေါ်တွင် In-App Banner Pop-up ပြသပေးမည့် Function
    function showInAppBanner(title, body, url, icon) {
        const banner = document.getElementById('inAppNotiBanner');
        const titleEl = document.getElementById('inAppNotiTitle');
        const bodyEl = document.getElementById('inAppNotiBody');
        const contentEl = document.getElementById('inAppNotiContent');

        if (banner && titleEl && bodyEl) {
            titleEl.innerText = title;
            bodyEl.innerText = body;
            contentEl.dataset.url = url || '/notifications';

            banner.classList.remove('hidden');
            setTimeout(() => {
                banner.classList.remove('-translate-y-10', 'opacity-0');
                banner.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            setTimeout(hideInAppBanner, 5000);
        }
    }

    function hideInAppBanner() {
        const banner = document.getElementById('inAppNotiBanner');
        if (banner) {
            banner.classList.remove('translate-y-0', 'opacity-100');
            banner.classList.add('-translate-y-10', 'opacity-0');
            setTimeout(() => {
                banner.classList.add('hidden');
            }, 300);
        }
    }

    // Android System Notification Bar ပေါ်တွင် Push Pop-up တက်စေမည့် Function
    function showSystemPushNotification(title, body, url, icon) {
        if (!('Notification' in window) || Notification.permission !== 'granted') {
            return;
        }

        const options = {
            body: body || 'နိုတီဖီကေးရှင်း အသစ် ရောက်ရှိလာပါသည်။',
            icon: icon || '/favicon.ico',
            vibrate: [200, 100, 200],
            tag: 'global-noti-tag', // Tag တစ်ခုတည်း သုံးထားသဖြင့် Noti အသစ်လာပါက အဟောင်းနေရာမှာ အစားထိုးပြမည်
            renotify: true,
            data: { url: url || '/notifications' }
        };

        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.ready.then((registration) => {
                registration.showNotification(title, options);
            }).catch(() => {
                new Notification(title, options);
            });
        } else {
            try {
                new Notification(title, options);
            } catch (e) {
                console.log("Notification Display Error:", e);
            }
        }
    }

    // Livewire Noti Sound Dispatcher
    window.playNotiSound = function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('play-notification-sound');
        }
    };

    // Global Realtime Listening Engine ( Noti Duplicate မဖြစ်အောင် ထိန်းပေးမည် )
    function registerGlobalAudioListeners() {
        requestNotiPermission();

        const currentUserId = "{{ auth()->id() }}";
        if (!currentUserId || typeof Echo === 'undefined') return;

        // Duplicate Echo Listener ဖြစ်မသွားစေရန် Channel ကို အရင် ရှင်းထုတ်မည်
        if (window.activeUserPrivateChannel) {
            Echo.leave(`App.Models.User.${currentUserId}`);
        }

        const handleIncomingNoti = (e) => {
            // 🔥 Noti ထပ်မတက်အောင် ၁ စက္ကန့်အတွင်း ဝင်လာသော Duplicate Noti များကို Block မည်
            const now = Date.now();
            if (now - lastNotiTime < 1000) {
                return;
            }
            lastNotiTime = now;

            console.log('🔥 REALTIME NOTIFICATION RECEIVED (SINGLE):', e);
            
            const title = e.title || e.data?.title || 'အကြောင်းကြားစာ အသစ်';
            const message = e.message || e.body || e.data?.message || 'တစ်စုံတစ်ခု ပြုလုပ်ခဲ့ပါသည်။';
            const url = e.url || e.data?.url || '/notifications';
            const icon = e.icon || e.data?.icon || '/favicon.ico';

            window.playNotiSound();

            showInAppBanner(title, message, url, icon);
            showSystemPushNotification(title, message, url, icon);

            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('refreshNotifications');
            }
        };

        // Channel ကို တိုက်ရိုက် ချိတ်ဆက်ပြီး Event ကို Multi-trigger မဖြစ်အောင် ဖမ်းမည်
        window.activeUserPrivateChannel = Echo.private(`App.Models.User.${currentUserId}`);
        
        // Single Listener Handler ဖြင့်သာ နားထောင်မည်
        window.activeUserPrivateChannel.notification(handleIncomingNoti);
        window.activeUserPrivateChannel.listen('.NotificationSent', handleIncomingNoti);
    }

    // Event Bindings
    document.addEventListener('livewire:navigated', registerGlobalAudioListeners);
    document.addEventListener('livewire:initialized', registerGlobalAudioListeners);
    document.addEventListener('DOMContentLoaded', registerGlobalAudioListeners);
    </script>
</body>
</html>
