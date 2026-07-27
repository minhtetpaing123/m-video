{{-- File Path: resources/views/livewire/profile/profile-feed.blade.php --}}
{{-- Purpose: Tab ပေါ်မူတည်၍ Post Cards သို့မဟုတ် FB-style About Overview Card ကို ပြသပေးသော View ဖြစ်ပါသည်။ --}}

<div class="space-y-3">
    
    {{-- ==================== ABOUT TAB ==================== --}}
    @if($currentTab === 'about')
        <div class="bg-[#1e2235] rounded-xl border border-gray-800/80 p-4 shadow-md space-y-4">
            <h3 class="text-sm font-bold text-white border-b border-gray-800 pb-2">About Overview</h3>

            <div class="space-y-3 text-xs text-gray-300">
                {{-- Bio --}}
                @if($user->bio)
                    <div class="flex items-start gap-3">
                        <span class="text-base">📝</span>
                        <div>
                            <span class="block text-gray-400 font-semibold mb-0.5">Bio</span>
                            <p class="text-gray-200 leading-relaxed">{{ $user->bio }}</p>
                        </div>
                    </div>
                @endif

                {{-- Stats (Uploaded Videos & Followers) --}}
                <div class="flex items-center gap-3">
                    <span class="text-base">🎬</span>
                    <div>
                        <span class="block text-gray-400 font-semibold mb-0.5">Content</span>
                        <span class="text-gray-200">Uploaded <strong class="text-white">{{ $user->posts()->whereNotNull('video_path')->count() }}</strong> Videos</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-base">👥</span>
                    <div>
                        <span class="block text-gray-400 font-semibold mb-0.5">Followers</span>
                        <span class="text-gray-200">Followed by <strong class="text-white">{{ $user->followers_count ?? 0 }}</strong> people</span>
                    </div>
                </div>

                {{-- Joined Date --}}
                <div class="flex items-center gap-3">
                    <span class="text-base">📅</span>
                    <div>
                        <span class="block text-gray-400 font-semibold mb-0.5">Joined</span>
                        <span class="text-gray-200">{{ $user->created_at ? $user->created_at->format('F d, Y') : 'Recently' }}</span>
                    </div>
                </div>

                {{-- Contact Info --}}
                @if($user->email)
                    <div class="flex items-center gap-3">
                        <span class="text-base">📧</span>
                        <div>
                            <span class="block text-gray-400 font-semibold mb-0.5">Email</span>
                            <span class="text-gray-200">{{ $user->email }}</span>
                        </div>
                    </div>
                @endif

                @if($user->phone)
                    <div class="flex items-center gap-3">
                        <span class="text-base">📞</span>
                        <div>
                            <span class="block text-gray-400 font-semibold mb-0.5">Phone</span>
                            <span class="text-gray-200">{{ $user->phone }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    {{-- ==================== POSTS / VIDEOS / PHOTOS TABS ==================== --}}
    @else
        <div class="flex items-center justify-between px-1">
            <h3 class="text-sm font-bold text-white capitalize">
                {{ $currentTab === 'posts' ? 'All Posts' : $currentTab }}
            </h3>
            <span class="text-xs text-gray-400">Manage Posts</span>
        </div>

        @if($posts && $posts->count() > 0)
            @foreach($posts as $post)
                <livewire:dashboard.post.post-card :post="$post" :key="'profile-post-'.$post->id" />
            @endforeach

            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        @else
            <div class="bg-[#1e2235] border border-gray-800/80 rounded-xl p-6 text-center text-xs text-gray-400">
                No {{ $currentTab }} published yet.
            </div>
        @endif
    @endif

</div>
