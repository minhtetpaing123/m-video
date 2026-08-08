<div class="space-y-3">
    <div class="flex items-center justify-between pt-1">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">
            Follow Requests <span class="text-red-500 text-xs font-extrabold ml-1">{{ $requests->count() }}</span>
        </h2>
    </div>

    <div class="space-y-3">
        @forelse($requests as $reqUser)
            <div class="flex items-start gap-3 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
                <img src="{{ $reqUser->avatar_url }}" class="w-16 h-16 rounded-full object-cover shadow-xs flex-shrink-0">
                
                <div class="flex-1 min-w-0 pt-0.5">
                    <div class="flex justify-between items-baseline">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                            {{ $reqUser->name }}
                        </h3>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">
                            {{ $reqUser->pivot->created_at ? $reqUser->pivot->created_at->diffForHumans() : '' }}
                        </span>
                    </div>
                    
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-2">
                        Requested to follow you
                    </p>

                    <div class="flex items-center gap-2">
                        <button wire:click="acceptRequest({{ $reqUser->id }})" 
                                class="flex-1 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Accept
                        </button>
                        <button wire:click="removeFriend({{ $reqUser->id }})" 
                                class="flex-1 py-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 active:scale-95 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition">
                            Decline
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-14 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200">No Follow Requests</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">When people request to follow you, they'll appear here.</p>
            </div>
        @endforelse
    </div>
</div>
