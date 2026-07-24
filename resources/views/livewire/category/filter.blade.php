<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <span class="text-3xl">📂</span>
                <h1 class="text-2xl font-bold text-white">{{ $categoryLabel }}</h1>
            </div>
            <span class="text-sm text-gray-400">({{ $posts->total() }} videos)</span>
        </div>

        {{-- Video Grid with View Mode Support --}}
        <livewire:layout.video-grid 
            :posts="$posts->items()" 
            empty-message="No videos found in this category"
            card-type="guest"
            :view-mode="$viewMode"
            wire:key="category-grid-{{ $category }}-{{ $viewMode }}"
        />

        {{-- ✅ Infinite Scroll --}}
        @if($posts->hasMorePages())
            <livewire:components.infinite-scroll 
                :hasMorePages="true"
                :page="$posts->currentPage()"
                loading-text="{{ __('Loading more videos...') }}"
                end-text="{{ __('🎉 You\'ve reached the end!') }}"
                wire:key="infinite-scroll-{{ $category }}-{{ $posts->currentPage() }}"
            />
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 text-sm">{{ __('🎉 You\'ve reached the end!') }}</p>
            </div>
        @endif
    </div>
</div>