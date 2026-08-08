// resources/js/app.js

// Import bootstrap
import './bootstrap';

// Import user header module
import { initUserHeader } from './user/user-header';
// resources/js/app.js

import './infinite-scroll.js'; // 🟢 ဒီလိုင်းလေး ဖြည့်ပေးပါ

// Initialize user header
document.addEventListener('DOMContentLoaded', () => {
    initUserHeader();
});

// Import Header JavaScript
import './components/header';

// Import Comment System
import CommentSystem from './home/comment';

// Global MVideoApp object
window.MVideoApp = {
    version: '1.0.0',
    onlineUserIds: new Set(),
    offlineTimers: {}, 
    userLastSeenTimes: {}, 

    showAlert: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = 'app-alert';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 300px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    },
    
    toggleDarkMode: function() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        
        document.dispatchEvent(new CustomEvent('darkMode:toggled', {
            detail: { darkMode: document.documentElement.classList.contains('dark') }
        }));
        
        this.showAlert(
            document.documentElement.classList.contains('dark') 
                ? 'Dark mode enabled' 
                : 'Light mode enabled',
            'success'
        );
    },
    
    showVideoComments: function(videoId, videoTitle) {
        if (window.CommentSystem) {
            window.CommentSystem.showComments(videoId, videoTitle);
        }
    },
    
    checkAuthStatus: function() {
        return window.Laravel?.isAuthenticated || false;
    },
    
    getCurrentUser: function() {
        return window.Laravel?.user || null;
    },
    
    init: function() {
        console.log('M-VIDEO App initialized');
        
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        
        this.initCommentSystem();
        this.importHomePageJS();
        this.setupAuthListeners();
        this.setupAnimations();
        this.setupKeyboardShortcuts();
        this.setupOnlineStatusTracker();
        this.startLastSeenTimer();

        // ✅ Livewire Morphing ဖြစ်တိုင်း Online Status အမှန်ကို Re-apply လုပ်ပေးမည်
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                this.reapplyOnlineStatuses();
            });
        });
    },

    // ✅ Real-time Presence Tracker
    setupOnlineStatusTracker: function() {
        if (window.Echo) {
            window.Echo.join('online')
                .here((users) => {
                    console.log('Currently online users:', users);
                    this.onlineUserIds.clear();
                    users.forEach(user => {
                        this.onlineUserIds.add(String(user.id));
                        this.updateUserStatusUI(user.id, true);
                    });
                })
                .joining((user) => {
                    console.log('User came online:', user.name);
                    const userId = String(user.id);
                    this.onlineUserIds.add(userId);

                    if (this.offlineTimers[userId]) {
                        clearTimeout(this.offlineTimers[userId]);
                        delete this.offlineTimers[userId];
                    }

                    this.updateUserStatusUI(user.id, true);
                })
                .leaving((user) => {
                    console.log('User leaving event triggered:', user.name);
                    const userId = String(user.id);
                    
                    if (this.offlineTimers[userId]) {
                        clearTimeout(this.offlineTimers[userId]);
                    }

                    this.offlineTimers[userId] = setTimeout(() => {
                        this.onlineUserIds.delete(userId);
                        if (!this.onlineUserIds.has(userId)) {
                            this.userLastSeenTimes[userId] = new Date();
                            this.updateUserStatusUI(user.id, false);
                        }
                        delete this.offlineTimers[userId];
                    }, 5000); // 5 Seconds Buffer
                })
                .error((error) => {
                    console.error('Presence Channel Error:', error);
                });
        }
    },

    // ✅ Time Ago Calculator Function
    formatTimeAgo: function(date) {
        if (!date) return 'Last seen recently';
        
        const seconds = Math.floor((new Date() - date) / 1000);
        
        if (seconds < 60) {
            return 'Last seen just now';
        }
        
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) {
            return `Last seen ${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
        }
        
        const hours = Math.floor(minutes / 60);
        if (hours < 24) {
            return `Last seen ${hours} ${hours === 1 ? 'hour' : 'hours'} ago`;
        }
        
        const days = Math.floor(hours / 24);
        return `Last seen ${days} ${days === 1 ? 'day' : 'days'} ago`;
    },

    // ✅ Single User Status Update Function
    updateUserStatusUI: function(userId, isOnline) {
        const strUserId = String(userId);

        // Green Status Badge
        const badges = document.querySelectorAll(`.user-status-badge-${strUserId}`);
        badges.forEach(badge => {
            if (isOnline) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });

        // Last Seen Text Element
        const lastSeenElements = document.querySelectorAll(`.user-last-seen-${strUserId}`);
        lastSeenElements.forEach(el => {
            if (isOnline) {
                el.classList.add('hidden');
            } else {
                el.classList.remove('hidden');
                
                let lastSeenDate = this.userLastSeenTimes[strUserId];
                if (!lastSeenDate) {
                    const dataTime = el.getAttribute('data-last-seen');
                    if (dataTime) {
                        lastSeenDate = new Date(dataTime);
                        this.userLastSeenTimes[strUserId] = lastSeenDate;
                    }
                }

                el.textContent = this.formatTimeAgo(lastSeenDate);
            }
        });
    },

    // ✅ Livewire DOM Refresh ဖြစ်သွားသည့်အခါ Status အမှန်များကို ပြန် Sync လုပ်ပေးမည့် Function
    reapplyOnlineStatuses: function() {
        const lastSeenElements = document.querySelectorAll('[class*="user-last-seen-"]');
        
        lastSeenElements.forEach(el => {
            const classList = Array.from(el.classList);
            const userClass = classList.find(c => c.startsWith('user-last-seen-'));
            
            if (userClass) {
                const userId = userClass.replace('user-last-seen-', '');
                const isOnline = this.onlineUserIds.has(String(userId));
                this.updateUserStatusUI(userId, isOnline);
            }
        });
    },

    // ✅ Timer Loop to update Last Seen Text automatically
    startLastSeenTimer: function() {
        setInterval(() => {
            const lastSeenElements = document.querySelectorAll('[class*="user-last-seen-"]');
            lastSeenElements.forEach(el => {
                if (!el.classList.contains('hidden')) {
                    const classList = Array.from(el.classList);
                    const userClass = classList.find(c => c.startsWith('user-last-seen-'));
                    
                    if (userClass) {
                        const userId = userClass.replace('user-last-seen-', '');
                        let lastSeenDate = this.userLastSeenTimes[userId];
                        
                        if (!lastSeenDate) {
                            const dataTime = el.getAttribute('data-last-seen');
                            if (dataTime) lastSeenDate = new Date(dataTime);
                        }

                        if (lastSeenDate) {
                            el.textContent = this.formatTimeAgo(lastSeenDate);
                        }
                    }
                }
            });
        }, 30000); // 30 seconds interval
    },
    
    initCommentSystem: function() {
        if (CommentSystem && typeof CommentSystem.init === 'function') {
            CommentSystem.init();
            console.log('Modern comment system with horizontal progress bar initialized');
        }
    },
    
    importHomePageJS: function() {
        const isHomePage = document.querySelector('.home-video-grid') !== null;
        
        if (isHomePage) {
            import('./home/home.js')
                .then(module => {
                    console.log('Home page module loaded successfully');
                })
                .catch(error => {
                    console.error('Error loading home page module:', error);
                });
        }
    },
    
    setupAuthListeners: function() {
        document.addEventListener('auth:changed', (e) => {
            console.log('Auth status changed:', e.detail);
            
            if (window.CommentSystem) {
                window.CommentSystem.handleAuthUpdate(
                    e.detail.authenticated, 
                    e.detail.user
                );
            }
            
            this.updateAuthUI(e.detail.authenticated);
        });
    },
    
    updateAuthUI: function(isAuthenticated) {
        const authElements = document.querySelectorAll('[data-auth-only], [data-guest-only]');
        
        authElements.forEach(element => {
            if (element.dataset.authOnly && !isAuthenticated) {
                element.style.display = 'none';
            } else if (element.dataset.authOnly && isAuthenticated) {
                element.style.display = '';
            }
            
            if (element.dataset.guestOnly && isAuthenticated) {
                element.style.display = 'none';
            } else if (element.dataset.guestOnly && !isAuthenticated) {
                element.style.display = '';
            }
        });
        
        const commentIcons = document.querySelectorAll('.comment-stat-item');
        commentIcons.forEach(icon => {
            if (isAuthenticated) {
                icon.classList.remove('read-only');
            } else {
                icon.classList.add('read-only');
            }
        });
    },
    
    setupAnimations: function() {
        if (!document.querySelector('#app-animations')) {
            const style = document.createElement('style');
            style.id = 'app-animations';
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                .video-card {
                    animation: fadeIn 0.5s ease forwards;
                    opacity: 0;
                    animation-delay: var(--delay, 0s);
                }
                @keyframes fadeIn {
                    to { opacity: 1; }
                }
                .app-alert {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    font-size: 14px;
                    font-weight: 500;
                }
            `;
            document.head.appendChild(style);
        }
    },
    
    setupKeyboardShortcuts: function() {
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                this.toggleDarkMode();
            }
            
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.video-comments-modal.active');
                if (modals.length > 0) {
                    modals.forEach(modal => {
                        const closeBtn = modal.querySelector('.modal-close-btn');
                        if (closeBtn) closeBtn.click();
                    });
                }
            }
            
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"], input[placeholder*="Search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    },
    
    playVideo: function(videoId) {
        console.log('Playing video:', videoId);
        this.showAlert('Video playback starting...', 'info');
    },
    
    likeVideo: function(videoId) {
        console.log('Liking video:', videoId);
        this.showAlert('Video liked!', 'success');
    },
    
    shareVideo: function(videoId) {
        console.log('Sharing video:', videoId);
        if (navigator.share) {
            navigator.share({
                title: 'Check out this video!',
                url: window.location.href
            });
        } else {
            this.showAlert('Link copied to clipboard!', 'success');
        }
    },
    
    initInfiniteScroll: function(containerId, loadCallback) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        let isLoading = false;
        
        container.addEventListener('scroll', () => {
            const scrollPosition = container.scrollTop + container.clientHeight;
            const scrollHeight = container.scrollHeight;
            const threshold = 100;
            
            if (scrollHeight - scrollPosition <= threshold && !isLoading) {
                isLoading = true;
                loadCallback().finally(() => {
                    isLoading = false;
                });
            }
        });
    }
};

window.CommentSystem = CommentSystem;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        window.MVideoApp.init();
    });
} else {
    window.MVideoApp.init();
}

export default window.MVideoApp;
