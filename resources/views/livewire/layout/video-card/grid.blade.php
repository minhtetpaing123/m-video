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
@endphp

<div class="group cursor-pointer animate-fade-in-up" 
     style="animation-delay: {{ $delay }}ms;" 
     onclick="window.location.href='{{ route('posts.show', $this->post->id) }}'">
    
    <div class="relative rounded-2xl overflow-hidden border-2 transition-all duration-500 ease-out hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/20"
         style="background: var(--bg-card); border-color: var(--border-color);">
        
        <div class="relative aspect-video bg-black overflow-hidden">
            @if($isVideo && $thumbnailUrl)
                <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                    <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border-2 border-white/30">
                        <svg class="w-7 h-7 text-white fill-current ml-1" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
                
                @if($this->post->video_duration)
                    <span class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-md text-white text-xs font-medium px-2 py-1 rounded-lg border border-white/10">
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
                    <span class="text-3xl">🎬</span>
                </div>
            @endif
            
            <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
                @if($this->post->category)
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-500/90 text-white">
                        {{ __($this->post->category) }}
                    </span>
                @endif
                @if($this->post->is_mature)
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-red-600 text-white">
                        🔞 18+
                    </span>
                @endif
            </div>
        </div>
        
        <div class="p-3">
            <div class="flex gap-2">
                <div class="flex-shrink-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-bold">
                        {{ substr($creator, 0, 1) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm sm:text-base font-bold line-clamp-2 group-hover:text-blue-500 transition" style="color: var(--text-primary);">
                        {{ $title }}
                    </h3>
                    <div class="flex items-center gap-1.5 flex-wrap mt-1 text-xs text-gray-400">
                        <span>{{ $creator }}</span>
                        <span>•</span>
                        <span>{{ number_format($this->post->views_count ?? 0) }} {{ __('views') }}</span>
                        <span>•</span>
                        <span>{{ $time }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>