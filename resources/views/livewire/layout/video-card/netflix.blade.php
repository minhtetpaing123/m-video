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
    
    // Font size class based on session
    $fontSize = session()->get('font_size', 'medium');
    $titleSize = $fontSize === 'small' ? 'text-[10px]' : ($fontSize === 'large' ? 'text-sm' : 'text-xs');
    $metaSize = $fontSize === 'small' ? 'text-[8px]' : ($fontSize === 'large' ? 'text-[11px]' : 'text-[10px]');
    $badgeSize = $fontSize === 'small' ? 'text-[7px]' : ($fontSize === 'large' ? 'text-[10px]' : 'text-[9px]');
    $durationSize = $fontSize === 'small' ? 'text-[8px]' : ($fontSize === 'large' ? 'text-[11px]' : 'text-[10px]');
    $playIconSize = $fontSize === 'small' ? 'w-9 h-9' : ($fontSize === 'large' ? 'w-14 h-14' : 'w-11 h-11');
    $playSvgSize = $fontSize === 'small' ? 'w-4 h-4' : ($fontSize === 'large' ? 'w-7 h-7' : 'w-5 h-5');
@endphp

<div class="group cursor-pointer rounded-lg overflow-hidden transition-all duration-300 ease-out 
            group-hover:scale-105 group-hover:shadow-2xl group-hover:shadow-blue-500/30 
            group-hover:z-10"
     style="animation-delay: {{ $delay }}ms; background: var(--bg-card);"
     onclick="window.location.href='{{ route('posts.show', $this->post->id) }}'">
    
    <div class="relative aspect-video bg-black overflow-hidden">
        @if($isVideo && $thumbnailUrl)
            <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            
            {{-- Hover Play Icon --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 bg-black/30">
                <div class="rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border-2 border-white/30 {{ $playIconSize }}">
                    <svg class="text-white fill-current ml-0.5 {{ $playSvgSize }}" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </div>
            
            {{-- Badges --}}
            <div class="absolute top-1.5 sm:top-2 left-1.5 sm:left-2 flex gap-0.5 sm:gap-1 flex-wrap">
                @if($this->post->category)
                    <span class="font-semibold px-1.5 sm:px-2 py-0.5 rounded bg-blue-500/90 text-white border border-blue-400/30 shadow-lg shadow-blue-500/30 {{ $badgeSize }}">
                        {{ $categoryEmoji }} {{ __($this->post->category) }}
                    </span>
                @endif
                @if($this->post->is_mature)
                    <span class="font-bold px-1 sm:px-1.5 py-0.5 rounded bg-red-600 text-white shadow-lg shadow-red-600/30 {{ $badgeSize }}">
                        🔞 18+
                    </span>
                @endif
            </div>
            
            {{-- Duration Badge --}}
            @if($this->post->video_duration)
                <span class="absolute bottom-1.5 sm:bottom-2 right-1.5 sm:right-2 bg-black/80 text-white font-medium px-1.5 sm:px-2 py-0.5 rounded {{ $durationSize }}">
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
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-900">
                <span class="text-2xl sm:text-3xl">🎬</span>
            </div>
        @endif
    </div>
    
    {{-- Content --}}
    <div class="p-1.5 sm:p-2">
        {{-- Title --}}
        <h3 class="font-medium truncate group-hover:text-blue-400 transition {{ $titleSize }}"
            style="color: var(--text-primary);">
            {{ $title }}
        </h3>
        
        {{-- Username, Views, Time (ဘေးချင်းကပ်) --}}
        <div class="flex items-center gap-0.5 sm:gap-1 mt-0.5 flex-wrap {{ $metaSize }}"
             style="color: var(--text-muted);">
            {{-- Username --}}
            <span class="truncate max-w-[60px] sm:max-w-[80px]">{{ $creator }}</span>
            <span>•</span>
            {{-- Views --}}
            <span>{{ number_format($this->post->views_count ?? 0) }} {{ __('views') }}</span>
            <span>•</span>
            {{-- Time --}}
            <span>{{ $time }}</span>
        </div>
    </div>
</div>