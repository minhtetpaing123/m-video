<!-- resources/views/components/post/media.blade.php -->
@props([
    'image' => null,
    'video' => null,
    'link' => null,
    'linkTitle' => null,
    'post' => null
])

@if($image)
    {{-- Image --}}
    <div class="mt-3 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 bg-black">
        <img src="{{ asset('storage/' . $image) }}" 
             alt="Post image" 
             class="w-full max-h-[480px] object-contain"
             loading="lazy"
             onerror="this.src='https://via.placeholder.com/600x400?text=Image+Not+Found'">
    </div>

@elseif($video || ($post && $post->video_cdn_url))
    {{-- Video (Bunny CDN) --}}
    @php
        $videoSource = $post->video_cdn_url ?? $video ?? null;
        $posterImage = $post->video_thumbnail_url ?? null;
    @endphp
    
    @if($videoSource)
        <div class="mt-3 rounded-xl overflow-hidden shadow-lg bg-black">
            <video 
                class="w-full max-h-[500px] object-contain"
                preload="metadata"
                playsinline
                controls
                poster="{{ $posterImage }}"
                style="background: #000; width: 100%; display: block;">
                <source src="{{ $videoSource }}" type="video/mp4">
                <p style="color: white; text-align: center; padding: 20px;">သင့်ဘရောက်ဆာက ဗီဒီယိုကို မပံ့ပိုးပါ။</p>
            </video>
        </div>
    @endif

@elseif($link)
    {{-- Link --}}
    <div class="mt-3 bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:bg-gray-100 transition">
        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="block p-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🔗</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-blue-600 hover:underline truncate">
                        {{ $linkTitle ?? $link }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">{{ $link }}</p>
                </div>
            </div>
        </a>
    </div>
@endif

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