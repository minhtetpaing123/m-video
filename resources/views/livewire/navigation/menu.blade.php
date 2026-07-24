<nav class="bg-gray-900/95 backdrop-blur-sm border-b border-gray-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            {{-- Left: Logo --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" 
                   wire:navigate
                   class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16v12H4V6zm1 1v10h14V7H5zm2 2h10v2H7V9zm0 4h6v2H7v-2zm8 0h2v2h-2v-2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent hidden sm:inline">
                        M-VIDEO
                    </span>
                </a>
            </div>

            {{-- Center: Search --}}
            <div class="flex-1 max-w-2xl mx-4 hidden md:block">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" 
                           name="q" 
                           placeholder="Search videos..." 
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-full text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 transition duration-200">
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Right: Navigation Links --}}
            <div class="flex items-center gap-2">
                
                {{-- Search Icon (Mobile) --}}
                <a href="{{ route('search') }}" 
                   wire:navigate
                   class="p-2 rounded-full hover:bg-gray-800 text-gray-400 hover:text-white transition duration-200 md:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- Home --}}
                <a href="{{ route('home') }}" 
                   wire:navigate
                   class="px-3 py-2 rounded-full text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition duration-200">
                    Home
                </a>

                {{-- Coming Soon --}}
                <a href="#" 
                   class="px-3 py-2 rounded-full text-sm font-medium text-gray-500 hover:text-gray-300 hover:bg-gray-800 transition duration-200">
                    Coming Soon
                </a>

                {{-- Trends --}}
                <a href="#" 
                   class="px-3 py-2 rounded-full text-sm font-medium text-gray-500 hover:text-gray-300 hover:bg-gray-800 transition duration-200">
                    Trends
                </a>

                {{-- Shorts --}}
                <a href="#" 
                   class="px-3 py-2 rounded-full text-sm font-medium text-gray-500 hover:text-gray-300 hover:bg-gray-800 transition duration-200">
                    Shorts
                </a>

                {{-- ❌ Settings Icon ဖယ်လိုက်ပါ --}}

                {{-- User Menu --}}
                <livewire:header.user-menu />
            </div>
        </div>
    </div>
</nav>