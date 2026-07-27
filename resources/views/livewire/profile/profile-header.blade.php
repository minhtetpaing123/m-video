{{-- File Path: resources/views/livewire/profile/profile-header.blade.php --}}
{{-- Purpose: Profile Avatar နှင့် Cover (Image, GIF, Video) UI Component View[span_1](start_span)[span_1](end_span) --}}

<div class="relative bg-[#1e2235]">
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

        {{-- Cover Edit Button (Owner Only) --}}
        @if(auth()->check() && auth()->id() === $user->id)
            <label class="absolute bottom-2 right-2 bg-black/60 hover:bg-black/80 backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 cursor-pointer shadow-md transition z-10">
                📷 Edit Cover
                <input type="file" wire:model="newCover" class="hidden" accept="image/*,video/mp4,video/webm,video/quicktime">
            </label>
        @endif
    </div>

    {{-- Profile Photo & Main Info Section --}}
    <div class="px-4 pb-3">
        <div class="flex justify-between items-end -mt-16 mb-3">
            
            {{-- Profile Picture Box --}}
            <div class="relative w-28 h-28 rounded-full border-4 border-[#121420] bg-gray-800 overflow-hidden shadow-2xl flex-shrink-0 group">
                
                @if ($newAvatar)
                    <img src="{{ $newAvatar->temporaryUrl() }}" class="w-full h-full object-cover opacity-80">
                @else
                    <img src="{{ $this->avatarUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @endif

                {{-- Avatar Uploading Indicator --}}
                <div wire:loading wire:target="newAvatar" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white text-xs font-medium text-center p-1">
                    Updating Your photo...
                </div>

                @if(auth()->check() && auth()->id() === $user->id)
                    <label class="absolute bottom-1 right-1 bg-gray-900/90 p-2 rounded-full text-white hover:bg-gray-800 border border-gray-700 cursor-pointer shadow-lg transition">
                        📷
                        <input type="file" wire:model="newAvatar" class="hidden" accept="image/*">
                    </label>
                @endif
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
                    {{-- ✅ Follow / Following Button အလုပ်လုပ်ပုံ --}}
                    <button wire:click="toggleFollow" class="px-4 py-1.5 font-semibold text-xs rounded-lg flex items-center gap-1.5 shadow-md transition {{ $isFollowing ? 'bg-gray-700 hover:bg-gray-600 text-gray-200 border border-gray-600' : 'bg-blue-600 hover:bg-blue-500 text-white' }}">
                        <span>{{ $isFollowing ? '✓ Following' : '👤 Follow' }}</span>
                    </button>

                    <button class="px-4 py-1.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 font-semibold text-xs rounded-lg transition">
                        💬 Message
                    </button>
                @endif
            </div>
        </div>

        {{-- Name & Username --}}
        <div class="mt-1">
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-1.5">
                {{ $user->name }}
            </h1>
            <p class="text-xs text-gray-400 font-medium mt-0.5">@ {{ $user->username ?? 'user' }}</p>
        </div>

        @if($user->bio)
            <p class="text-xs text-gray-300 mt-2.5 font-normal leading-relaxed">
                {{ $user->bio }}
            </p>
        @endif
    </div>
</div>
