@props(['post'])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow mb-3 w-full relative" data-post-id="{{ $post->id }}">
    {{-- Post Header --}}
    <div class="flex items-center justify-between px-4 pt-3 pb-2">
        <div class="flex items-center space-x-3">
            {{-- User Avatar --}}
            <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden">
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

    {{-- Post Media --}}
    @if($post->image || $post->video || $post->link)
        <x-post.media 
            :image="$post->image" 
            :video="$post->video"
            :link="$post->link"
            :link-title="$post->link_title"
        />
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
        :reaction-type="$post->getUserReactionAttribute(auth()->id())"
    />

    {{-- Comments Section --}}
    <x-post.comments-section 
        :comments="$post->comments"
        :comments-count="$post->comments_count"
        :post-id="$post->id"
    />
</div>
<script>
$(document).ready(function() {
    console.log('Card JS loaded');
    
    // Test direct click
    $('.post-menu-btn').click(function() {
        console.log('Menu button clicked');
        alert('Button clicked');
    });
});
</script>