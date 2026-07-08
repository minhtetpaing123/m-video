<!-- resources/views/components/post/media.blade.php -->
@props([
    'image' => null,
    'video' => null,
    'link' => null,
    'linkTitle' => null,
    'post' => null
])

@if($image)
    <div class="mt-1 bg-gray-100 rounded-lg overflow-hidden">
        <img src="{{ Storage::url($image) }}" 
             alt="Post image" 
             class="w-full max-h-96 object-cover"
             loading="lazy">
    </div>
@elseif($video)
    <div class="mt-1 bg-black rounded-lg overflow-hidden relative">
        {{-- Video Thumbnail --}}
        <div class="video-thumbnail relative cursor-pointer" onclick="playVideo(this)">
            @php
                $videoPath = storage_path('app/public/'.$video);
                $videoUrl = Storage::url($video);
                $thumbnailUrl = $videoUrl . '?thumb=' . (file_exists($videoPath) ? filemtime($videoPath) : time());
            @endphp
            
            {{-- Video.js player for better control --}}
            <video 
                class="w-full max-h-96 video-player"
                preload="metadata"
                playsinline
                controls
                data-post-id="{{ $post->id ?? '' }}"
                poster="{{ $thumbnailUrl }}#t=0.1">
                <source src="{{ $videoUrl }}?v={{ file_exists($videoPath) ? filemtime($videoPath) : time() }}" 
                        type="video/mp4">
                Your browser does not support the video tag.
            </video>
            
            {{-- Play Button Overlay (hidden when video plays) --}}
            <div class="play-overlay absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 transition-opacity hover:bg-opacity-40">
                <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        {{-- Video Controls Info --}}
        <div class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
            <span class="video-duration">--:--</span>
        </div>
    </div>
@elseif($link)
    @php
        // Extract video ID from different platforms
        $videoId = '';
        $platform = '';
        
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'youtube';
        }
        // YouTube Shorts
        elseif (preg_match('/youtube\.com\/shorts\/([^"&?\/\s]{11})/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'youtube';
        }
        // TikTok
        elseif (preg_match('/tiktok\.com\/@[\w.-]+\/video\/(\d+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'tiktok';
        }
        // Facebook
        elseif (preg_match('/facebook\.com\/(?:watch\/?\?v=|[\w.-]+\/videos\/|video\.php\?v=)(\d+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'facebook';
        }
        // Instagram
        elseif (preg_match('/instagram\.com\/(?:p|reel)\/([^\/\?]+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'instagram';
        }
        // Vimeo
        elseif (preg_match('/vimeo\.com\/(\d+)/', $link, $match)) {
            $videoId = $match[1];
            $platform = 'vimeo';
        }
    @endphp

    @if($platform == 'youtube' && $videoId)
        {{-- YouTube Embed --}}
        <div class="mt-1 relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; border-radius: 0.5rem;">
            <iframe 
                src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&rel=0&modestbranding=1"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="absolute top-0 left-0 w-full h-full"
                loading="lazy">
            </iframe>
        </div>
    @elseif($platform == 'tiktok' && $videoId)
        {{-- TikTok Embed --}}
        <div class="mt-1 bg-black rounded-lg overflow-hidden">
            <blockquote class="tiktok-embed" cite="{{ $link }}" data-video-id="{{ $videoId }}" style="max-width: 100%;min-width: 100%;">
                <section></section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        </div>
    @elseif($platform == 'facebook' && $videoId)
        {{-- Facebook Embed --}}
        <div class="mt-1 bg-black rounded-lg overflow-hidden">
            <div class="fb-video" data-href="{{ $link }}" data-width="auto" data-show-text="false">
                <div class="fb-xfbml-parse-ignore"></div>
            </div>
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0"></script>
        </div>
    @elseif($platform == 'instagram' && $videoId)
        {{-- Instagram Embed --}}
        <div class="mt-1 bg-black rounded-lg overflow-hidden">
            <blockquote class="instagram-media" data-instgrm-permalink="{{ $link }}" data-instgrm-version="14" style="max-width: 100%;min-width: 100%;">
            </blockquote>
            <script async src="//www.instagram.com/embed.js"></script>
        </div>
    @elseif($platform == 'vimeo' && $videoId)
        {{-- Vimeo Embed --}}
        <div class="mt-1 relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; border-radius: 0.5rem;">
            <iframe 
                src="https://player.vimeo.com/video/{{ $videoId }}?api=1"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                class="absolute top-0 left-0 w-full h-full"
                loading="lazy">
            </iframe>
        </div>
    @else
        {{-- Fallback: Show as normal link --}}
        <div class="mt-1 bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:shadow-md transition">
            <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="block">
                <div class="flex items-start p-4">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-3xl">🔗</span>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-semibold text-gray-800 line-clamp-2">
                            {{ $linkTitle ?? parse_url($link, PHP_URL_HOST) }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ parse_url($link, PHP_URL_HOST) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-2 truncate">{{ $link }}</p>
                    </div>
                </div>
            </a>
        </div>
    @endif
@endif

<script>
// Video play function
function playVideo(thumbnail) {
    const container = thumbnail.closest('.mt-1');
    const video = container.querySelector('video');
    const overlay = container.querySelector('.play-overlay');
    
    if (video) {
        video.play();
        if (overlay) overlay.style.display = 'none';
    }
}

// Auto pause other videos when one plays
document.addEventListener('play', function(e) {
    if (e.target.tagName === 'VIDEO') {
        document.querySelectorAll('video').forEach(video => {
            if (video !== e.target && !video.paused) {
                video.pause();
            }
        });
    }
}, true);

// Update video duration
document.querySelectorAll('video').forEach(video => {
    video.addEventListener('loadedmetadata', function() {
        const duration = Math.floor(this.duration);
        const minutes = Math.floor(duration / 60);
        const seconds = duration % 60;
        const durationSpan = this.closest('.mt-1')?.querySelector('.video-duration');
        if (durationSpan) {
            durationSpan.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
    });
    
    video.addEventListener('pause', function() {
        const overlay = this.closest('.mt-1')?.querySelector('.play-overlay');
        if (overlay && this.currentTime < this.duration) {
            overlay.style.display = 'flex';
        }
    });
    
    video.addEventListener('play', function() {
        const overlay = this.closest('.mt-1')?.querySelector('.play-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    });
});
</script>