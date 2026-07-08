// resources/js/app.js
// resources/js/app.js

// Import bootstrap
import './bootstrap';

// Import user header module
import { initUserHeader } from './user/user-header';

// Initialize user header
document.addEventListener('DOMContentLoaded', () => {
    initUserHeader();
});

// Your other global JavaScript below
// Import Header JavaScript
import './components/header';

// Import Comment System
import CommentSystem from './home/comment';

// Global MVideoApp object
window.MVideoApp = {
    version: '1.0.0',
    
    // Utility functions
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
        
        // Dispatch event for other components
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
    
    // Show video comments
    showVideoComments: function(videoId, videoTitle) {
        if (window.CommentSystem) {
            window.CommentSystem.showComments(videoId, videoTitle);
        }
    },
    
    // Check auth status
    checkAuthStatus: function() {
        return window.Laravel?.isAuthenticated || false;
    },
    
    // Get current user
    getCurrentUser: function() {
        return window.Laravel?.user || null;
    },
    
    // Initialize app
    init: function() {
        console.log('M-VIDEO App initialized');
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        
        // Initialize Comment System
        this.initCommentSystem();
        
        // Import home page JS if needed
        this.importHomePageJS();
        
        // Setup auth event listeners
        this.setupAuthListeners();
        
        // Setup animations
        this.setupAnimations();
        
        // Setup keyboard shortcuts
        this.setupKeyboardShortcuts();
    },
    
    // Initialize comment system
    initCommentSystem: function() {
        if (CommentSystem && typeof CommentSystem.init === 'function') {
            CommentSystem.init();
            console.log('Modern comment system with horizontal progress bar initialized');
        }
    },
    
    // Import home page JavaScript
    importHomePageJS: function() {
        // Check if we're on the home page
        const isHomePage = document.querySelector('.home-video-grid') !== null;
        
        if (isHomePage) {
            // Dynamically import home.js
            import('./home/home.js')
                .then(module => {
                    console.log('Home page module loaded successfully');
                })
                .catch(error => {
                    console.error('Error loading home page module:', error);
                });
        }
    },
    
    // Setup auth event listeners
    setupAuthListeners: function() {
        // Listen for auth changes
        document.addEventListener('auth:changed', (e) => {
            console.log('Auth status changed:', e.detail);
            
            // Update comment system if available
            if (window.CommentSystem) {
                window.CommentSystem.handleAuthUpdate(
                    e.detail.authenticated, 
                    e.detail.user
                );
            }
            
            // Update UI elements
            this.updateAuthUI(e.detail.authenticated);
        });
    },
    
    // Update UI based on auth status
    updateAuthUI: function(isAuthenticated) {
        // Update auth buttons visibility
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
        
        // Update comment icons
        const commentIcons = document.querySelectorAll('.comment-stat-item');
        commentIcons.forEach(icon => {
            if (isAuthenticated) {
                icon.classList.remove('read-only');
            } else {
                icon.classList.add('read-only');
            }
        });
    },
    
    // Setup animations
    setupAnimations: function() {
        // Add CSS animations if not already present
        if (!document.querySelector('#app-animations')) {
            const style = document.createElement('style');
            style.id = 'app-animations';
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                
                .video-card {
                    animation: fadeIn 0.5s ease forwards;
                    opacity: 0;
                    animation-delay: var(--delay, 0s);
                }
                
                @keyframes fadeIn {
                    to {
                        opacity: 1;
                    }
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
    
    // Setup keyboard shortcuts
    setupKeyboardShortcuts: function() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + / to toggle dark mode
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                this.toggleDarkMode();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.video-comments-modal.active');
                if (modals.length > 0) {
                    modals.forEach(modal => {
                        const closeBtn = modal.querySelector('.modal-close-btn');
                        if (closeBtn) closeBtn.click();
                    });
                }
            }
            
            // Ctrl/Cmd + K to focus search (if exists)
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"], input[placeholder*="Search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    },
    
    // Play video (placeholder)
    playVideo: function(videoId) {
        console.log('Playing video:', videoId);
        this.showAlert('Video playback starting...', 'info');
    },
    
    // Like video (placeholder)
    likeVideo: function(videoId) {
        console.log('Liking video:', videoId);
        this.showAlert('Video liked!', 'success');
    },
    
    // Share video (placeholder)
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
    
    // Initialize infinite scroll for any scrollable container
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

// Make CommentSystem globally available
window.CommentSystem = CommentSystem;

// Initialize app when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        window.MVideoApp.init();
    });
} else {
    window.MVideoApp.init();
}

// Export for ES6 modules
export default window.MVideoApp;