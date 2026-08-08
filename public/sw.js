// public/sw.js (Production - Ultra Fast Optimized)

const CACHE_NAME = 'mvideo-static-v1';
const VIDEO_CACHE = 'video-v1';

// Install event
self.addEventListener('install', event => {
    console.log('⚡ Service Worker installing...');
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', event => {
    console.log('⚡ Service Worker activating...');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME && cacheName !== VIDEO_CACHE) {
                        console.log('🗑️ Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    event.waitUntil(clients.claim());
});

// Fetch event
self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);
    
    // ✅ PRODUCTION: logout ကို လုံးဝမကိုင်ပါနဲ့ (Browser သို့ တိုက်ရိုက်လွှတ်မည်)
    if (url.pathname === '/logout' || url.pathname.includes('/logout')) {
        return; 
    }
    
    // 1. Ultra Fast: Static Assets Caching (CSS, JS, Fonts, Images, Audio) - Cache First Strategy
    const isStaticAsset = url.pathname.match(/\.(css|js|woff2?|ttf|eot|png|jpg|jpeg|webp|svg|gif|ico|mp3)$/i) ||
                          url.hostname.includes('fonts.googleapis.com') ||
                          url.hostname.includes('fonts.gstatic.com');

    if (isStaticAsset) {
        event.respondWith(
            caches.open(CACHE_NAME).then(async cache => {
                const cachedResponse = await cache.match(event.request);
                if (cachedResponse) {
                    return cachedResponse;
                }

                try {
                    const response = await fetch(event.request);
                    if (response && response.status === 200) {
                        cache.put(event.request, response.clone());
                    }
                    return response;
                } catch (error) {
                    return cachedResponse || new Response('Asset Fetch Error', { status: 503 });
                }
            })
        );
        return;
    }

    // 2. Video requests detection & caching
    const isVideoRequest = url.pathname.includes('/video/') ||
                           url.pathname.includes('/videos/') ||
                           url.pathname.includes('/storage/posts/videos/') ||
                           url.pathname.match(/\.(mp4|m3u8|ts|webm|mov|mkv)($|\?)/i) ||
                           event.request.destination === 'video';

    if (isVideoRequest) {
        event.respondWith(
            caches.open(VIDEO_CACHE).then(async cache => {
                let response = await cache.match(event.request);
                
                if (response) {
                    console.log('✅ Video Cache Hit:', url.pathname);
                    return response;
                }
                
                try {
                    console.log('⬇️ Fetching Video:', url.pathname);
                    response = await fetch(event.request);
                    
                    // 💡 Status 200, 206 (Partial Content) နှင့် 0 (Opaque/Cross-origin) များကို လက်ခံရန်
                    if (response && (response.status === 200 || response.status === 206 || response.status === 0)) {
                        const clone = response.clone();
                        cache.put(event.request, clone);
                        console.log('✅ Cached Video successfully:', url.pathname);
                    }
                    
                    return response;
                    
                } catch (error) {
                    console.error('❌ Video Fetch error:', error);
                    return new Response('Media Network Error', { status: 503 });
                }
            })
        );
        return;
    }
    
    // Default: Network first Strategy for other pages
    event.respondWith(
        fetch(event.request).catch(async () => {
            const cached = await caches.match(event.request);
            return cached || new Response('Offline', { status: 503 });
        })
    );
});

// =========================================================
// 🟢 WebSocket / Reverb + Web Push Notification Event Listeners
// =========================================================

// 1. WebSocket / Livewire (postMessage) မှ ရောက်ရှိလာသော Noti ကို ဖုန်း Screen တွင် Pop-up ပြသခြင်း
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SHOW_NOTIFICATION') {
        const payload = event.data.payload || {};
        
        const options = {
            body: payload.message || payload.body || 'နိုတီဖီကေးရှင်း အသစ် ရောက်ရှိလာပါသည်။',
            icon: payload.icon || '/favicon.ico',
            badge: payload.badge || '/favicon.ico',
            vibrate: [200, 100, 200],
            data: {
                url: payload.url || '/noti'
            }
        };

        self.registration.showNotification(payload.title || 'အကြောင်းကြားစာ', options);
    }
});

// 2. Standard Web Push Event (FCM သို့မဟုတ် Backend WebPush)
self.addEventListener('push', function (event) {
    if (!event.data) return;

    try {
        const data = event.data.json();
        const options = {
            body: data.message || data.body || 'You have a new notification!',
            icon: data.icon || '/favicon.ico',
            badge: data.badge || '/favicon.ico',
            vibrate: [100, 50, 100],
            data: {
                url: data.url || '/noti'
            }
        };

        event.waitUntil(
            self.registration.showNotification(data.title || 'New Notification', options)
        );
    } catch (e) {
        console.error('Push Event Error:', e);
    }
});

// 3. Notification Click Event: Noti ကို နှိပ်လိုက်ပါက သက်ဆိုင်ရာ Link သို့ ခေါ်ဆောင်ပေးမည်
self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/noti';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (let i = 0; i < clientList.length; i++) {
                let client = clientList[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
