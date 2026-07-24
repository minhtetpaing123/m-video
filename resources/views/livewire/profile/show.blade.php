<div>
    <div class="gradient-bg min-h-screen text-white">
        
        {{-- Navbar --}}
        <nav class="nav-blur fixed top-0 left-0 right-0 z-50">
            <!-- ... navbar ... -->
        </nav>

        {{-- Main Content --}}
        <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

            {{-- ============================================ --}}
            {{-- REAL-TIME PROFILE HEADER (Livewire Component) --}}
            {{-- ============================================ --}}
            <livewire:profile.header :user="$user" :videoCount="$videoCount" />

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- ... stats ... -->
            </div>

            {{-- Videos Section --}}
            <div class="glass-card rounded-3xl p-6 md:p-8">
                <!-- ... videos ... -->
            </div>
        </div>
    </div>
</div>