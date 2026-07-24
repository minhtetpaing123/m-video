<div class="fixed bottom-0 left-0 right-0 z-50 bg-[#1a1a2e] dark:bg-[#1a1a2e] light:bg-white border-t border-[#2a2a3e] dark:border-[#2a2a3e] light:border-[#e4e6eb] flex justify-around items-center h-[60px] px-2 shadow-[0_-4px_20px_rgba(0,0,0,0.15)]">
    
    {{-- 1. Home --}}
    <a href="{{ route('home') }}" 
       wire:navigate 
       class="flex flex-col items-center justify-center gap-0.5 px-2 min-w-[56px] relative text-gray-400 hover:text-white transition-colors duration-200 group {{ $active === 'home' ? 'text-blue-500' : '' }}">
        <div class="w-6 h-6 flex items-center justify-center transition-transform duration-200 group-hover:scale-110">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 28 28">
                <path d="M25.825 12.29c-.018-.019-.185-7.394-.185-7.394A1.815 1.815 0 0 0 23.824 3.5H16.5a.5.5 0 0 0-.5.5v4.75a.25.25 0 0 1-.25.25h-3.5a.25.25 0 0 1-.25-.25V4a.5.5 0 0 0-.5-.5H4.176a1.815 1.815 0 0 0-1.816 1.816c0 .122-.142 7.35-.16 7.37a1.867 1.867 0 0 0 1.45 3.21h.386c1.005.02 1.809.847 1.809 1.867v8.02c0 1.03.84 1.87 1.87 1.87h3.933a1.87 1.87 0 0 0 1.87-1.87v-5.1c0-.386.314-.7.7-.7h2.8c.386 0 .7.314.7.7v5.1c0 1.03.84 1.87 1.87 1.87h3.933a1.87 1.87 0 0 0 1.87-1.87v-8.04c0-1.02.804-1.847 1.81-1.867h.386a1.868 1.868 0 0 0 1.45-3.21z"/>
            </svg>
        </div>
        <span class="text-[10px] font-medium leading-3 {{ $active === 'home' ? 'text-blue-500' : 'text-gray-400' }}">Home</span>
        @if($active === 'home')
            <span class="absolute -top-px left-1/2 -translate-x-1/2 w-5 h-[2.5px] bg-blue-500 rounded-b-[3px]"></span>
        @endif
    </a>

    {{-- 2. Coming Soon --}}
    <a href="/coming-soon" 
       wire:navigate 
       class="flex flex-col items-center justify-center gap-0.5 px-2 min-w-[56px] relative text-gray-400 hover:text-white transition-colors duration-200 group {{ $active === 'coming-soon' ? 'text-blue-500' : '' }}">
        <div class="w-6 h-6 flex items-center justify-center transition-transform duration-200 group-hover:scale-110">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 28 28">
                <path d="M14 2.5c-6.08 0-11 4.92-11 11s4.92 11 11 11 11-4.92 11-11-4.92-11-11-11zm0 20c-4.96 0-9-4.04-9-9s4.04-9 9-9 9 4.04 9 9-4.04 9-9 9zm1-14h-2v7l5.5 3.3.9-1.5-4.4-2.6V8.5z"/>
            </svg>
        </div>
        <span class="text-[10px] font-medium leading-3 {{ $active === 'coming-soon' ? 'text-blue-500' : 'text-gray-400' }}">Coming Soon</span>
        @if($active === 'coming-soon')
            <span class="absolute -top-px left-1/2 -translate-x-1/2 w-5 h-[2.5px] bg-blue-500 rounded-b-[3px]"></span>
        @endif
    </a>

    {{-- 3. Trends --}}
    <a href="/trending" 
       wire:navigate 
       class="flex flex-col items-center justify-center gap-0.5 px-2 min-w-[56px] relative text-gray-400 hover:text-white transition-colors duration-200 group {{ $active === 'trends' ? 'text-blue-500' : '' }}">
        <div class="w-6 h-6 flex items-center justify-center transition-transform duration-200 group-hover:scale-110">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 28 28">
                <path d="M15.5 2.5c-1.5 0-2.5 1-2.5 2.5v8.5c0 1.5 1 2.5 2.5 2.5s2.5-1 2.5-2.5V5c0-1.5-1-2.5-2.5-2.5zm-8 8c-1.5 0-2.5 1-2.5 2.5v7.5c0 1.5 1 2.5 2.5 2.5s2.5-1 2.5-2.5V13c0-1.5-1-2.5-2.5-2.5zm16 4c-1.5 0-2.5 1-2.5 2.5v3.5c0 1.5 1 2.5 2.5 2.5s2.5-1 2.5-2.5V17c0-1.5-1-2.5-2.5-2.5z"/>
            </svg>
        </div>
        <span class="text-[10px] font-medium leading-3 {{ $active === 'trends' ? 'text-blue-500' : 'text-gray-400' }}">Trends</span>
        @if($active === 'trends')
            <span class="absolute -top-px left-1/2 -translate-x-1/2 w-5 h-[2.5px] bg-blue-500 rounded-b-[3px]"></span>
        @endif
    </a>

    {{-- 4. Shorts --}}
    <a href="/shorts" 
       wire:navigate 
       class="flex flex-col items-center justify-center gap-0.5 px-2 min-w-[56px] relative text-gray-400 hover:text-white transition-colors duration-200 group {{ $active === 'shorts' ? 'text-blue-500' : '' }}">
        <div class="w-6 h-6 flex items-center justify-center transition-transform duration-200 group-hover:scale-110">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 28 28">
                <path d="M20.5 6.5a2.5 2.5 0 0 1 2.5 2.5v10a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 5 19V9a2.5 2.5 0 0 1 2.5-2.5h13zm-7 3.5v8l5-4-5-4z"/>
            </svg>
        </div>
        <span class="text-[10px] font-medium leading-3 {{ $active === 'shorts' ? 'text-blue-500' : 'text-gray-400' }}">Shorts</span>
        @if($active === 'shorts')
            <span class="absolute -top-px left-1/2 -translate-x-1/2 w-5 h-[2.5px] bg-blue-500 rounded-b-[3px]"></span>
        @endif
    </a>
</div>