<!-- resources/views/components/header.blade.php -->
<header class="sticky-header">
    <div class="header-container">
        <div class="header-inner">
            {{-- Logo Section --}}
            <x-header.logo />

            {{-- Desktop Search Bar --}}
            <x-header.desktop-search />

            {{-- Right Section - User Menu --}}
            <x-header.user-menu />
        </div>
    </div>

    {{-- Mobile Search Overlay --}}
    <x-header.mobile-search />
</header>