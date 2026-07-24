<div class="glass-card rounded-3xl p-8 md:p-10 mb-8 relative overflow-hidden">
    {{-- Background Glow --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
    
    <div class="relative flex flex-col md:flex-row items-center gap-8">
        
        {{-- Avatar --}}
        <div class="avatar-ring flex-shrink-0">
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('default-avatar.png') }}" 
                 alt="Avatar"
                 class="w-28 h-28 rounded-full object-cover border-2 border-white/10">
        </div>

        {{-- User Info --}}
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold glow-text">{{ $user->name }}</h1>
            <p class="text-gray-400 text-sm mt-1">@ {{ $user->username ?? 'user' }}</p>
            
            {{-- Bio with real-time edit --}}
            <p class="text-gray-300 mt-2 max-w-md mx-auto md:mx-0">
                {{ $user->bio ?? 'No bio yet' }}
            </p>
            
            {{-- Stats --}}
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-3 text-sm text-gray-400">
                <span class="flex items-center gap-1">
                    <i class="fas fa-video text-blue-400"></i> 
                    <span wire:loading.remove>{{ $videoCount }}</span>
                    <span wire:loading>...</span>
                    Videos
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-users text-purple-400"></i>
                    <span wire:loading.remove>{{ $followersCount }}</span>
                    <span wire:loading>...</span>
                    Followers
                </span>
                <span>
                    <i class="fas fa-calendar-alt text-purple-400 mr-1"></i> 
                    Joined {{ $user->created_at->format('M Y') }}
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex-shrink-0 flex flex-col gap-2">
            {{-- Follow Button --}}
            @auth
                @if(auth()->id() != $user->id)
                    <button wire:click="toggleFollow" 
                            class="px-6 py-2.5 rounded-full transition duration-300 text-sm font-medium
                                   {{ $isFollowing 
                                       ? 'bg-gray-700 hover:bg-gray-600 text-white' 
                                       : 'bg-blue-600 hover:bg-blue-700 text-white' }}">
                        <span wire:loading.remove>
                            {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                        </span>
                        <span wire:loading>...</span>
                    </button>
                @endif
            @endauth

            {{-- Edit Button --}}
            @auth
                @if(auth()->id() == $user->id)
                    <a href="{{ route('profile.settings') }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-full transition duration-300 border border-white/10 text-sm font-medium">
                        <i class="fas fa-pen"></i>
                        Edit Profile
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>