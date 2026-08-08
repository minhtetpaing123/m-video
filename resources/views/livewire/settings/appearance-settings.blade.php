<div class="flex flex-col min-h-full bg-white dark:bg-[#1e2235] text-gray-900 dark:text-white transition-colors duration-200">

    {{-- Header with Back Button --}}
    <div class="flex items-center justify-between px-4 sm:px-8 py-4 border-b border-gray-200 dark:border-gray-800 sticky top-0 bg-white/80 dark:bg-[#1e2235]/80 backdrop-blur-md z-10">
        <button wire:click="$parent.setTab('menu')" type="button" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-300 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Dark Mode & Appearance</h3>
        <div class="w-9"></div>
    </div>

    {{-- Main Options Content --}}
    <div class="p-4 sm:p-8 space-y-6 flex-1">
        <div class="space-y-1">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Theme Mode</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400">Choose how your platform interface looks to you.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            {{-- Dark Theme Option --}}
            <button type="button"
                    onclick="document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark');"
                    wire:click="updateTheme('dark')" 
                    class="p-4 rounded-xl border flex flex-col items-center gap-3 transition text-center
                    {{ $theme === 'dark' ? 'bg-blue-500/10 border-blue-500 text-blue-600 dark:text-white' : 'bg-gray-50 dark:bg-gray-900/60 border-gray-200 dark:border-gray-800/80 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/80' }}">
                <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-gray-700 flex items-center justify-center text-slate-700 dark:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold">Dark Mode</p>
                    <p class="text-[11px] opacity-70">Always dark theme</p>
                </div>
            </button>

            {{-- Light Theme Option --}}
            <button type="button"
                    onclick="document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light');"
                    wire:click="updateTheme('light')" 
                    class="p-4 rounded-xl border flex flex-col items-center gap-3 transition text-center
                    {{ $theme === 'light' ? 'bg-blue-500/10 border-blue-500 text-blue-600 dark:text-white' : 'bg-gray-50 dark:bg-gray-900/60 border-gray-200 dark:border-gray-800/80 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/80' }}">
                <div class="w-12 h-12 rounded-full bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold">Light Mode</p>
                    <p class="text-[11px] opacity-70">Always bright theme</p>
                </div>
            </button>

            {{-- System Auto Option --}}
            <button type="button"
                    onclick="localStorage.removeItem('theme'); if(window.matchMedia('(prefers-color-scheme: dark)').matches){ document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }"
                    wire:click="updateTheme('system')" 
                    class="p-4 rounded-xl border flex flex-col items-center gap-3 transition text-center
                    {{ $theme === 'system' ? 'bg-blue-500/10 border-blue-500 text-blue-600 dark:text-white' : 'bg-gray-50 dark:bg-gray-900/60 border-gray-200 dark:border-gray-800/80 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/80' }}">
                <div class="w-12 h-12 rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold">Automatic</p>
                    <p class="text-[11px] opacity-70">Sync with device system</p>
                </div>
            </button>
        </div>
    </div>
</div>
