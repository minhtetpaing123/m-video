/* resources/js/home.js */
/* M-VIDEO HOME PAGE JAVASCRIPT */

console.log('=== M-VIDEO HOME PAGE LOADED ===');

// Global variables
let activeDropdown = null;
let isMobile = window.innerWidth <= 768;

// Toast function - DISABLED
function showToast(message, duration = 2000) {
    console.log('Toast:', message);
    return;
}

// Menu button click handler
function initMenuButtons() {
    document.querySelectorAll('.menu-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Menu button clicked');
            
            const wrapper = this.closest('.menu-button-wrapper');
            const dropdown = wrapper.querySelector('.menu-dropdown');
            const isActive = dropdown.classList.contains('active');
            
            // Close all other dropdowns
            document.querySelectorAll('.menu-dropdown.active').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('active');
                    d.scrollTop = 0;
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('active');
            
            // If opening dropdown
            if (!isActive) {
                // AUTO SCROLL LOGIC FOR MOBILE
                if (isMobile) {
                    setTimeout(() => {
                        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                        const dropdownTop = dropdown.getBoundingClientRect().top;
                        
                        // If dropdown is near top, scroll down
                        if (dropdownTop < 50) {
                            const scrollToPosition = currentScroll + 100;
                            window.scrollTo({
                                top: scrollToPosition,
                                behavior: 'smooth'
                            });
                        }
                        
                        dropdown.scrollTop = 0;
                    }, 100);
                }
                
                // Close when clicking outside
                setTimeout(() => {
                    const closeHandler = function(e) {
                        if (!e.target.closest('.menu-dropdown') && !e.target.closest('.menu-button')) {
                            dropdown.classList.remove('active');
                            document.removeEventListener('click', closeHandler);
                        }
                    };
                    document.addEventListener('click', closeHandler);
                }, 10);
            } else {
                dropdown.scrollTop = 0;
            }
        });
    });
}

// Menu item click handler
function initMenuItems() {
    document.addEventListener('click', function(e) {
        // Menu items
        const menuItem = e.target.closest('.menu-item');
        if (menuItem) {
            e.preventDefault();
            e.stopPropagation();
            
            const action = menuItem.getAttribute('data-action');
            const text = menuItem.querySelector('.menu-item-text').textContent;
            const icon = menuItem.querySelector('.menu-item-icon').textContent;
            
            console.log('Menu item clicked:', action, '-', text, icon);
            console.log(`Action: ${text} (${action}) - ${icon}`);
            
            // Perform actual action based on type
            switch(action) {
                case 'share':
                    if (navigator.share) {
                        const videoTitle = menuItem.closest('.menu-dropdown')
                            .querySelector('.video-preview-title').textContent;
                        navigator.share({
                            title: videoTitle,
                            text: 'Check out this video on M-VIDEO',
                            url: window.location.href
                        }).catch(console.error);
                    } else {
                        alert(`Share: ${text}`);
                    }
                    break;
                    
                case 'save':
                    alert(`Video saved to your library!`);
                    break;
                    
                case 'watch-later':
                    alert(`Added to Watch Later!`);
                    break;
                    
                case 'download':
                    alert(`Downloading video...`);
                    break;
                    
                case 'not-interested':
                    const videoCard = menuItem.closest('.video-card');
                    videoCard.style.opacity = '0.5';
                    alert(`We'll show fewer videos like this`);
                    break;
                    
                case 'report':
                    alert(`Report sent to moderators`);
                    break;
            }
            
            // Close ALL dropdowns
            document.querySelectorAll('.menu-dropdown.active').forEach(dropdown => {
                dropdown.classList.remove('active');
                dropdown.scrollTop = 0;
            });
        }
        
        // Close mobile menu button
        if (e.target.closest('.close-mobile-menu')) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = e.target.closest('.menu-dropdown');
            dropdown.classList.remove('active');
            dropdown.scrollTop = 0;
        }
    });
}

// ESC key to close dropdowns
function initEscapeKey() {
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.menu-dropdown.active').forEach(dropdown => {
                dropdown.classList.remove('active');
                dropdown.scrollTop = 0;
            });
        }
    });
}

// Play button click handler
function initPlayButtons() {
    document.querySelectorAll('.play-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const title = this.closest('.video-card').querySelector('.video-title').textContent;
            console.log('Play button clicked:', title);
        });
    });
}

// Video card click handler
function initVideoCards() {
    document.querySelectorAll('.video-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.menu-button') || e.target.closest('.play-button')) {
                return;
            }
            
            const title = this.querySelector('.video-title').textContent;
            console.log('Video card clicked:', title);
        });
    });
}

// Creator name click handler
function initCreatorNames() {
    document.querySelectorAll('.creator-name').forEach(name => {
        name.addEventListener('click', function(e) {
            e.stopPropagation();
            const creator = this.textContent;
            console.log('Creator clicked:', creator);
        });
    });
}

// Stats interaction handler
function initVideoStats() {
    // Format number function
    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    }
    
    // Like button functionality
    document.querySelectorAll('.stat-item[data-stat="likes"]').forEach(likeBtn => {
        likeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const countEl = this.querySelector('.stat-count');
            let count = parseInt(countEl.textContent.replace('K', '000').replace('M', '000000'));
            
            if (this.classList.contains('liked')) {
                // Unlike
                count--;
                this.classList.remove('liked');
                countEl.textContent = formatNumber(count);
            } else {
                // Like
                count++;
                this.classList.add('liked');
                countEl.textContent = formatNumber(count);
            }
        });
    });
}

// Initialize everything
function initHomePage() {
    console.log('Initializing M-VIDEO home page...');
    
    // Check if we're on the home page
    const videoGrid = document.querySelector('.home-video-grid');
    if (!videoGrid) {
        console.log('Not on home page, skipping initialization');
        return;
    }
    
    // Initialize all systems
    initMenuButtons();
    initMenuItems();
    initEscapeKey();
    initPlayButtons();
    initVideoCards();
    initCreatorNames();
    initVideoStats();
    
    // Handle window resize
    window.addEventListener('resize', function() {
        isMobile = window.innerWidth <= 768;
    });
    
    // Public API for debugging/testing
    window.MVideoHome = {
        testMenuSystem: function() {
            console.log('Testing menu system...');
            const firstMenuBtn = document.querySelector('.menu-button');
            if (firstMenuBtn) {
                firstMenuBtn.click();
                console.log('Menu opened successfully!');
            }
        },
        
        testLikeSystem: function() {
            console.log('Testing like system...');
            const firstLikeBtn = document.querySelector('.stat-item[data-stat="likes"]');
            if (firstLikeBtn) {
                firstLikeBtn.click();
                console.log('Like toggled!');
            }
        }
    };
    
    console.log('✅ M-VIDEO home page fully initialized!');
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomePage);
} else {
    initHomePage();
}