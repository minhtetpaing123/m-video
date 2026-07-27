{{-- File Path: resources/views/livewire/profile/profile-intro.blade.php --}}
{{-- Purpose: Intro Card (Videos, Followers, Joined date) များကို Card ပုံစံဖြင့် ပြသပေးသော Blade View --}}

<div class="bg-[#1e2235] rounded-xl p-4 border border-gray-800/80 shadow-md">
    <h3 class="text-sm font-bold text-white mb-3">Intro</h3>
    
    <div class="space-y-2.5 text-xs text-gray-300">
        <div class="flex items-center gap-2.5">
            <span class="text-base">🎬</span>
            <span>Uploaded <strong class="text-white">{{ $videoCount }}</strong> Videos</span>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="text-base">👥</span>
            <span>Followed by <strong class="text-white">{{ $user->followers_count ?? 0 }}</strong> people</span>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="text-base">📅</span>
            <span>Joined {{ $user->created_at ? $user->created_at->format('F Y') : 'Recently' }}</span>
        </div>
    </div>
</div>
