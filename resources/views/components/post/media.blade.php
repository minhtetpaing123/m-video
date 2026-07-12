<!-- resources/views/components/post/media.blade.php -->
@props([
    'image' => null,
    'video' => null,
    'link' => null,
    'linkTitle' => null,
    'post' => null
])

@if($image)
    {{-- Image Display --}}
    <div class="mt-3 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
        <img src="{{ Storage::url($image) }}" 
             alt="Post image" 
             class="w-full max-h-[480px] object-cover"
             loading="lazy">
    </div>

@elseif($video)
    {{-- Video Display with Streaming Support --}}
    @php
        // Clean video path for streaming
        $videoPath = $video;
        if (str_starts_with($videoPath, 'public/')) {
            $videoPath = substr($videoPath, 7);
        }
        $videoPath = ltrim($videoPath, '/');
        
        // Generate streaming URL using route
        $videoUrl = route('video.stream', ['path' => $videoPath]);
    @endphp

    <div class="mt-3 rounded-xl overflow-hidden shadow-lg bg-black">
        <video 
            class="w-full max-h-[500px] object-contain"
            preload="metadata"
            playsinline
            controls
            style="background: #000; width: 100%; display: block;">
            <source src="{{ $videoUrl }}" type="video/mp4">
            <p style="color: white; text-align: center; padding: 20px;">သင့်ဘရောက်ဆာက ဗီဒီယိုကို မပံ့ပိုးပါ။</p>
        </video>
    </div>

@elseif($link)
    {{-- Link/External Video Display --}}
    @php
        $videoId = '';
        $platform = '';
        $embedUrl = '';
        $platformIcon = '🔗';
        
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'youtube';
            $embedUrl = 'https://www.youtube.com/embed/' . $videoId . '?enablejsapi=1&rel=0&modestbranding=1&autoplay=0';
            $platformIcon = '▶️';
        }
        // YouTube Shorts
        elseif (preg_match('/youtube\.com\/shorts\/([^"&?\/\s]{11})/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'youtube';
            $embedUrl = 'https://www.youtube.com/embed/' . $videoId . '?enablejsapi=1&rel=0&modestbranding=1&autoplay=0';
            $platformIcon = '▶️';
        }
        // TikTok
        elseif (preg_match('/tiktok\.com\/@[\w.-]+\/video\/(\d+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'tiktok';
            $platformIcon = '🎵';
        }
        // Facebook
        elseif (preg_match('/facebook\.com\/(?:watch\/?\?v=|[\w.-]+\/videos\/|video\.php\?v=)(\d+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'facebook';
            $platformIcon = '👥';
        }
        // Instagram
        elseif (preg_match('/instagram\.com\/(?:p|reel)\/([^\/\?]+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'instagram';
            $platformIcon = '📸';
        }
        // Vimeo
        elseif (preg_match('/vimeo\.com\/(\d+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'vimeo';
            $embedUrl = 'https://player.vimeo.com/video/' . $videoId . '?api=1&autoplay=0';
            $platformIcon = '🎥';
        }
    @endphp

    @if($platform == 'youtube' && $videoId)
        {{-- YouTube Embed --}}
        <div class="mt-3 relative rounded-xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%; height: 0; background: #0a0a0a;">
            <iframe 
                src="{{ $embedUrl }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                class="absolute top-0 left-0 w-full h-full"
                loading="lazy">
            </iframe>
        </div>
        
    @elseif($platform == 'tiktok' && $videoId)
        {{-- TikTok Embed --}}
        <div class="mt-3 bg-black rounded-xl overflow-hidden shadow-lg">
            <blockquote class="tiktok-embed" 
                        cite="{{ $link }}" 
                        data-video-id="{{ $videoId }}" 
                        style="max-width: 100%; min-width: 100%; margin: 0;">
                <section></section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        </div>
        
    @elseif($platform == 'facebook' && $videoId)
        {{-- Facebook Embed --}}
        <div class="mt-3 bg-black rounded-xl overflow-hidden shadow-lg">
            <div id="fb-root"></div>
            <div class="fb-video" 
                 data-href="{{ $link }}" 
                 data-width="auto" 
                 data-show-text="false"
                 data-allowfullscreen="true">
                <div class="fb-xfbml-parse-ignore"></div>
            </div>
            <script async defer crossorigin="anonymous" 
                    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0">
            </script>
        </div>
        
    @elseif($platform == 'instagram' && $videoId)
        {{-- Instagram Embed --}}
        <div class="mt-3 bg-black rounded-xl overflow-hidden shadow-lg">
            <blockquote class="instagram-media" 
                        data-instgrm-permalink="{{ $link }}" 
                        data-instgrm-version="14" 
                        style="max-width: 100%; min-width: 100%; margin: 0;">
            </blockquote>
            <script async src="//www.instagram.com/embed.js"></script>
        </div>
        
    @elseif($platform == 'vimeo' && $videoId)
        {{-- Vimeo Embed --}}
        <div class="mt-3 relative rounded-xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%; height: 0; background: #0a0a0a;">
            <iframe 
                src="{{ $embedUrl }}"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                class="absolute top-0 left-0 w-full h-full"
                loading="lazy">
            </iframe>
        </div>
        
    @else
        {{-- Fallback: Show as normal link card --}}
        <div class="mt-3 bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 hover:border-gray-500 transition-all duration-300 shadow-lg hover:shadow-xl">
            <a href="{{ $link }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="block">
                <div class="flex items-start p-5">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg text-4xl">
                        {{ $platformIcon }}
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <h4 class="font-semibold text-white line-clamp-2">
                            {{ $linkTitle ?? parse_url($link, PHP_URL_HOST) }}
                        </h4>
                        <p class="text-sm text-gray-400 mt-1 truncate">
                            {{ parse_url($link, PHP_URL_HOST) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2 truncate">{{ $link }}</p>
                    </div>
                    <div class="ml-2 flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    @endif
@endif

{{-- JavaScript to auto-pause other videos --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('video');
    
    videos.forEach(function(video) {
        video.addEventListener('play', function() {
            videos.forEach(function(otherVideo) {
                if (otherVideo !== video && !otherVideo.paused) {
                    otherVideo.pause();
                }
            });
        });
    });
});
</script>