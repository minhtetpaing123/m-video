@props(['post'])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow mb-3 w-full max-w-[680px] mx-auto relative" data-post-id="{{ $post->id }}">
    {{-- Post Header --}}
    <div class="flex items-center justify-between px-4 pt-3 pb-2">
        <div class="flex items-center space-x-3">
            {{-- User Avatar --}}
            <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">
                @if($post->user && $post->user->avatar)
                    <img src="{{ asset('storage/' . $post->user->avatar) }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr($post->user->name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>
            
            {{-- User Info --}}
            <div>
                <div class="font-semibold text-sm">{{ $post->user->name ?? 'Unknown' }}</div>
                <div class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }} · 🌐</div>
            </div>
        </div>

        {{-- Three Dots Menu Button --}}
        <x-post.menu-button :post="$post" />
    </div>

    {{-- Post Content --}}
    @if($post->content)
        <div class="px-4 pb-2 text-sm whitespace-pre-line">{{ $post->content }}</div>
    @endif

    {{-- ============================================ --}}
    {{-- POST MEDIA --}}
    {{-- ============================================ --}}
    @if($post->image || $post->video || $post->link || $post->video_cdn_url)
        
        {{-- IMAGE --}}
        @if($post->image)
            <div class="px-0 pb-2">
                <div class="w-full overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="Post image" 
                         class="w-full h-auto object-contain"
                         loading="lazy"
                         onerror="this.src='https://via.placeholder.com/600x400?text=Image+Not+Found'">
                </div>
            </div>
        @endif

        {{-- VIDEO - Using Separated Component --}}
        @if($post->video_cdn_url || $post->video)
            <x-post.video-player 
                :video-cdn-url="$post->video_cdn_url ?? null"
                :video-local-url="$post->video ? asset('storage/' . $post->video) : null"
                :thumbnail-url="$post->video_thumbnail_url ?? null"
                :post-id="$post->id"
                :autoplay="false"
                :muted="false"
                :loop="false"
                :controls="true"
                max-height="70vh"
                min-height="200px"
                :show-duration="true"
                :show-play-overlay="true"
            />
        @endif

        {{-- LINK --}}
        @if($post->link)
            <div class="px-4 pb-2">
                <a href="{{ $post->link }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition border border-gray-200">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🔗</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-blue-600 hover:underline truncate">
                                {{ $post->link_title ?? $post->link }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ $post->link }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    @endif

    {{-- Post Stats --}}
    <x-post.stats 
        :likes-count="$post->likes_count"
        :comments-count="$post->comments_count"
        :shares-count="$post->shares_count"
        :reaction-summary="$post->reaction_summary ?? []"
        :post-id="$post->id"
    />

    {{-- Post Actions --}}
    <x-post.actions 
        :post-id="$post->id"
        :initial-liked="$post->isLikedBy(auth()->id()) ?? false"
        :likes-count="$post->likes_count"
        :comments-count="$post->comments_count"
        :shares-count="$post->shares_count"
        :reaction-type="null"
    />

    {{-- Comments Section --}}
    <x-post.comments-section 
        :comments="$post->comments"
        :comments-count="$post->comments_count"
        :post-id="$post->id"
    />
</div>