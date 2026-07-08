// resources/js/user/user-header.js

export function initUserHeader() {
    // Search icon click - show search bar
    const searchIcon = document.querySelector('.mv-search-icon-btn');
    const searchBarContainer = document.querySelector('.mv-search-bar-container');
    const searchBack = document.querySelector('.mv-search-back');
    const topBar = document.querySelector('.mv-top-bar');
    
    if (searchIcon && searchBarContainer) {
        searchIcon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            topBar.style.display = 'none';
            searchBarContainer.style.display = 'block';
            
            setTimeout(() => {
                const input = document.querySelector('.mv-search-input');
                if (input) {
                    input.focus();
                }
            }, 100);
        });
        
        if (searchBack) {
            searchBack.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                topBar.style.display = 'flex';
                searchBarContainer.style.display = 'none';
            });
        }
    }

    // Add icon click
    const addIcon = document.querySelector('.mv-add-icon-btn');
    if (addIcon) {
        addIcon.addEventListener('click', function() {
            console.log('Add/Create clicked');
        });
    }
}

// Auto-initialize if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUserHeader);
} else {
    initUserHeader();
}