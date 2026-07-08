@props(['post'])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow mb-3 w-full" style="max-width: 100%;">
    {{-- Post Header Component --}}
    <x-post.header :post="$post" />

    {{-- Post Content Component --}}
    @if($post->content)
        <x-post.content :content="$post->content" />
    @endif

    {{-- Post Media Component --}}
    @if($post->image || $post->video || $post->link)
        <x-post.media 
            :image="$post->image" 
            :video="$post->video"
            :link="$post->link"
            :link-title="$post->link_title"
        />
    @endif

    {{-- Post Stats Component --}}
    <x-post.stats 
        :post-id="$post->id"
        :likes-count="$post->likes_count"
        :comments-count="$post->comments_count"
        :shares-count="$post->shares_count"
        :reaction-summary="$post->reaction_summary ?? []"
    />

    {{-- Post Actions Component --}}
    <x-post.actions 
        :post-id="$post->id"
        :initial-liked="$post->isLikedBy(auth()->id()) ?? false"
        :initial-likes-count="$post->likes_count"
        :initial-comments-count="$post->comments_count"
        :initial-shares-count="$post->shares_count"
    />

    {{-- Comments Section Component --}}
    <x-post.comments-section 
        :post-id="$post->id"
        :comments="$post->comments"
        :comments-count="$post->comments_count"
    />
</div>