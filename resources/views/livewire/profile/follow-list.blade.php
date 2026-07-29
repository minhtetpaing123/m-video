<div class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#1e2235] rounded-2xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl border border-gray-700/50">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700/50 flex-shrink-0">
            <h2 class="text-xl font-bold text-white">
                {{ $type === 'followers' ? 'Followers' : 'Following' }}
            </h2>
            <button wire:click="closeModal" 
                    class="text-gray-400 hover:text-white transition p-1 rounded-lg hover:bg-gray-700/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Search & Tabs --}}
        <div class="px-6 py-3 border-b border-gray-700/50 flex-shrink-0">
            {{-- Search Bar --}}
            <div class="relative mb-3">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search people..." 
                       class="w-full bg-[#121420] text-white text-sm rounded-lg pl-10 pr-4 py-2.5 border border-gray-700/50 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            {{-- Tabs --}}
            <div class="flex gap-1 bg-[#121420] rounded-lg p-1">
                <button wire:click="switchTab('followers')" 
                        class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition {{ $type === 'followers' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700/50' }}">
                    Followers <span class="ml-1 text-xs opacity-70">({{ $profileUser->followers_count }})</span>
                </button>
                <button wire:click="switchTab('following')" 
                        class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition {{ $type === 'following' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700/50' }}">
                    Following <span class="ml-1 text-xs opacity-70">({{ $profileUser->following_count }})</span>
                </button>
            </div>
        </div>

        {{-- User List --}}
        <div class="flex-1 overflow-y-auto px-4 py-2">
            @if($users->count() > 0)
                <div class="divide-y divide-gray-700/30">
                    @foreach($users as $user)
                        @php
                            $buttonState = $this->getFollowButtonState($user);
                            $isOnline = $user->isOnline();
                        @endphp
                        <div class="flex items-center gap-3 py-3 hover:bg-white/5 rounded-lg px-3 transition" wire:key="user-{{ $user->id }}">
                            {{-- Avatar --}}
                            <a href="{{ route('profile.show', $user->id) }}" class="flex-shrink-0">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-700/50">
                            </a>

                            {{-- User Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('profile.show', $user->id) }}" class="hover:underline">
                                        <h4 class="text-sm font-semibold text-white truncate">{{ $user->name }}</h4>
                                    </a>
                                    
                                    {{-- ✅ Real-time Online Status Badge --}}
                                    <span data-user-id="{{ $user->id }}" 
                                          class="user-status-badge flex-shrink-0 relative inline-flex h-3 w-3 user-status-badge-{{ $user->id }} {{ $isOnline ? '' : 'hidden' }}">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                    </span>
                                </div>
                                
                                <p class="text-xs text-gray-400 truncate">@ {{ $user->username ?? 'user' }}</p>
                                
                                {{-- ✅ Real-time Last Seen Text (Time-Ago Calculator Safe) --}}
                                <p class="text-[10px] text-gray-500 mt-0.5 user-last-seen-{{ $user->id }} {{ $isOnline ? 'hidden' : '' }}"
                                   data-user-id="{{ $user->id }}"
                                   data-last-seen="{{ $user->last_seen_at ? $user->last_seen_at->toIso8601String() : '' }}">
                                    Last seen {{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'recently' }}
                                </p>

                                {{-- Mutual Friends --}}
                                @php
                                    $mutualCount = auth()->check() ? auth()->user()->mutualFriendsCount($user) : 0;
                                @endphp
                                @if($mutualCount > 0)
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $mutualCount }} mutual friend{{ $mutualCount > 1 ? 's' : '' }}
                                    </p>
                                @endif

                                {{-- Follow Back Label --}}
                                @if(auth()->check() && auth()->id() !== $user->id && $user->isFollowedBy(auth()->user()) && !auth()->user()->isFollowing($user))
                                    <p class="text-xs text-green-400 mt-0.5 font-medium">
                                        Follows you
                                    </p>
                                @endif
                            </div>

                            {{-- Follow Button --}}
                            @if(auth()->check() && auth()->id() !== $user->id && $buttonState)
                                <button wire:click="toggleFollow({{ $user->id }})" 
                                        class="px-4 py-1.5 text-xs font-semibold rounded-lg transition flex-shrink-0 {{ $buttonState['class'] }}">
                                    {{ $buttonState['text'] }}
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-4xl mb-3">👥</div>
                    <p class="text-gray-400 text-sm">No {{ $type }} found</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Unfollow Modal --}}
    @if($confirmingUnfollow && $unfollowUserId)
        <div class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-[#1e2235] rounded-2xl max-w-md w-full p-6 border border-gray-700/50 shadow-2xl">
                <h3 class="text-xl font-bold text-white text-center mb-2">
                    Unfollow {{ $unfollowUserName }}?
                </h3>
                <p class="text-sm text-gray-400 text-center mb-6">
                    They won't know you unfollowed them.
                </p>

                <div class="flex gap-3">
                    <button wire:click="cancelUnfollow" 
                            class="flex-1 px-4 py-2.5 bg-gray-700 text-white font-medium rounded-lg">
                        Cancel
                    </button>
                    <button wire:click="executeUnfollow" 
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg">
                        Unfollow
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- ✅ Livewire re-render ဖြစ်တိုင်း Online Status ပြန် Sync လုပ်ပေးမည့် Script --}}
<script>
    document.addEventListener('livewire:navigated', () => {
        if (window.MVideoApp && typeof window.MVideoApp.syncOnlineUsers === 'function') {
            window.MVideoApp.syncOnlineUsers();
        }
    });
</script>
