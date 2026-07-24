@if($isHomePage)
    <div class="sticky top-16 z-40" style="background: var(--bg-secondary); backdrop-filter: blur(12px); box-shadow: 0 2px 20px rgba(0,0,0,0.08);">
        <div class="max-w-7xl mx-auto px-3 sm:px-4">
            <div class="overflow-x-auto overflow-y-hidden scrollbar-hide py-2 sm:py-3">
                <div class="flex items-center gap-2 sm:gap-3 min-w-max">
                    
                    {{-- ALL BUTTON --}}
                    <a href="{{ route('home') }}"
                       wire:navigate
                       class="flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 border relative overflow-hidden category-link-hover"
                       style="{{ $this->isAllActive() 
                           ? 'background: linear-gradient(135deg, #2d88ff, #1a73e8); color: #fff; border-color: #2d88ff; box-shadow: 0 4px 15px rgba(45,136,255,0.3); transform: scale(1.05);' 
                           : 'background: var(--bg-card); color: var(--text-secondary); border-color: var(--border-color);' }}">
                        <span class="text-sm sm:text-base">🔥</span>
                        <span>All</span>
                        <span class="inline-flex items-center justify-center text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full" 
                             style="{{ $this->isAllActive() 
                                 ? 'background: rgba(255,255,255,0.2); color: #fff;' 
                                 : 'background: var(--bg-card-hover); color: var(--text-muted);' }}">
                            {{ count($categories) }}
                        </span>
                    </a>
                    
                    {{-- CATEGORY BUTTONS --}}
                    @foreach($categories as $slug => $label)
                        <a href="{{ route('category.filter', $slug) }}"
                           wire:navigate
                           class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 border whitespace-nowrap category-link-hover"
                           style="{{ $this->isActive($slug) 
                               ? 'background: linear-gradient(135deg, #2d88ff, #1a73e8); color: #fff; border-color: #2d88ff; box-shadow: 0 4px 15px rgba(45,136,255,0.3); transform: scale(1.05);' 
                               : 'background: var(--bg-card); color: var(--text-secondary); border-color: var(--border-color);' }}">
                            <span class="text-sm sm:text-base">{{ substr($label, 0, 2) }}</span>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach

                    {{-- 18+ BUTTON --}}
                    <a href="{{ route('category.18plus') }}"
                       wire:navigate
                       class="flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 border relative overflow-hidden category-link-hover"
                       style="{{ $is18Plus 
                           ? 'background: linear-gradient(135deg, #e74c3c, #c0392b); color: #fff; border-color: #e74c3c; box-shadow: 0 4px 15px rgba(231,76,60,0.4); transform: scale(1.05);' 
                           : 'background: rgba(231,76,60,0.1); color: #e74c3c; border-color: rgba(231,76,60,0.3);' }}">
                        <span class="text-sm sm:text-base">🔞</span>
                        <span class="font-bold">18+</span>
                    </a>
                    
                </div>
            </div>
        </div>
    </div>

    <style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-link-hover {
        transition: all 0.3s ease;
    }
    
    .category-link-hover:hover {
        transform: scale(1.05);
    }
    </style>
@endif