// resources/js/header.js

class MVideoHeader {
    constructor() {
        this.init();
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.setupEventListeners();
    }

    cacheElements() {
        // Desktop Search
        this.searchInput = document.getElementById('searchInput');
        this.searchClear = document.getElementById('searchClear');
        this.searchForm = document.getElementById('searchForm');
        
        // Mobile Search
        this.searchToggleMobile = document.getElementById('searchToggleMobile');
        this.mobileSearchOverlay = document.getElementById('mobileSearchOverlay');
        this.closeMobileSearch = document.getElementById('closeMobileSearch');
        this.mobileSearchInput = document.getElementById('mobileSearchInput');
        this.clearMobileSearch = document.getElementById('clearMobileSearch');
        this.mobileSearchForm = document.getElementById('mobileSearchForm');
        
        // User Menu
        this.userMenuButton = document.getElementById('userMenuButton');
        this.userDropdown = document.getElementById('userDropdown');
        
        // Search Tags
        this.searchTags = document.querySelectorAll('.search-tag-btn');
        
        // State
        this.isUserMenuOpen = false;
        this.isMobileSearchOpen = false;
    }

    bindEvents() {
        this.handleSearchInput = this.handleSearchInput.bind(this);
        this.handleClearSearch = this.handleClearSearch.bind(this);
        this.handleMobileSearchInput = this.handleMobileSearchInput.bind(this);
        this.handleClearMobileSearch = this.handleClearMobileSearch.bind(this);
        this.toggleUserMenu = this.toggleUserMenu.bind(this);
        this.handleOutsideClick = this.handleOutsideClick.bind(this);
        this.openMobileSearch = this.openMobileSearch.bind(this);
        this.closeMobileSearchOverlay = this.closeMobileSearchOverlay.bind(this);
        this.handleFormSubmit = this.handleFormSubmit.bind(this);
        this.handleMobileFormSubmit = this.handleMobileFormSubmit.bind(this);
        this.handleSearchTagClick = this.handleSearchTagClick.bind(this);
    }

