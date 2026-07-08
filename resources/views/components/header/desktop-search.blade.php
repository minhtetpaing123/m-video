<!-- resources/views/components/header/desktop-search.blade.php -->
<div class="desktop-search">
    <div class="search-container">
        <form id="searchForm" class="search-form" action="/search" method="GET">
            <div class="search-input-container">
                <input type="text" 
                       name="q"
                       id="searchInput"
                       class="search-input"
                       placeholder="Search videos, channels, creators..."
                       autocomplete="off"
                       aria-label="Search videos">
                
                {{-- Search Icon --}}
                <div class="search-icon-container">
                    <x-icons.search-icon class="search-icon-svg" />
                </div>
                
                {{-- Clear Button --}}
                <button type="button" 
                        id="searchClear"
                        class="clear-btn">
                    <x-icons.close-icon class="clear-icon" />
                </button>
            </div>
        </form>
    </div>
</div>