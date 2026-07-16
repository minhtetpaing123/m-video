{{-- Post Card Component --}}
@props(['post'])

<div class="post-card" style="background: #fff; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    
    {{-- User Info --}}
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: #e4e6eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
            {{ $post->user->name[0] ?? 'U' }}
        </div>
        <div>
            <div style="font-weight: 600; font-size: 14px;">{{ $post->user->name ?? 'User' }}</div>
            <div style="font-size: 12px; color: #65676b;">{{ $post->created_at->diffForHumans() }}</div>
        </div>
    </div>

    {{-- Title --}}
    @if($post->title)
        <h3 style="font-size: 16px; font-weight: 600; margin: 0 0 6px 0;">{{ $post->title }}</h3>
    @endif

    {{-- Content --}}
    @if($post->content)
        <p style="margin: 0 0 10px 0; color: #333; font-size: 14px;">{{ $post->content }}</p>
    @endif

    {{-- ============================================ --}}
    {{-- VIDEO PLAYER - Bunny CDN --}}
    {{-- ============================================ --}}
    @if($post->video_cdn_url)
        <div style="background: #000; border-radius: 8px; overflow: hidden; margin: 10px 0;">
            <video controls style="width: 100%; max-height: 400px; display: block;">
                <source src="{{ $post->video_cdn_url }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    @endif

    {{-- Image --}}
    @if($post->image)
        <div style="margin: 10px 0;">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width: 100%; border-radius: 8px;">
        </div>
    @endif

    {{-- Category Badge --}}
    @if($post->category)
        <div style="margin: 8px 0;">
            <span style="background: #e4e6eb; padding: 4px 12px; border-radius: 16px; font-size: 12px; color: #65676b;">
                {{ $post->category_label }}
            </span>
        </div>
    @endif

    {{-- Actions --}}
    <div style="display: flex; gap: 20px; padding-top: 10px; border-top: 1px solid #e4e6eb; margin-top: 10px;">
        <button style="background: none; border: none; cursor: pointer; font-size: 14px; color: #65676b;">
            👍 Like
        </button>
        <button style="background: none; border: none; cursor: pointer; font-size: 14px; color: #65676b;">
            💬 Comment
        </button>
        <button style="background: none; border: none; cursor: pointer; font-size: 14px; color: #65676b;">
            👥 Share
        </button>
    </div>
</div>