    setupEventListeners() {
        // Desktop Search
        if (this.searchInput && this.searchClear) {
            this.searchInput.addEventListener('input', this.handleSearchInput);
            this.searchClear.addEventListener('click', this.handleClearSearch);
        }

        // User Menu
        if (this.userMenuButton && this.userDropdown) {
            this.userMenuButton.addEventListener('click', this.toggleUserMenu);
            document.addEventListener('click', this.handleOutsideClick);
        }

        // Mobile Search
        if (this.searchToggleMobile && this.mobileSearchOverlay) {
            this.searchToggleMobile.addEventListener('click', this.openMobileSearch);
        }

        if (this.closeMobileSearch && this.mobileSearchOverlay) {
            this.closeMobileSearch.addEventListener('click', this.closeMobileSearchOverlay);
        }

        if (this.mobileSearchInput && this.clearMobileSearch) {
            this.mobileSearchInput.addEventListener('input', this.handleMobileSearchInput);
            this.clearMobileSearch.addEventListener('click', this.handleClearMobileSearch);
        }

        // Form Submission
        if (this.searchForm) {
            this.searchForm.addEventListener('submit', this.handleFormSubmit);
        }

        if (this.mobileSearchForm) {
            this.mobileSearchForm.addEventListener('submit', this.handleMobileFormSubmit);
        }

        // Search Tags
        if (this.searchTags.length > 0) {
            this.searchTags.forEach(tag => {
                tag.addEventListener('click', this.handleSearchTagClick);
            });
        }

        // Close mobile search on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobileSearchOpen) {
                this.closeMobileSearchOverlay();
            }
        });

        // Close mobile search on overlay click
        if (this.mobileSearchOverlay) {
            this.mobileSearchOverlay.addEventListener('click', (e) => {
                if (e.target === this.mobileSearchOverlay) {
                    this.closeMobileSearchOverlay();
                }
            });
        }
    }

    // ===== EVENT HANDLERS =====
    
    handleSearchInput(e) {
        if (this.searchClear) {
            this.searchClear.style.display = e.target.value.length > 0 ? 'block' : 'none';
        }
    }

    handleClearSearch() {
        if (this.searchInput) {
            this.searchInput.value = '';
            if (this.searchClear) this.searchClear.style.display = 'none';
            this.searchInput.focus();
        }
    }

    handleMobileSearchInput(e) {
        if (this.clearMobileSearch) {
            this.clearMobileSearch.style.display = e.target.value.length > 0 ? 'block' : 'none';
        }
    }

    handleClearMobileSearch() {
        if (this.mobileSearchInput) {
            this.mobileSearchInput.value = '';
            if (this.clearMobileSearch) this.clearMobileSearch.style.display = 'none';
            this.mobileSearchInput.focus();
        }
    }

    toggleUserMenu(e) {
        e.stopPropagation();
        
        if (!this.userDropdown) return;
        
        this.isUserMenuOpen = !this.isUserMenuOpen;
        
        if (this.isUserMenuOpen) {
            this.userDropdown.classList.add('show');
        } else {
            this.userDropdown.classList.remove('show');
        }
    }

    handleOutsideClick(e) {
        if (!this.userDropdown || !this.userMenuButton) return;
        
        if (!this.userDropdown.contains(e.target) && !this.userMenuButton.contains(e.target)) {
            this.isUserMenuOpen = false;
            this.userDropdown.classList.remove('show');
        }
    }

    openMobileSearch() {
        if (this.mobileSearchOverlay) {
            this.mobileSearchOverlay.classList.add('show');
            this.isMobileSearchOpen = true;
            document.body.style.overflow = 'hidden'; // Prevent scrolling
            
            // Focus on input after transition
            setTimeout(() => {
                if (this.mobileSearchInput) {
                    this.mobileSearchInput.focus();
                }
            }, 300);
        }
    }

    closeMobileSearchOverlay() {
        if (this.mobileSearchOverlay) {
            this.mobileSearchOverlay.classList.remove('show');
            this.isMobileSearchOpen = false;
            document.body.style.overflow = ''; // Restore scrolling
            
            // Clear search input
            if (this.mobileSearchInput) {
                this.mobileSearchInput.value = '';
                if (this.clearMobileSearch) {
                    this.clearMobileSearch.style.display = 'none';
                }
            }
        }
    }

    handleFormSubmit(e) {
        if (this.searchInput) {
            const query = this.searchInput.value.trim();
            if (!query) {
                e.preventDefault();
                this.searchInput.focus();
            }
        }
    }

    handleMobileFormSubmit(e) {
        if (this.mobileSearchInput) {
            const query = this.mobileSearchInput.value.trim();
            if (!query) {
                e.preventDefault();
                this.mobileSearchInput.focus();
            }
        }
    }

    handleSearchTagClick(e) {
        const searchText = e.currentTarget.dataset.search;
        if (searchText) {
            if (this.mobileSearchInput) {
                this.mobileSearchInput.value = searchText;
                if (this.clearMobileSearch) {
                    this.clearMobileSearch.style.display = 'block';
                }
                // Submit the form
                if (this.mobileSearchForm) {
                    this.mobileSearchForm.submit();
                }
            }
        }
    }

    // ===== PUBLIC METHODS =====
    
    openUserMenu() {
        this.isUserMenuOpen = true;
        if (this.userDropdown) {
            this.userDropdown.classList.add('show');
        }
    }

    closeUserMenu() {
        this.isUserMenuOpen = false;
        if (this.userDropdown) {
            this.userDropdown.classList.remove('show');
        }
    }

    focusSearch() {
        if (this.searchInput) {
            this.searchInput.focus();
        }
    }

    search(query) {
        if (this.searchInput) {
            this.searchInput.value = query;
            if (this.searchClear) {
                this.searchClear.style.display = 'block';
            }
            if (this.searchForm) {
                this.searchForm.submit();
            }
        }
    }

    destroy() {
        // Remove event listeners if needed
        // This method can be called when component is removed from DOM
        
        if (this.searchInput && this.searchClear) {
            this.searchInput.removeEventListener('input', this.handleSearchInput);
            this.searchClear.removeEventListener('click', this.handleClearSearch);
        }
        
        if (this.userMenuButton && this.userDropdown) {
            this.userMenuButton.removeEventListener('click', this.toggleUserMenu);
            document.removeEventListener('click', this.handleOutsideClick);
        }
        
        if (this.searchToggleMobile && this.mobileSearchOverlay) {
            this.searchToggleMobile.removeEventListener('click', this.openMobileSearch);
        }
        
        if (this.closeMobileSearch && this.mobileSearchOverlay) {
            this.closeMobileSearch.removeEventListener('click', this.closeMobileSearchOverlay);
        }
        
        if (this.mobileSearchInput && this.clearMobileSearch) {
            this.mobileSearchInput.removeEventListener('input', this.handleMobileSearchInput);
            this.clearMobileSearch.removeEventListener('click', this.handleClearMobileSearch);
        }
        
        if (this.searchForm) {
            this.searchForm.removeEventListener('submit', this.handleFormSubmit);
        }
        
        if (this.mobileSearchForm) {
            this.mobileSearchForm.removeEventListener('submit', this.handleMobileFormSubmit);
        }
        
        if (this.searchTags.length > 0) {
            this.searchTags.forEach(tag => {
                tag.removeEventListener('click', this.handleSearchTagClick);
            });
        }
        
        document.removeEventListener('keydown', this.handleEscapeKey);
    }
}

// Initialize header when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Check if header exists
    const header = document.querySelector('.sticky-header');
    if (header) {
        window.mVideoHeader = new MVideoHeader();
        console.log('M-VIDEO Header initialized');
    }
});

// Export as ES6 module
export default MVideoHeader;