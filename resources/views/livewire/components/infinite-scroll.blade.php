<div>
    {{-- Sentinel - Auto load trigger --}}
    @if($hasMorePages)
        <div id="sentinel" 
             x-data="{
                 observer: null,
                 isLoading: false,
                 page: {{ $page }},
                 hasMore: {{ $hasMorePages ? 'true' : 'false' }},
                 init() {
                     console.log('Sentinel init, page: ' + this.page + ', hasMore: ' + this.hasMore);
                     
                     // Setup observer after DOM ready
                     this.$nextTick(() => {
                         setTimeout(() => {
                             this.setupObserver();
                         }, 500);
                     });
                 },
                 setupObserver() {
                     if (this.observer) {
                         this.observer.disconnect();
                         console.log('Observer disconnected');
                     }
                     
                     this.observer = new IntersectionObserver((entries) => {
                         entries.forEach(entry => {
                             if (entry.isIntersecting && !this.isLoading && this.hasMore) {
                                 console.log('Sentinel triggered, loading page: ' + (this.page + 1));
                                 this.isLoading = true;
                                 
                                 // Call Livewire loadMore
                                 $wire.loadMore();
                                 
                                 // Reset loading state after 3 seconds
                                 setTimeout(() => {
                                     this.isLoading = false;
                                     console.log('Loading state reset');
                                 }, 3000);
                             }
                         });
                     }, {
                         rootMargin: '0px 0px 100px 0px',
                         threshold: 0.1
                     });
                     
                     this.observer.observe(this.$el);
                     console.log('Observer started for page: ' + this.page);
                 },
                 resetObserver() {
                     console.log('Resetting observer');
                     this.hasMore = {{ $hasMorePages ? 'true' : 'false' }};
                     this.page = {{ $page }};
                     
                     if (this.observer) {
                         this.observer.disconnect();
                     }
                     
                     setTimeout(() => {
                         this.setupObserver();
                     }, 100);
                 },
                 destroy() {
                     if (this.observer) {
                         this.observer.disconnect();
                         console.log('Observer destroyed');
                     }
                 }
             }"
             @load-more-complete.window="resetObserver()"
             class="h-10">
        </div>
    @endif

    {{-- Loading Indicator --}}
    <div wire:loading wire:target="loadMore" class="flex justify-center py-6">
        <div class="flex items-center gap-3">
            <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-400 text-sm">{{ $loadingText }}</span>
        </div>
    </div>

    {{-- End of Content --}}
    @if(!$hasMorePages)
        <div class="text-center py-8">
            <p class="text-gray-500 text-sm">{{ $endText }}</p>
        </div>
    @endif
</div>