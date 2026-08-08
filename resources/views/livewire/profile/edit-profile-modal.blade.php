<div x-data x-init="
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else if (savedTheme === 'light') {
        document.documentElement.classList.remove('dark');
    }
" class="w-full min-h-screen bg-gray-100 dark:bg-[#121521] text-gray-900 dark:text-white transition-colors duration-200">

    {{-- ⚡ Component Load လုပ်သည်နှင့် Dark Mode Class ကို ချက်ချင်း ရိုက်ထည့်ပေးမည့် Script --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <div class="w-full max-w-4xl mx-auto min-h-screen bg-white dark:bg-[#1e2235] shadow-2xl flex flex-col transition-colors duration-200">
        
        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="bg-green-600/20 border-b border-green-500/30 text-green-700 dark:text-green-300 text-xs sm:text-sm px-4 sm:px-6 py-3 flex justify-between items-center sticky top-0 z-50 backdrop-blur-md">
                <span>{{ session('message') }}</span>
                <button wire:click="$refresh" class="text-green-500 dark:text-green-400 font-bold p-1">✕</button>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- MAIN SETTINGS MENU LIST (FB Full Features) --}}
        {{-- ============================================ --}}
        @if ($activeTab === 'menu')
            <div class="p-4 sm:p-8 space-y-6 sm:space-y-8 flex-1">
                
                {{-- Top Header --}}
                <div class="pb-2 border-b border-gray-200 dark:border-gray-800/80">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings & Privacy
                    </h2>
                </div>

                {{-- 1. ACCOUNT SETTINGS --}}
                <div class="space-y-2">
                    <div class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">Account Settings</div>
                    <div class="space-y-2">
                        {{-- Edit Profile Info --}}
                        <button wire:click="setTab('edit-profile')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-blue-500 transition">Edit Profile</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Name, Username, Bio & Photos</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Password & Security --}}
                        <button wire:click="setTab('change-password')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-green-500 transition">Password & Security</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Change password & account security</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Personal Details --}}
                        <button wire:click="setTab('personal-details')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-cyan-500/10 text-cyan-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-cyan-500 transition">Personal Details</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Contact info, Email, Phone & Birthday</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 2. PREFERENCES --}}
                <div class="space-y-2">
                    <div class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">Preferences</div>
                    <div class="space-y-2">
                        {{-- Notifications --}}
                        <button wire:click="setTab('notifications-pref')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-yellow-500/10 text-yellow-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-yellow-500 transition">Notifications</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Push & email notification settings</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Dark Mode / Theme --}}
                        <button wire:click="setTab('appearance')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-slate-500/10 text-slate-500 dark:text-slate-300 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-slate-500 dark:group-hover:text-slate-300 transition">Dark Mode & Appearance</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Theme and display options</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Media & Quality --}}
                        <button wire:click="setTab('media-quality')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-emerald-500 transition">Media & Autoplay</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Video quality, HD upload & autoplay</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 3. PRIVACY & CONTROLS --}}
                <div class="space-y-2">
                    <div class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">Privacy & Controls</div>
                    <div class="space-y-2">
                        {{-- Friends --}}
                        <a href="{{ route('friends') }}" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-indigo-500/10 text-indigo-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-indigo-500 transition">Friends</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Manage friend requests & list</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- Blocking --}}
                        <a href="{{ route('settings.blocked-users') }}" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-red-500 transition">Blocking</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Manage blocked users list</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- Profile & Tagging --}}
                        <button wire:click="setTab('profile-tagging')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-orange-500/10 text-orange-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 10h5M7 13h5M17 21l-5-5-5 5V5a2 2 0 012-2h6a2 2 0 012 2v16z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-orange-500 transition">Profile & Tagging</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Who can post & tag on your profile</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 4. YOUR ACTIVITY & INFORMATION --}}
                <div class="space-y-2">
                    <div class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">Your Activity & Information</div>
                    <div class="space-y-2">
                        {{-- Activity Log --}}
                        <button wire:click="setTab('activity-log')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-violet-500/10 text-violet-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-violet-500 transition">Activity Log</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">View likes, comments & search history</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Saved Posts --}}
                        <a href="{{ route('saved') }}" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-pink-500 transition">Saved Items</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Your bookmarked posts & videos</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- Download Your Information --}}
                        <button wire:click="setTab('download-data')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 hover:bg-gray-100 dark:hover:bg-gray-800/90 active:scale-[0.99] transition border border-gray-200 dark:border-gray-800/80 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-teal-500/10 text-teal-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white group-hover:text-teal-500 transition">Download Your Info</p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Download a copy of your account data</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 5. ACCOUNT ACTIONS --}}
                <div class="space-y-2 pt-2">
                    <div class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">Account Actions</div>
                    <div class="space-y-2">
                        {{-- Deactivation or Deletion --}}
                        <button wire:click="setTab('account-deactivation')" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 active:scale-[0.99] transition border border-amber-500/20 group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm sm:text-base font-semibold text-amber-600 dark:text-amber-400">Deactivation or Deletion</p>
                                    <p class="text-xs sm:text-sm text-amber-600/70 dark:text-amber-300/70">Temporarily deactivate or delete account</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-amber-500 group-hover:text-gray-700 dark:group-hover:text-white transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Log Out --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-between p-3.5 sm:p-4 rounded-xl bg-red-500/10 hover:bg-red-500/20 active:scale-[0.99] transition border border-red-500/20 group">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm sm:text-base font-semibold text-red-600 dark:text-red-400">Log Out</p>
                                        <p class="text-xs sm:text-sm text-red-600/70 dark:text-red-300/70">Sign out of your account</p>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        {{-- ============================================ --}}
        {{-- SUB-PAGE: EDIT PROFILE FORM                  --}}
        {{-- ============================================ --}}
        @elseif ($activeTab === 'edit-profile')
            <div class="flex items-center justify-between px-4 sm:px-8 py-4 border-b border-gray-200 dark:border-gray-800 sticky top-0 bg-white dark:bg-[#1e2235] z-10">
                <button wire:click="setTab('menu')" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Edit Profile Details</h3>
                <div class="w-9"></div>
            </div>

            <form wire:submit.prevent="saveProfile" class="p-4 sm:p-8 space-y-5 flex-1">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Display Name</label>
                    <input type="text" wire:model="name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 transition">
                    @error('name') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Username</label>
                    <input type="text" wire:model="username" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 transition">
                    @error('username') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Bio</label>
                    <textarea wire:model="bio" rows="4" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 resize-none transition"></textarea>
                    @error('bio') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Change Avatar</label>
                    <input type="file" wire:model="newAvatar" accept="image/*" class="w-full text-xs sm:text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-200 dark:file:bg-gray-800 file:text-gray-700 dark:file:text-gray-200 hover:file:bg-gray-300 dark:hover:file:bg-gray-700 cursor-pointer">
                    @error('newAvatar') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Change Cover</label>
                    <input type="file" wire:model="newCover" accept="image/*,video/mp4,video/webm,video/quicktime" class="w-full text-xs sm:text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-200 dark:file:bg-gray-800 file:text-gray-700 dark:file:text-gray-200 hover:file:bg-gray-300 dark:hover:file:bg-gray-700 cursor-pointer">
                    @error('newCover') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <button type="button" wire:click="setTab('menu')" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs sm:text-sm font-semibold transition">
                        Cancel
                    </button>
                    <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition">
                        <span wire:loading wire:target="saveProfile" class="animate-spin text-xs">🌀</span>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>

        {{-- ============================================ --}}
        {{-- SUB-PAGE: CHANGE PASSWORD FORM               --}}
        {{-- ============================================ --}}
        @elseif ($activeTab === 'change-password')
            <div class="flex items-center justify-between px-4 sm:px-8 py-4 border-b border-gray-200 dark:border-gray-800 sticky top-0 bg-white dark:bg-[#1e2235] z-10">
                <button wire:click="setTab('menu')" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Change Password</h3>
                <div class="w-9"></div>
            </div>

            <form wire:submit.prevent="changePassword" class="p-4 sm:p-8 space-y-5 flex-1">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Current Password</label>
                    <input type="password" wire:model="current_password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-green-500 transition">
                    @error('current_password') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">New Password</label>
                    <input type="password" wire:model="new_password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-green-500 transition">
                    @error('new_password') <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Confirm New Password</label>
                    <input type="password" wire:model="new_password_confirmation" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-green-500 transition">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <button type="button" wire:click="setTab('menu')" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs sm:text-sm font-semibold transition">
                        Cancel
                    </button>
                    <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 bg-green-600 hover:bg-green-500 text-white rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition">
                        <span wire:loading wire:target="changePassword" class="animate-spin text-xs">🌀</span>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>

        {{-- ============================================ --}}
        {{-- SUB-PAGE: DARK MODE & APPEARANCE            --}}
        {{-- ============================================ --}}
        @elseif ($activeTab === 'appearance')
            <livewire:settings.appearance-settings :wire:key="'appearance-settings'" />

        {{-- ============================================ --}}
        {{-- PLACEHOLDER FOR FUTURE TABS                  --}}
        {{-- ============================================ --}}
        @else
            <div class="flex items-center justify-between px-4 sm:px-8 py-4 border-b border-gray-200 dark:border-gray-800 sticky top-0 bg-white dark:bg-[#1e2235] z-10">
                <button wire:click="setTab('menu')" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white uppercase">{{ str_replace('-', ' ', $activeTab) }}</h3>
                <div class="w-9"></div>
            </div>

            <div class="p-8 sm:p-16 text-center text-gray-500 dark:text-gray-400 space-y-4 my-auto">
                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto text-blue-500 text-xl">
                    ⚙️
                </div>
                <p class="text-base font-semibold text-gray-900 dark:text-white">Feature Under Development</p>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Logic and interface for {{ str_replace('-', ' ', $activeTab) }} will be added in the next update.</p>
                <button wire:click="setTab('menu')" class="mt-2 px-5 py-2.5 bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 text-xs sm:text-sm text-gray-700 dark:text-gray-300 rounded-xl font-medium transition">
                    Back to Settings
                </button>
            </div>
        @endif

    </div>
</div>
