import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// ✅ CSRF Token ကို ရယူပါ
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

console.log('🔧 Initializing Echo...');

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || 'localhost',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    activityTimeout: 30000,
    pongTimeout: 10000,
    // ✅ Auth Endpoint နဲ့ CSRF Token ထည့်ပါ
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        }
    }
});

console.log('✅ Echo initialized!');

// ✅ WebSocket Connection Events
if (window.Echo && window.Echo.connector && window.Echo.connector.socket) {
    window.Echo.connector.socket.addEventListener('open', () => {
        console.log('✅ WebSocket connected!');
    });

    window.Echo.connector.socket.addEventListener('error', (error) => {
        console.error('❌ WebSocket error:', error);
    });

    window.Echo.connector.socket.addEventListener('close', () => {
        console.log('⚠️ WebSocket disconnected');
    });
}

// ✅ Presence Channel ကို Join လုပ်ပါ
setTimeout(() => {
    console.log('🔄 Joining presence channel: online');
    
    if (!window.Echo) {
        console.error('❌ Echo is not defined!');
        return;
    }
    
    const channel = window.Echo.join('online');
    
    channel
        .here((users) => {
            console.log('✅ Online users:', users);
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('updateOnlineUsers', { users: users });
            }
        })
        .joining((user) => {
            console.log('👤 User joined:', user);
            window.Echo.join('online').here((users) => {
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('updateOnlineUsers', { users: users });
                }
            });
        })
        .leaving((user) => {
            console.log('👋 User left:', user);
            window.Echo.join('online').here((users) => {
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('updateOnlineUsers', { users: users });
                }
            });
        })
        .error((error) => {
            console.error('❌ Presence Channel error:', error);
        });
}, 2000);