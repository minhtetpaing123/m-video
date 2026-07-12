// public/sw.js (Final Version)

const CACHE_NAME = 'mvideo-v1';
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
    
    console.log('🔍 Fetch:', url.pathname);
    
    // Cache video requests
    if (url.pathname.includes('/video/') || 
        url.pathname.includes('.mp4') ||
        url.pathname.includes('/storage/posts/videos/')) {
        
        event.respondWith(
            caches.open(VIDEO_CACHE).then(async cache => {
                // Check cache first
                let response = await cache.match(event.request);
                
                if (response) {
                    console.log('✅ Cached:', url.pathname);
                    return response;
                }
                
                try {
                    console.log('⬇️ Fetching:', url.pathname);
                    response = await fetch(event.request);
                    
                    // Cache successful responses
                    if (response && response.status === 200) {
                        const clone = response.clone();
                        cache.put(event.request, clone);
                        console.log('✅ Cached successfully:', url.pathname);
                    }
                    
                    return response;
                    
                } catch (error) {
                    console.error('❌ Fetch error:', error);
                    return new Response('Network error', { status: 503 });
                }
            })
        );
        return;
    }
    
    // Default: network first
    event.respondWith(
        fetch(event.request).catch(async () => {
            const cached = await caches.match(event.request);
            return cached || new Response('Offline', { status: 503 });
        })
    );
});