{{-- resources/views/components/guest/video-card.blade.php --}}
@props(['post', 'index' => 0])

@php
    $isVideo = $post->video ? true : false;
    $isLink = $post->link ? true : false;
    $isImage = $post->image ? true : false;
    
    $title = $post->title ?? $post->content ?? 'Untitled';
    $creator = $post->user->name ?? 'Unknown';
    $time = $post->created_at->diffForHumans();
    
    // Get link domain (ဒီမှာ domain ကိုမပြတော့ဘူး)
    $linkDomain = $post->link ? parse_url($post->link, PHP_URL_HOST) : null;
    if ($linkDomain) {
        $linkDomain = str_replace('www.', '', $linkDomain);
        $linkDomain = str_replace('m.', '', $linkDomain);
    }
    
    // Link Title
    $linkTitle = $post->link_title ?? 'External Link';
@endphp

<div class="video-card bg-gray-900 rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" 
     onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
    
    {{-- Thumbnail --}}
    <div class="relative aspect-video bg-gray-900">
        
        @if($isVideo)
            {{-- Video --}}
            @if($post->video_thumbnail)
                <img src="{{ Storage::url($post->video_thumbnail) }}" alt="{{ $title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-600 to-blue-500">
                    <div class="text-center">
                        <div class="text-4xl mb-2">🎬</div>
                        <div class="text-white/50 text-sm font-bold tracking-wider">M-VIDEO</div>
                    </div>
                </div>
            @endif
            
        @elseif($isLink)
            {{-- Link --}}
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900 p-4">
                @php
                    $platformIcons = [
                        'youtube.com' => '▶️',
                        'youtu.be' => '▶️',
                        'vimeo.com' => '🎥',
                        'tiktok.com' => '🎵',
                        'instagram.com' => '📸',
                        'facebook.com' => '👥',
                        'twitter.com' => '🐦',
                        'x.com' => '🐦',
                        'mediafire.com' => '📁',
                    ];
                    $icon = '🔗';
                    foreach ($platformIcons as $domain => $platformIcon) {
                        if (str_contains($linkDomain ?? '', $domain)) {
                            $icon = $platformIcon;
                            break;
                        }
                    }
                @endphp
                <div class="text-6xl mb-3">{{ $icon }}</div>
                <div class="text-white/40 text-xs font-bold tracking-widest uppercase">M-VIDEO</div>
                <div class="text-gray-500 text-xs mt-2 truncate max-w-full px-4">
                    {{ $linkTitle }}
                </div>
            </div>
            
        @elseif($isImage)
            {{-- Image --}}
            <img src="{{ Storage::url($post->image) }}" alt="{{ $title }}" class="w-full h-full object-cover">
        @endif
        
        {{-- Badges --}}
        <div style="position: absolute; top: 8px; left: 8px; display: flex; gap: 6px; flex-wrap: wrap; z-index: 5;">
            @if($post->category)
                <span style="background: rgba(45, 136, 255, 0.9); color: white; font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 12px;">
                    {{ $post->category_label }}
                </span>
            @endif
            
            @if($post->is_mature)
                <span style="background: rgba(231, 76, 60, 0.9); color: white; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">
                    🔞 18+
                </span>
            @endif
            
            @if($isLink)
                <span style="background: rgba(46, 204, 113, 0.9); color: white; font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 12px;">
                    🔗 Link
                </span>
            @endif
        </div>
    </div>
    
    {{-- Content --}}
    <div class="p-3">
        <div class="flex items-start gap-2">
            <div class="flex-shrink-0 mt-1">
                @if($post->user->avatar)
                    <img src="{{ Storage::url($post->user->avatar) }}" alt="{{ $creator }}" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold bg-gradient-to-br from-purple-500 to-pink-500">
                        {{ substr($creator, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-white text-sm font-semibold truncate">{{ $creator }}</h4>
                <span class="text-gray-400 text-xs">{{ $time }}</span>
            </div>
        </div>
        
        {{-- Title --}}
        <h3 class="text-white text-sm font-medium mt-1 line-clamp-2">
            {{ $title }}
        </h3>
        
        {{-- Link Title (Domain အစား) --}}
        @if($isLink)
            <p class="text-gray-500 text-xs mt-1 truncate">
                {{ $linkTitle }}
            </p>
        @endif
        
        {{-- Stats --}}
        <div class="flex items-center gap-3 text-gray-400 text-xs mt-2">
            <span>{{ number_format($post->views_count ?? 0) }} views</span>
            <span>{{ number_format($post->likes_count ?? 0) }} likes</span>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.video-card {
    transition: all 0.3s ease;
}

.video-card:hover {
    transform: translateY(-4px);
}
</style>