<!-- resources/views/components/header/user-menu.blade.php -->
<div class="right-section-container">
    {{-- Mobile Search Toggle --}}
    <button id="searchToggleMobile" class="mobile-search-toggle">
        <x-icons.search-icon class="mobile-search-icon" />
    </button>

    {{-- User Menu --}}
    <div class="user-menu-wrapper">
        <button id="userMenuButton" class="user-menu-btn">
            <x-icons.user-icon class="user-menu-svg" />
        </button>

        {{-- Dropdown Menu --}}
        <div id="userDropdown" class="user-dropdown-menu">
            <div class="guest-menu-content">
                <p class="welcome-message">Welcome to M-VIDEO</p>
                <div class="auth-buttons-container">
                    <a href="/login" class="sign-in-button">Sign In</a>
                    <a href="/register" class="create-account-button">Create Account</a>
                </div>
            </div>
        </div>
    </div>
</div>