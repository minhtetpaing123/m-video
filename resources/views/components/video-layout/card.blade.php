{{-- resources/views/components/video-layout/card.blade.php --}}
@props([
    'post',
    'index' => 0,
    'type' => 'guest',
])

@php
    $isVideo = $post->video_cdn_url ? true : false;
    $isLink = $post->link ? true : false;
    $isImage = $post->image ? true : false;
    
    $title = $post->title ?? $post->content ?? 'Untitled';
    $creator = $post->user->name ?? 'Unknown';
    $time = $post->created_at->diffForHumans();
    
    $linkDomain = $post->link ? parse_url($post->link, PHP_URL_HOST) : null;
    if ($linkDomain) {
        $linkDomain = str_replace('www.', '', $linkDomain);
        $linkDomain = str_replace('m.', '', $linkDomain);
    }
    $linkTitle = $post->link_title ?? 'External Link';
    
    $thumbnailUrl = $post->video_thumbnail_url ?? null;
    if (!$thumbnailUrl && $post->video_thumbnail) {
        $thumbnailUrl = Storage::url($post->video_thumbnail);
    }
    
    $showActions = ($type === 'auth');
    
    // Animation delay based on index
    $delay = ($index % 4) * 100;
@endphp

<div class="group cursor-pointer animate-fade-in-up" style="animation-delay: {{ $delay }}ms;" onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
    
    {{-- ============================================ --}}
    {{-- CARD --}}
    {{-- ============================================ --}}
    <div class="relative rounded-2xl overflow-hidden border-2 transition-all duration-500 ease-out hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/20 dark:hover:shadow-blue-500/30"
         style="background: var(--bg-card); border-color: var(--border-color);">
        
        {{-- Animated Gradient Border (Hover) --}}
        <div class="absolute inset-0 rounded-2xl p-[2px] bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10"></div>
        
        {{-- ============================================ --}}
        {{-- THUMBNAIL --}}
        {{-- ============================================ --}}
        <div class="relative aspect-video bg-black overflow-hidden">
            
            @if($isVideo)
                @if($thumbnailUrl)
                    <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 shadow-[inset_0_0_80px_rgba(59,130,246,0.2)] group-hover:shadow-[inset_0_0_80px_rgba(59,130,246,0.5)] transition-shadow duration-500"></div>
                    
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 scale-50 group-hover:scale-100">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border-2 border-white/30 shadow-2xl">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white fill-current ml-1" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                    
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-purple-600 to-blue-500">
                        <span class="text-3xl sm:text-4xl mb-1 sm:mb-2">🎬</span>
                        <span class="text-white/30 text-[10px] sm:text-xs font-bold tracking-wider">M-VIDEO</span>
                    </div>
                @endif
                
                {{-- Duration Badge --}}
                @if($post->video_duration)
                    <span class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-md text-white text-[10px] sm:text-xs font-medium px-2 py-1 rounded-lg border border-white/10 shadow-lg shadow-black/30 transition-all duration-300 group-hover:scale-105 group-hover:bg-black/80">
                        @php
                            $hours = floor($post->video_duration / 3600);
                            $minutes = floor(($post->video_duration % 3600) / 60);
                            $seconds = $post->video_duration % 60;
                        @endphp
                        @if($hours > 0)
                            {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                        @else
                            {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                        @endif
                    </span>
                @endif
                
            @elseif($isLink)
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
                        ];
                        $icon = '🔗';
                        foreach ($platformIcons as $domain => $platformIcon) {
                            if (str_contains($linkDomain ?? '', $domain)) {
                                $icon = $platformIcon;
                                break;
                            }
                        }
                    @endphp
                    <span class="text-4xl sm:text-5xl mb-1 sm:mb-2">{{ $icon }}</span>
                    <span class="text-white/30 text-[9px] sm:text-xs font-bold tracking-widest uppercase">M-VIDEO</span>
                    <span class="text-gray-400 text-[10px] sm:text-xs mt-1.5 sm:mt-2 px-2 truncate max-w-full">{{ $linkTitle }}</span>
                </div>
                
            @elseif($isImage)
                <img src="{{ $post->image_url }}" alt="{{ $title }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            @endif
            
            {{-- ============================================ --}}
            {{-- BADGES - Thumbnail ပေါ်မှာ --}}
            {{-- ============================================ --}}
            <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
                
                @if($post->category)
                    <span class="text-[10px] sm:text-xs font-semibold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full bg-blue-500/90 text-white border border-blue-400/30 shadow-lg shadow-blue-500/30">
                        {{ $post->category_label }}
                    </span>
                @endif
                
                @if($post->is_mature)
                    <span class="text-[9px] sm:text-[11px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-red-600 text-white shadow-lg shadow-red-600/30">
                        🔞 {{ __('18+') }}
                    </span>
                @endif
                
                @if($isLink)
                    <span class="text-[9px] sm:text-[11px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-green-600 text-white shadow-lg shadow-green-600/30">
                        🔗 {{ __('Link') }}
                    </span>
                @endif
            </div>
        </div>
        
        {{-- ============================================ --}}
        {{-- CONTENT --}}
        {{-- ============================================ --}}
        <div class="p-3 sm:p-4">
            <div class="flex gap-2 sm:gap-3">
                
                {{-- Channel Avatar --}}
                <div class="flex-shrink-0">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm sm:text-base font-bold ring-2 ring-purple-500/30 group-hover:ring-purple-500/70 transition-all duration-300 shadow-lg shadow-purple-500/30 group-hover:shadow-purple-500/50 group-hover:scale-110 group-hover:rotate-12">
                        {{ substr($creator, 0, 1) }}
                    </div>
                </div>
                
                {{-- Video Info --}}
                <div class="flex-1 min-w-0">
                    
                    {{-- Title --}}
                    <div class="relative inline-block">
                        <div class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 group-hover:w-full transition-all duration-700 ease-out"></div>
                        <span class="inline-block text-base sm:text-lg md:text-xl font-bold line-clamp-2 leading-snug group-hover:text-blue-500 transition-colors duration-300 pb-1"
                              style="color: var(--text-primary);">
                            {{ $title }}
                        </span>
                    </div>
                    
                    {{-- ============================================ --}}
                    {{-- USERNAME + VIEWS + TIME (ဘေးချင်းကပ်) --}}
                    {{-- ============================================ --}}
                    <div class="flex items-center gap-1.5 flex-wrap mt-1">
                        
                        {{-- Username Badge --}}
                        <span class="inline-flex items-center gap-1 text-xs sm:text-sm font-medium px-2 py-0.5 rounded-full border transition-all duration-300 group-hover:scale-105"
                              style="background: var(--bg-card-hover); color: var(--text-secondary); border-color: var(--border-color);">
                            <span class="text-[10px]">👤</span>
                            {{ $creator }}
                        </span>
                        
                        <span class="text-xs text-gray-400 dark:text-gray-500">•</span>
                        
                        {{-- Views Badge --}}
                        <span class="inline-flex items-center gap-1 text-xs sm:text-sm font-medium px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-500 dark:text-blue-400 border border-blue-500/20 transition-all duration-300 group-hover:bg-blue-500/20">
                            <span class="text-[10px]">👁️</span>
                            {{ number_format($post->views_count ?? 0) }} {{ __('views') }}
                        </span>
                        
                        <span class="text-xs text-gray-400 dark:text-gray-500">•</span>
                        
                        {{-- Time Badge --}}
                        <span class="inline-flex items-center gap-1 text-xs sm:text-sm font-medium px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-500 dark:text-purple-400 border border-purple-500/20 transition-all duration-300 group-hover:bg-purple-500/20">
                            <span class="text-[10px]">⏱️</span>
                            {{ $time }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================ */
/* ANIMATIONS */
/* ============================================ */

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

/* ============================================ */
/* RESPONSIVE */
/* ============================================ */
@media (max-width: 640px) {
    .rounded-2xl {
        border-radius: 12px;
    }
}

/* ============================================ */
/* HOVER SHADOW */
/* ============================================ */
.group:hover .shadow-2xl {
    box-shadow: 0 20px 50px -12px rgba(59, 130, 246, 0.3);
}

html.dark .group:hover .shadow-2xl {
    box-shadow: 0 20px 50px -12px rgba(59, 130, 246, 0.4);
}

/* ============================================ */
/* BADGE ANIMATIONS */
/* ============================================ */
.group:hover .inline-flex.items-center {
    transition: all 0.3s ease;
}

.group:hover .bg-blue-500\/10 {
    background: rgba(59, 130, 246, 0.2);
}

.group:hover .bg-purple-500\/10 {
    background: rgba(168, 85, 247, 0.2);
}

/* ============================================ */
/* RESPONSIVE FLEX WRAP */
/* ============================================ */
@media (max-width: 360px) {
    .flex-wrap {
        gap: 2px;
    }
    .flex-wrap span {
        font-size: 10px;
        padding: 0 4px;
    }
}
</style>