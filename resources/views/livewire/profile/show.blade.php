{{-- File Path: resources/views/profile/show.blade.php --}}
{{-- Purpose: Profile Sub-components (Header, Tabs, Feed/About) အားလုံးကို စုစည်းပြသပေးသော Main Profile Page View --}}

<div>
    <div class="bg-gray-100 dark:bg-[#121420] text-gray-900 dark:text-gray-100 min-h-screen pb-24 transition-colors duration-200">
        <div class="max-w-xl mx-auto">

            {{-- 1. Profile Header (Cover, Avatar, Bio, Follow/Edit Actions) --}}
            <livewire:profile.profile-header :user="$user" />

            {{-- 2. Profile Tabs (Posts, Videos, About, Photos navigation) --}}
            <livewire:profile.profile-tabs />

            {{-- Content Area --}}
            <div class="p-3 space-y-3">
                
                {{-- 3. Feed & About Section (Tab အလိုက် Posts/Videos/Photos သို့မဟုတ် About Info ပြသမည်) --}}
                <livewire:profile.profile-feed :user="$user" />

            </div>

        </div>
    </div>

    {{-- ============================================ --}}
    {{-- ✅ FOLLOW LIST MODAL --}}
    {{-- ============================================ --}}
    @if(isset($showFollowModal) && $showFollowModal && isset($followUserId))
        <livewire:profile.follow-list 
            :user="$user" 
            :type="$followType ?? 'followers'" 
            :key="'follow-list-' . $followUserId . '-' . ($followType ?? 'followers')" 
        />
    @endif
</div>
