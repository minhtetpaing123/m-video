{{-- File Path: resources/views/livewire/profile/profile-header.blade.php --}}
{{-- Purpose: Profile Avatar နှင့် Cover (Image, GIF, Video) UI Component View --}}

@php
    $isUserOnline = method_exists($user, 'isOnline') ? $user->isOnline() : false;
    $isOwner = auth()->check() && auth()->id() === $user->id;
@endphp

<div class="relative bg-[#1e2235]"
     x-data="{ 
        isOnline: @json($isUserOnline),
        deviceOnline: navigator.onLine 
     }"
     x-init="
        const updateOnlineStatus = () => {
            deviceOnline = navigator.onLine;
        };

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);

        const initEcho = () => {
            if (typeof Echo !== 'undefined') {
                Echo.join('chat')
                    .here((users) => {
                        isOnline = users.some(u => Number(u.id) === Number({{ $user->id }}));
                    })
                    .joining((user) => {
                        if (Number(user.id) === Number({{ $user->id }})) {
                            isOnline = true;
                        }
                    })
                    .leaving((user) => {
                        if (Number(user.id) === Number({{ $user->id }})) {
                            isOnline = false;
                        }
                    });
            } else {
                setTimeout(initEcho, 500);
            }
        };

        initEcho();
     ">
    {{-- Cover Photo / Video Section --}}
    <div class="h-44 sm:h-52 w-full bg-gradient-to-r from-gray-800 via-indigo-950 to-slate-900 relative group overflow-hidden">
        
        {{-- Cover Preview or Existing Cover Display --}}
        @if ($newCover)
            @if(in_array(strtolower($newCover->getClientOriginalExtension()), ['mp4', 'webm', 'mov', 'quicktime']))
                <video src="{{ $newCover->temporaryUrl() }}" autoplay loop muted playsinline class="w-full h-full object-cover opacity-80"></video>
            @else
                <img src="{{ $newCover->temporaryUrl() }}" class="w-full h-full object-cover opacity-80">
            @endif
        @elseif($this->coverUrl)
            @if(preg_match('/\.(mp4|webm|mov)$/i', $this->coverUrl))
                <video src="{{ $this->coverUrl }}" autoplay loop muted playsinline class="w-full h-full object-cover"></video>
            @else
                <img src="{{ $this->coverUrl }}" alt="Cover" class="w-full h-full object-cover">
            @endif
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-500 text-xs font-semibold">
                No Cover Photo / Video
            </div>
        @endif

        {{-- Cover Uploading Indicator --}}
        <div wire:loading wire:target="newCover" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white text-xs font-medium z-10">
            Updating Your photo...
        </div>

        {{-- FB Style Cover Edit Button (Owner Only) --}}
        @if(auth()->check() && auth()->id() === $user->id)
            <label class="absolute bottom-3 right-3 bg-black/60 hover:bg-black/80 backdrop-blur-md text-white text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5 cursor-pointer shadow-lg border border-white/10 transition z-10 active:scale-95">
                <svg class="w-4 h-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Edit Cover</span>
                <input type="file" wire:model="newCover" class="hidden" accept="image/*,video/mp4,video/webm,video/quicktime">
            </label>
        @endif
    </div>

    {{-- Profile Photo & Main Info Section --}}
    <div class="px-4 pb-3">
        <div class="flex justify-between items-end -mt-16 mb-3">
            
            {{-- Profile Picture Container (Outer Wrapper) --}}
            <div class="relative w-28 h-28 flex-shrink-0">
                
                {{-- Inner Circle Box --}}
                <div class="w-full h-full rounded-full border-4 border-[#121420] bg-gray-800 overflow-hidden shadow-2xl relative">
                    @if ($newAvatar)
                        <img src="{{ $newAvatar->temporaryUrl() }}" class="w-full h-full object-cover opacity-80">
                    @else
                        <img src="{{ $this->avatarUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @endif

                    {{-- Avatar Uploading Indicator --}}
                    <div wire:loading wire:target="newAvatar" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white text-xs font-medium text-center p-1">
                        Updating...
                    </div>
                </div>

                {{-- FB Style Clean Profile Camera Button (Owner Only) --}}
                @if(auth()->check() && auth()->id() === $user->id)
                    <label class="absolute bottom-1 right-0 bg-gray-800 hover:bg-gray-700 text-white p-2 rounded-full border-2 border-[#121420] cursor-pointer shadow-xl transition z-10 active:scale-95 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input type="file" wire:model="newAvatar" class="hidden" accept="image/*">
                    </label>
                @endif

                {{-- FB Style Dynamic Online Status Badge --}}
                <span x-show="isOnline && deviceOnline" 
                      class="absolute bottom-1 {{ $isOwner ? 'right-9' : 'right-1' }} w-4 h-4 bg-green-500 border-2 border-[#121420] rounded-full z-20"
                      x-cloak>
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                </span>
                <span x-show="!isOnline || !deviceOnline" 
                      class="absolute bottom-1 {{ $isOwner ? 'right-9' : 'right-1' }} w-4 h-4 bg-gray-400 border-2 border-[#121420] rounded-full z-20"
                      x-cloak>
                </span>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 mb-1">
                @if(auth()->check() && auth()->id() === $user->id)
                    <button class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-lg flex items-center gap-1.5 shadow-md transition">
                        ➕ Add Story
                    </button>
                    <button class="px-4 py-1.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 font-semibold text-xs rounded-lg flex items-center gap-1.5 transition">
                        ✏️ Edit Profile
                    </button>
                @else
                    {{-- Follow / Following Button --}}
                    <button wire:click="toggleFollow" class="px-4 py-1.5 font-semibold text-xs rounded-lg flex items-center gap-1.5 shadow-md transition {{ $isFollowing ? 'bg-gray-700 hover:bg-gray-600 text-gray-200 border border-gray-600' : 'bg-blue-600 hover:bg-blue-500 text-white' }}">
                        <span>{{ $isFollowing ? '✓ Following' : '👤 Follow' }}</span>
                    </button>

                    {{-- ✅ Message Button - Chat ကိုသွားရန် --}}
                    <a href="{{ route('chat.show', $user->id) }}" 
                       class="px-4 py-1.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 font-semibold text-xs rounded-lg flex items-center gap-1.5 transition">
                        💬 Message
                    </a>
                @endif
            </div>
        </div>

        {{-- Name & Username --}}
        <div class="mt-1">
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-1.5">
                {{ $user->name }}
            </h1>
            
            <div class="flex items-center gap-2 mt-0.5">
                <p class="text-xs text-gray-400 font-medium">@ {{ $user->username ?? 'user' }}</p>

                {{-- Dynamic Online Status Text --}}
                <span class="text-xs text-gray-500">•</span>
                <span x-show="isOnline && deviceOnline" class="text-xs font-semibold text-green-400" x-cloak>
                    Active now
                </span>
                <span x-show="!isOnline || !deviceOnline" class="text-xs text-gray-400" x-cloak>
                    Last seen {{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'recently' }}
                </span>
            </div>
        </div>

        {{-- Follower & Following Count Display --}}
        <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
            <button wire:click="$dispatch('openFollowList', { userId: {{ $user->id }}, type: 'followers' })" 
                    class="flex items-center gap-1 hover:underline hover:text-blue-400 transition group cursor-pointer">
                <strong class="text-white text-sm group-hover:text-blue-400 transition">{{ $this->followersCount }}</strong>
                <span>Followers</span>
            </button>
            <button wire:click="$dispatch('openFollowList', { userId: {{ $user->id }}, type: 'following' })" 
                    class="flex items-center gap-1 hover:underline hover:text-blue-400 transition group cursor-pointer">
                <strong class="text-white text-sm group-hover:text-blue-400 transition">{{ $this->followingCount }}</strong>
                <span>Following</span>
            </button>
        </div>

        @if($user->bio)
            <p class="text-xs text-gray-300 mt-2.5 font-normal leading-relaxed">
                {{ $user->bio }}
            </p>
        @endif
    </div>
</div>
