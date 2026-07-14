<!-- resources/views/layouts/category.blade.php -->
@php
    $categories = App\Models\Post::getCategories();
    $isHomePage = request()->routeIs('home') || request()->routeIs('category.filter') || request()->routeIs('category.18plus');
@endphp

@if($isHomePage)
    <div class="sticky top-16 z-40 bg-gradient-to-b from-[#0a0a0a] to-[#111111] border-b border-white/5 backdrop-blur-md shadow-lg">
        <div class="max-w-7xl mx-auto px-3 sm:px-4">
            <div class="overflow-x-auto overflow-y-hidden scrollbar-hide py-2 sm:py-3">
                <div class="flex items-center gap-2 sm:gap-3 min-w-max">
                    
                    {{-- ALL BUTTON --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 border relative overflow-hidden
                              {{ (!request()->has('category') && !request()->routeIs('category.18plus')) 
                                  ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white border-blue-500 shadow-lg shadow-blue-500/30 scale-105' 
                                  : 'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10 hover:text-white hover:border-white/20 hover:scale-105' }}">
                        <span class="text-sm sm:text-base">🔥</span>
                        <span>All</span>
                        <span class="inline-flex items-center justify-center bg-white/20 text-white text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full">{{ count($categories) }}</span>
                    </a>
                    
                    {{-- CATEGORY BUTTONS --}}
                    @foreach($categories as $slug => $label)
                        <a href="{{ route('category.filter', $slug) }}"
                           class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 border whitespace-nowrap
                                  {{ request('category') == $slug 
                                      ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white border-blue-500 shadow-lg shadow-blue-500/30 scale-105' 
                                      : 'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10 hover:text-white hover:border-white/20 hover:scale-105' }}">
                            <span class="text-sm sm:text-base">{{ substr($label, 0, 2) }}</span>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach

                    {{-- ============================================ --}}
                    {{-- 18+ BUTTON - သီးသန့် ထင်ရှားအောင် --}}
                    {{-- ============================================ --}}
                    <a href="{{ route('category.18plus') }}"
                       class="flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 border relative overflow-hidden
                              {{ request()->routeIs('category.18plus') 
                                  ? 'bg-gradient-to-r from-red-600 to-red-700 text-white border-red-500 shadow-lg shadow-red-500/40 scale-105' 
                                  : 'bg-red-500/10 text-red-400 border-red-500/30 hover:bg-red-500/20 hover:text-red-300 hover:border-red-500/50 hover:scale-105' }}">
                        <span class="text-sm sm:text-base">🔞</span>
                        <span class="font-bold">18+</span>
                    </a>
                    
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Hide scrollbar */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Smooth animation on load */
    .category-item {
        animation: fadeInCategory 0.4s ease forwards;
        opacity: 0;
        transform: translateY(8px);
    }

    @keyframes fadeInCategory {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .category-item:nth-child(1) { animation-delay: 0.00s; }
    .category-item:nth-child(2) { animation-delay: 0.03s; }
    .category-item:nth-child(3) { animation-delay: 0.06s; }
    .category-item:nth-child(4) { animation-delay: 0.09s; }
    .category-item:nth-child(5) { animation-delay: 0.12s; }
    .category-item:nth-child(6) { animation-delay: 0.15s; }
    .category-item:nth-child(7) { animation-delay: 0.18s; }
    .category-item:nth-child(8) { animation-delay: 0.21s; }
    .category-item:nth-child(9) { animation-delay: 0.24s; }
    .category-item:nth-child(10) { animation-delay: 0.27s; }
    .category-item:nth-child(11) { animation-delay: 0.30s; }
    .category-item:nth-child(12) { animation-delay: 0.33s; }
    .category-item:nth-child(13) { animation-delay: 0.36s; }
    .category-item:nth-child(14) { animation-delay: 0.39s; }
    .category-item:nth-child(15) { animation-delay: 0.42s; }
    .category-item:nth-child(16) { animation-delay: 0.45s; }
    .category-item:nth-child(17) { animation-delay: 0.48s; }
    .category-item:nth-child(18) { animation-delay: 0.51s; }
    .category-item:nth-child(19) { animation-delay: 0.54s; }
    .category-item:nth-child(20) { animation-delay: 0.57s; }
    .category-item:nth-child(21) { animation-delay: 0.60s; }
    .category-item:nth-child(22) { animation-delay: 0.63s; }
    .category-item:nth-child(23) { animation-delay: 0.66s; }
    .category-item:nth-child(24) { animation-delay: 0.69s; }
    .category-item:nth-child(25) { animation-delay: 0.72s; }
    </style>
@endif