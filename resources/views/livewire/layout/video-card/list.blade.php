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
@endphp

<div class="group cursor-pointer rounded-xl overflow-hidden transition-all duration-300 border shadow-sm hover:shadow-md"
     style="background: var(--bg-card); border-color: var(--border-color);"
     onclick="window.location.href='{{ route('posts.show', $this->post->id) }}'">
    
    {{-- Mobile: Column (အပေါ်-အောက်), Desktop: Row (ဘယ်-ညာ) --}}
    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 p-2 sm:p-3">
        
        {{-- Thumbnail (Full width on mobile, fixed width on desktop) --}}
        <div class="relative w-full sm:w-40 md:w-48 lg:w-56 xl:w-64 flex-shrink-0 aspect-video bg-black rounded-lg overflow-hidden">
            @if($isVideo && $thumbnailUrl)
                <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                
                @if($this->post->video_duration)
                    <span class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] sm:text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded">
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
            
            {{-- Category Badge with Emoji - Thumbnail ပေါ်မှာ --}}
            <div class="absolute top-1 sm:top-2 left-1 sm:left-2 flex gap-0.5 sm:gap-1 flex-wrap">
                @if($this->post->category)
                    <span class="text-[8px] sm:text-[10px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full bg-blue-500/90 text-white border border-blue-400/30 shadow-lg shadow-blue-500/30">
                        {{ $categoryEmoji }} {{ __($this->post->category) }}
                    </span>
                @endif
                @if($this->post->is_mature)
                    <span class="text-[7px] sm:text-[9px] font-bold px-1 sm:px-1.5 py-0.5 rounded-full bg-red-600 text-white shadow-lg shadow-red-600/30">
                        🔞 18+
                    </span>
                @endif
            </div>
        </div>
        
        {{-- Content (Right) --}}
        <div class="flex-1 min-w-0 py-0.5 sm:py-1">
            
            {{-- Title --}}
            <h3 class="font-semibold text-sm sm:text-base md:text-lg line-clamp-2 group-hover:text-blue-400 transition"
                style="color: var(--text-primary);">
                {{ $title }}
            </h3>
            
            {{-- Creator, Views, Time --}}
            <div class="flex flex-wrap items-center gap-1 sm:gap-2 text-[11px] sm:text-sm mt-1 sm:mt-2"
                 style="color: var(--text-secondary);">
                <span class="font-medium truncate max-w-[100px] sm:max-w-none" style="color: var(--text-primary);">{{ $creator }}</span>
                <span class="hidden xs:inline">•</span>
                <span class="truncate">{{ number_format($this->post->views_count ?? 0) }} {{ __('views') }}</span>
                <span class="hidden xs:inline">•</span>
                <span class="truncate">{{ $time }}</span>
            </div>
            
            {{-- Content Preview --}}
            @if($this->post->content)
                <p class="text-[11px] sm:text-sm mt-0.5 sm:mt-1 line-clamp-2" style="color: var(--text-muted);">
                    {{ Str::limit(strip_tags($this->post->content), 80) }}
                </p>
            @endif
        </div>
    </div>
</div>