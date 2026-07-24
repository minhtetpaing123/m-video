@php
    $isVideo = $this->post->video_cdn_url ? true : false;
    $isLink = $this->post->link ? true : false;
    $isImage = $this->post->image ? true : false;
    
    $title = $this->post->title ?? $this->post->content ?? 'Untitled';
    $creator = $this->post->user->name ?? 'Unknown';
    $time = $this->post->created_at->diffForHumans();
    
    $thumbnailUrl = $this->post->video_thumbnail_url ?? null;
    if (!$thumbnailUrl && $this->post->video_thumbnail) {
        $thumbnailUrl = Storage::url($this->post->video_thumbnail);
    }
    
    $delay = ($this->index % 4) * 100;
    
    // Category Emoji Mapping
    $categoryEmojis = [
        'action' => '⚔️',
        'comedy' => '😂',
        'romantic_comedy' => '❤️',
        'drama' => '🎭',
        'horror' => '👻',
        'dark_comedy' => '🖤',
        'crime_drama' => '🔫',
        'action_comedy' => '⚔️😂',
        'thriller' => '🔪',
        'documentary' => '📹',
        'animation' => '🎨',
        'family' => '👨‍👩‍👧‍👦',
        'music' => '🎵',
        'reality' => '📺',
        'sports' => '⚽',
        'talk_show' => '🎙️',
    ];
    $categoryEmoji = $categoryEmojis[$this->post->category] ?? '🎬';
    
    // Get current theme from session
    $theme = session()->get('theme', 'dark');
    $isDark = ($theme === 'dark');
    
    // Font size class based on session
    $fontSize = session()->get('font_size', 'medium');
    $titleSize = $fontSize === 'small' ? 'text-sm' : ($fontSize === 'large' ? 'text-lg' : 'text-base');
    $channelSize = $fontSize === 'small' ? 'text-[10px]' : ($fontSize === 'large' ? 'text-sm' : 'text-xs');
    $viewsSize = $fontSize === 'small' ? 'text-[9px]' : ($fontSize === 'large' ? 'text-xs' : 'text-[10px]');
    $badgeSize = $fontSize === 'small' ? 'text-[6px]' : ($fontSize === 'large' ? 'text-[10px]' : 'text-[8px]');
    $durationSize = $fontSize === 'small' ? 'text-[9px]' : ($fontSize === 'large' ? 'text-xs' : 'text-[10px]');
    $avatarSize = $fontSize === 'small' ? 'w-6 h-6 text-[9px]' : ($fontSize === 'large' ? 'w-10 h-10 text-sm' : 'w-8 h-8 text-[10px]');
@endphp

{{-- YouTube Style Card --}}
<div class="group cursor-pointer" 
     style="animation-delay: {{ $delay }}ms;" 
     onclick="window.location.href='{{ route('posts.show', $this->post->id) }}'">
    
    <div class="flex flex-col gap-1.5 sm:gap-2">
        
        {{-- Thumbnail --}}
        <div class="relative aspect-video bg-black rounded-lg sm:rounded-xl overflow-hidden">
            @if($isVideo && $thumbnailUrl)
                <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                
                {{-- Duration Badge --}}
                @if($this->post->video_duration)
                    <span class="absolute bottom-1.5 sm:bottom-2 right-1.5 sm:right-2 bg-black/90 text-white font-medium px-1.5 sm:px-2 py-0.5 rounded {{ $durationSize }}">
                        @php
                            $hours = floor($this->post->video_duration / 3600);
                            $minutes = floor(($this->post->video_duration % 3600) / 60);
                            $seconds = $this->post->video_duration % 60;
                        @endphp
                        @if($hours > 0)
                            {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                        @else
                            {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                        @endif
                    </span>
                @endif
                
                {{-- Category Badge with Emoji - Thumbnail ပေါ်မှာ --}}
                <div class="absolute top-1.5 sm:top-2 left-1.5 sm:left-2 flex gap-0.5 sm:gap-1 flex-wrap">
                    @if($this->post->category)
                        <span class="font-semibold px-1.5 sm:px-2 py-0.5 rounded-full bg-blue-500/90 text-white border border-blue-400/30 shadow-lg shadow-blue-500/30 {{ $badgeSize }}">
                            {{ $categoryEmoji }} {{ __($this->post->category) }}
                        </span>
                    @endif
                    @if($this->post->is_mature)
                        <span class="font-bold px-1 sm:px-1.5 py-0.5 rounded-full bg-red-600 text-white shadow-lg shadow-red-600/30 {{ $badgeSize }}">
                            🔞 18+
                        </span>
                    @endif
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                    <span class="text-3xl sm:text-4xl">🎬</span>
                </div>
            @endif
        </div>
        
        {{-- Content --}}
        <div class="flex gap-2 sm:gap-3">
            
            {{-- Channel Avatar --}}
            <div class="flex-shrink-0 mt-0.5 sm:mt-1">
                <div class="rounded-full bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white font-bold {{ $avatarSize }}">
                    {{ substr($creator, 0, 1) }}
                </div>
            </div>
            
            {{-- Video Info --}}
            <div class="flex-1 min-w-0">
                {{-- ✅ Title with Language Support --}}
                <h3 class="font-medium line-clamp-2 group-hover:text-blue-400 transition {{ $titleSize }}"
                    style="color: var(--text-primary);">
                    {{ __($title) }}
                </h3>
                
                {{-- ✅ Channel Name with Language Support --}}
                <p class="mt-0.5 transition {{ $channelSize }}" 
                   style="color: var(--text-secondary);">
                    {{ __($creator) }}
                </p>
                
                {{-- Views & Time --}}
                <div class="flex flex-wrap items-center gap-0.5 sm:gap-1 mt-0.5 {{ $viewsSize }}"
                     style="color: var(--text-muted);">
                    <span>{{ number_format($this->post->views_count ?? 0) }} {{ __('views') }}</span>
                    <span>•</span>
                    <span>{{ $time }}</span>
                </div>
            </div>
        </div>
    </div>
</div>