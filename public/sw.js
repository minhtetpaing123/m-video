// public/sw.js
const CACHE_NAME = 'mvideo-cache-v1';
const VIDEO_CACHE = 'video-cache-v1';

// Install event
self.addEventListener('install', event => {
    console.log('Service Worker installing...');
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', event => {
    console.log('Service Worker activating...');
    event.waitUntil(clients.claim());
});

// Fetch event - cache videos
self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);
    
    // Cache video files
    if (url.pathname.includes('/storage/posts/videos/')) {
        event.respondWith(
            caches.open(VIDEO_CACHE).then(async cache => {
                const cachedResponse = await cache.match(event.request);
                if (cachedResponse) {
                    console.log('Serving from cache:', url.pathname);
                    return cachedResponse;
                }
                
                console.log('Fetching and caching:', url.pathname);
                const networkResponse = await fetch(event.request);
                cache.put(event.request, networkResponse.clone());
                return networkResponse;
            })
        );
    } else {
        // For other requests, try network first, then cache
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(event.request);
            })
        );
    }
});