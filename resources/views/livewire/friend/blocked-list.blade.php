<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 pb-24 pt-16 transition-colors duration-300"
     x-data="{ showUnblockModal: false, selectedUser: null }">
    
    {{-- 🟢 Top Fixed Header with Back Button --}}
    <div class="fixed top-0 left-0 right-0 z-30 flex justify-center bg-white/90 dark:bg-gray-900/90 backdrop-blur-md shadow-xs border-b border-gray-100 dark:border-gray-800">
        <div class="w-full max-w-xl px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button onclick="history.back()" class="p-2 -ml-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition active:scale-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <h1 class="text-base font-bold text-gray-900 dark:text-white">Blocked Accounts</h1>
            </div>
            
            <span class="px-3 py-1 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-full border border-red-100 dark:border-red-900/40">
                {{ auth()->user()->blockedUsers()->count() }} blocked
            </span>
        </div>
    </div>

    {{-- 🟢 Main Content Container --}}
    <div class="w-full max-w-xl mx-auto px-4 mt-3">
        @if($blockedUsers->count() > 0)
            <div class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700/60 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700/50">
                @foreach($blockedUsers as $user)
                    <div wire:key="blocked-user-{{ $user->id }}" class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                        
                        {{-- User Info Section --}}
                        <div class="flex items-center gap-3 min-w-0">
                            @if(!empty($user->profile_photo_url))
                                <img src="{{ $user->profile_photo_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 dark:border-gray-700 shadow-xs shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-rose-500 to-red-500 text-white font-bold text-sm flex items-center justify-center shadow-xs shrink-0 ring-2 ring-white dark:ring-gray-800">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="truncate">
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    {{ $user->name }}
                                </h2>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Blocked on {{ $user->pivot->created_at ? $user->pivot->created_at->format('M d, Y') : 'recently' }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <button type="button" 
                                @click="selectedUser = { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}' }; showUnblockModal = true"
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700/80 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition-all duration-200 active:scale-95 shrink-0 border border-transparent hover:border-blue-200 dark:hover:border-blue-800/40">
                            <span>Unblock</span>
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($blockedUsers->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $blockedUsers->links() }}
                </div>
            @endif
        @else
            {{-- 🟢 Empty State --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 text-center border border-gray-100 dark:border-gray-700/60 shadow-xs mt-6">
                <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">No Blocked Accounts</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">
                    When you block someone, they won't be able to see your posts or profile. They will appear here.
                </p>
            </div>
        @endif
    </div>

    {{-- ✨ Modern Custom Unblock Modal --}}
    <template x-teleport="body">
        <div x-show="showUnblockModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            
            <div @click.away="showUnblockModal = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-3xl p-6 shadow-2xl border border-gray-100 dark:border-gray-700/60 text-center flex flex-col items-center">
                
                {{-- Info Icon --}}
                <div class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    Unblock <span x-text="selectedUser?.name"></span>?
                </h3>
                
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6 px-1">
                    They will be able to see your posts and profile again depending on your privacy settings.
                </p>

                {{-- Action Buttons --}}
                <div class="w-full flex flex-col gap-2">
                    <button type="button" 
                            @click="showUnblockModal = false; $wire.unblock(selectedUser.id)"
                            wire:loading.attr="disabled"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-98 text-white font-semibold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                        <span>Unblock</span>
                    </button>
                    
                    <button type="button" 
                            @click="showUnblockModal = false"
                            class="w-full py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700/50 dark:hover:bg-gray-700 active:scale-98 text-gray-700 dark:text-gray-300 font-semibold text-sm rounded-xl transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- 🟢 Bottom Navigation Bar --}}
    <livewire:layout.nav active="settings" />
</div>
