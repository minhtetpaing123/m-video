<!-- resources/views/components/header/mobile-search.blade.php -->
<div id="mobileSearchOverlay" class="mobile-search-overlay">
    <div class="mobile-search-content">
        {{-- Search Header --}}
        <div class="mobile-search-header">
            <button id="closeMobileSearch" class="close-search-btn">
                <x-icons.close-icon class="close-search-icon" />
            </button>
            <span class="mobile-search-title">Search</span>
            <div class="mobile-search-spacer"></div>
        </div>

        {{-- Search Input --}}
        <form id="mobileSearchForm" class="mobile-search-form" action="/search" method="GET">
            <div class="mobile-input-container">
                <input type="text" 
                       name="q"
                       id="mobileSearchInput"
                       class="mobile-search-input"
                       placeholder="Search videos, channels, creators..."
                       autocomplete="off"
                       autofocus>
                
                <div class="mobile-search-icon-container">
                    <x-icons.search-icon class="mobile-search-icon-svg" />
                </div>
                
                <button type="button" 
                        id="clearMobileSearch"
                        class="mobile-clear-btn">
                    <x-icons.close-icon class="mobile-clear-icon" />
                </button>
            </div>
        </form>

        {{-- Mobile Suggestions --}}
        <div class="mobile-suggestions">
            <div class="trending-section">
                <h3 class="trending-title">Trending Now</h3>
                <div class="trending-tags-container">
                    <button type="button" 
                            class="search-tag-btn"
                            data-search="Gaming">Gaming</button>
                    <button type="button" 
                            class="search-tag-btn"
                            data-search="Mobile Legends">Mobile Legends</button>
                    <button type="button" 
                            class="search-tag-btn"
                            data-search="Shorts">Shorts</button>
                    <button type="button" 
                            class="search-tag-btn"
                            data-search="Tutorials">Tutorials</button>
                    <button type="button" 
                            class="search-tag-btn"
                            data-search="Live">Live</button>
                </div>
            </div>
        </div>
    </div>
</div>