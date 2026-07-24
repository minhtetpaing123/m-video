<div>
    @if($isHomePage)
        {{-- Fixed Header Bar with Scroll Hide/Show Logic --}}
        <div x-data="{ 
                 showCategory: true, 
                 lastScrollY: 0,
                 init() {
                     window.addEventListener('scroll', () => {
                         let currentScrollY = window.scrollY;
                         
                         // အောက်သို့ ဆွဲရွှေ့လျှင် ဖျောက်မည်
                         if (currentScrollY > this.lastScrollY && currentScrollY > 50) {
                             this.showCategory = false;
                         } 
                         // အပေါ်သို့ ပြန်ဆွဲလျှင် ချက်ချင်း ပြန်ပေါ်မည်
                         else if (currentScrollY < this.lastScrollY) {
                             this.showCategory = true;
                         }
                         
                         this.lastScrollY = currentScrollY;
                     }, { passive: true });
                 }
             }"
             class="fixed left-0 right-0 top-14 sm:top-16 z-30 transition-all duration-300 ease-in-out"
             :class="showCategory ? 'translate-y-0 opacity-100' : '-translate-y-20 opacity-0 pointer-events-none'"
             style="background: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
            
            <div class="max-w-7xl mx-auto px-3 sm:px-4 py-2.5">
                <div x-data 
                     x-init="$nextTick(() => {
                         const activeEl = $el.querySelector('.active-category');
                         if (activeEl) {
                             activeEl.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                         }
                     })"
                     class="overflow-x-auto overflow-y-hidden scrollbar-none flex items-center gap-2 sm:gap-2.5 py-1">
                    
                    {{-- ALL BUTTON --}}
                    <a href="{{ route('home') }}"
                       wire:navigate
                       class="flex items-center gap-2 px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-300 whitespace-nowrap shrink-0 group relative overflow-hidden active:scale-95 {{ $this->isAllActive() ? 'active-category' : '' }}"
                       style="{{ $this->isAllActive() 
                           ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4); border: 1.5px solid #60a5fa;' 
                           : 'background: var(--bg-card); color: var(--text-primary); border: 1.5px solid rgba(236, 72, 153, 0.4);' }}">
                        <span class="text-base leading-none">🔥</span>
                        <span>{{ __('All') }}</span>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full transition-colors" 
                              style="{{ $this->isAllActive() 
                                  ? 'background: rgba(255, 255, 255, 0.25); color: #ffffff;' 
                                  : 'background: var(--bg-input); color: var(--text-muted);' }}">
                            {{ count($categories) }}
                        </span>
                    </a>
                    
                    {{-- CATEGORY BUTTONS --}}
                    @foreach($categories as $slug => $label)
                        @php
                            $hasEmoji = preg_match('/[\x{1F300}-\x{1F9FF}]/u', $label, $matches);
                            $emoji = $hasEmoji ? $matches[0] : '🎬';
                            $cleanLabel = trim(preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $label));
                        @endphp

                        <a href="{{ route('category.filter', $slug) }}"
                           wire:navigate
                           class="flex items-center gap-2 px-5 py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 whitespace-nowrap shrink-0 hover:scale-[1.03] active:scale-95 {{ $this->isActive($slug) ? 'active-category' : '' }}"
                           style="{{ $this->isActive($slug) 
                               ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4); border: 1.5px solid #60a5fa; font-weight: 600;' 
                               : 'background: var(--bg-card); color: var(--text-primary); border: 1.5px solid rgba(236, 72, 153, 0.4);' }}">
                            <span class="text-base leading-none">{{ $emoji }}</span>
                            <span>{{ __($cleanLabel) }}</span>
                        </a>
                    @endforeach

                    {{-- 18+ BUTTON --}}
                    <a href="{{ route('category.18plus') }}"
                       wire:navigate
                       class="flex items-center gap-1.5 px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-300 whitespace-nowrap shrink-0 hover:scale-[1.03] active:scale-95 {{ $this->is18PlusActive() ? 'active-category' : '' }}"
                       style="{{ $this->is18PlusActive() 
                           ? 'background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #ffffff; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); border: 1.5px solid #f87171;' 
                           : 'background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1.5px solid rgba(239, 68, 68, 0.4);' }}">
                        <span class="text-base leading-none">🔞</span>
                        <span>{{ __('18+') }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Spacing div to prevent content overlap --}}
        <div class="h-14 sm:h-16"></div>

        <style>
            .scrollbar-none::-webkit-scrollbar { display: none; }
            .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    @endif
</div>