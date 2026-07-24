@props([
    'videoCdnUrl' => null,
    'videoLocalUrl' => null,
    'thumbnailUrl' => null,
    'postId' => null,
    'autoplay' => false,
    'muted' => false,
    'loop' => false,
    'controls' => true,
    'maxHeight' => '70vh',
    'minHeight' => '200px',
    'showDuration' => true,
    'showPlayOverlay' => true,
])

@php
    $videoSource = $videoCdnUrl ?? $videoLocalUrl;
    $videoId = 'video-' . ($postId ?? uniqid());
@endphp

@if($videoSource)
    <div class="w-full bg-black relative group video-container" data-video-id="{{ $videoId }}">
        {{-- Video Element --}}
        <video 
            id="{{ $videoId }}"
            class="w-full h-auto video-player"
            style="display: block; max-height: {{ $maxHeight }}; min-height: {{ $minHeight }};"
            preload="metadata"
            playsinline
            {{ $controls ? 'controls' : '' }}
            {{ $autoplay ? 'autoplay' : '' }}
            {{ $muted ? 'muted' : '' }}
            {{ $loop ? 'loop' : '' }}
            poster="{{ $thumbnailUrl }}"
            controlsList="nodownload"
            disablePictureInPicture>
            
            <source src="{{ $videoSource }}" type="video/mp4">
            
            <p style="color: white; text-align: center; padding: 20px;">
                သင့်ဘရောက်ဆာက ဗီဒီယိုကို မပံ့ပိုးပါ။
            </p>
        </video>

        {{-- Loading Spinner --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none video-loading hidden">
            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        </div>

        {{-- Play Button Overlay --}}
        @if($showPlayOverlay)
            <button class="absolute inset-0 w-full h-full flex items-center justify-center play-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/20" 
                    onclick="document.getElementById('{{ $videoId }}')?.play?.()"
                    aria-label="Play video">
                <svg class="w-16 h-16 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
        @endif

        {{-- Video Info Badge --}}
        @if($showDuration)
            <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded-md">
                <span class="video-duration">0:00</span>
            </div>
        @endif

        {{-- Video Progress Bar (Custom) --}}
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20 group-hover:h-1.5 transition-all duration-200">
            <div class="h-full bg-blue-500 video-progress-bar" style="width: 0%;"></div>
        </div>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-video-id="{{ $videoId }}"]');
    if (!container) return;

    const video = document.getElementById('{{ $videoId }}');
    if (!video) return;

    const loading = container.querySelector('.video-loading');
    const playOverlay = container.querySelector('.play-overlay');
    const durationDisplay = container.querySelector('.video-duration');
    const progressBar = container.querySelector('.video-progress-bar');

    // Show loading while video is loading
    video.addEventListener('loadstart', function() {
        loading?.classList.remove('hidden');
    });

    video.addEventListener('canplaythrough', function() {
        loading?.classList.add('hidden');
    });

    // Error handling
    video.addEventListener('error', function(e) {
        loading?.classList.add('hidden');
        console.error('Video error:', e);
        
        container.innerHTML = `
            <div class="flex items-center justify-center bg-gray-900 text-white p-8 text-center" 
                 style="min-height: {{ $minHeight }}; max-height: {{ $maxHeight }};">
                <div>
                    <svg class="w-12 h-12 mx-auto mb-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p>Video ဖွင့်လို့မရပါ</p>
                    <p class="text-xs text-gray-400 mt-1">ဗီဒီယိုကို နောက်မှထပ်ကြည့်ပါ</p>
                </div>
            </div>
        `;
    });

    // Update duration
    video.addEventListener('loadedmetadata', function() {
        if (durationDisplay) {
            const minutes = Math.floor(video.duration / 60);
            const seconds = Math.floor(video.duration % 60);
            durationDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
    });

    // Update progress bar
    video.addEventListener('timeupdate', function() {
        if (progressBar && video.duration) {
            const percent = (video.currentTime / video.duration) * 100;
            progressBar.style.width = `${percent}%`;
        }
        
        if (durationDisplay) {
            const minutes = Math.floor(video.currentTime / 60);
            const seconds = Math.floor(video.currentTime % 60);
            durationDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
    });

    // Play/pause overlay
    video.addEventListener('play', function() {
        playOverlay?.classList.add('opacity-0');
    });

    video.addEventListener('pause', function() {
        if (video.currentTime > 0) {
            playOverlay?.classList.remove('opacity-0');
        }
    });

    // Click on video to toggle
    video.addEventListener('click', function(e) {
        if (e.target === video) {
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        }
    });

    // Keyboard shortcuts
    video.addEventListener('keydown', function(e) {
        if (e.key === ' ' || e.key === 'Space') {
            e.preventDefault();
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        }
    });

    // Autoplay check
    if ({{ $autoplay ? 'true' : 'false' }}) {
        video.play().catch(function(error) {
            console.log('Autoplay prevented:', error);
        });
    }
});
</script>
@endpush

<style>
.video-container {
    position: relative;
    background: #000;
    overflow: hidden;
}

.video-container video {
    width: 100%;
    height: auto;
    background: #000;
}

.video-container .play-overlay {
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.video-container .play-overlay:hover {
    opacity: 1 !important;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.video-progress-bar {
    transition: width 0.3s ease;
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .video-container video {
        min-height: 180px;
    }
    
    .video-container .play-overlay svg {
        width: 48px;
        height: 48px;
    }
}

@media (min-width: 641px) {
    .video-container video {
        min-height: 250px;
    }
}

@media (prefers-color-scheme: dark) {
    .video-container .video-duration {
        background: rgba(0, 0, 0, 0.8);
    }
}
</style>