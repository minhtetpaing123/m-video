<div>
    <div class="w-full px-2 sm:px-4 md:px-6 lg:px-8 py-3 sm:py-4 md:py-6">
        
        {{-- Video Grid --}}
        <livewire:layout.video-grid 
            :posts="$posts" 
            empty-message="{{ __('No posts found') }}"
            card-type="guest"
            wire:key="video-grid-{{ $totalPosts }}"
        />

        {{-- Infinite Scroll --}}
        <livewire:components.infinite-scroll 
            :hasMorePages="$hasMorePages"
            :page="$currentPage"
            loading-text="{{ __('Loading more videos...') }}"
            end-text="{{ __('🎉 You\'ve reached the end!') }}"
            wire:key="infinite-scroll-{{ $currentPage }}-{{ $hasMorePages }}"
        />
    </div>

    {{-- Scroll to top button --}}
    <button 
        x-data="{ 
            show: false,
            init() {
                window.addEventListener('scroll', () => {
                    this.show = window.scrollY > 500;
                });
            }
        }"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-40 right-4 w-14 h-14 rounded-full bg-blue-500 text-white shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 flex items-center justify-center hover:scale-110 active:scale-95 z-50"
        x-show="show"
        x-transition
        x-cloak
    >
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    {{-- Floating Category Filters --}}
    <livewire:category.floating-category-filters />

    {{-- Guest Bottom Navigation --}}
    <livewire:layout.guest-nav active="home" />
</div>