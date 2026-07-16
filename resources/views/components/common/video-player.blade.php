{{-- resources/views/components/common/video-player.blade.php --}}
@props([
    'src' => null, 
    'src_1080' => null, 
    'src_720' => null, 
    'src_480' => null, 
    'src_360' => null, 
    'src_240' => null, 
    'src_144' => null, 
    'subtitle' => null, 
    'poster' => null,
    'autoplay' => false,
    'class' => '',
])

@php
    $getStreamUrl = function($path) {
        if (!$path) return '';
        
        // Bunny CDN URL - တိုက်ရိုက်ပြန်ပေး
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Local storage fallback
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        $path = ltrim($path, '/');
        return route('video.stream', ['path' => $path]);
    };

    // Get actual URLs - Bunny CDN ကို အရင်ဦးစားပေး
    $url1080 = $getStreamUrl($src_1080 ?? $src);
    $url720 = $getStreamUrl($src_720 ?? $src);
    $url480 = $getStreamUrl($src_480);
    $url360 = $getStreamUrl($src_360);
    $url240 = $getStreamUrl($src_240);
    $url144 = $getStreamUrl($src_144);
    $subtitleUrl = $subtitle ? Storage::url($subtitle) : '';
    $playerId = 'player_' . md5($src ?? uniqid());
    
    // Build quality options array
    $qualities = [
        'Auto' => ['url' => 'auto', 'label' => 'Auto'],
        '1080p' => ['url' => $url1080 ?: $url720, 'label' => '1080p'],
        '720p' => ['url' => $url720 ?: $url1080, 'label' => '720p'],
        '480p' => ['url' => $url480 ?: $url720, 'label' => '480p'],
        '360p' => ['url' => $url360 ?: $url720, 'label' => '360p'],
        '240p' => ['url' => $url240 ?: $url720, 'label' => '240p'],
        '144p' => ['url' => $url144 ?: $url720, 'label' => '144p'],
    ];
    
    // Determine available qualities for Auto detection
    $availableQualities = [];
    if ($url1080) $availableQualities['1080p'] = $url1080;
    if ($url720) $availableQualities['720p'] = $url720;
    if ($url480) $availableQualities['480p'] = $url480;
    if ($url360) $availableQualities['360p'] = $url360;
    if ($url240) $availableQualities['240p'] = $url240;
    if ($url144) $availableQualities['144p'] = $url144;
    
    // If no specific qualities, use src as 720p
    if (empty($availableQualities)) {
        $availableQualities['720p'] = $getStreamUrl($src);
    }
    
    // Default quality
    $defaultQuality = 'Auto';
    $defaultLabel = 'Auto';
    
    // Get the highest available quality
    $qualityPriority = ['1080p', '720p', '480p', '360p', '240p', '144p'];
    foreach ($qualityPriority as $q) {
        if (isset($availableQualities[$q])) {
            $defaultQuality = $q;
            break;
        }
    }
@endphp

<div class="relative w-full max-w-full bg-black rounded-lg md:rounded-xl overflow-hidden group select-none {{ $class }}" id="vp-{{ $playerId }}">
    <div class="relative w-full aspect-video">
        <video 
            id="{{ $playerId }}"
            class="w-full h-full block object-contain"
            preload="metadata"
            playsinline
            webkit-playsinline
            x5-playsinline
            {{ $autoplay ? 'autoplay muted' : '' }}
            poster="{{ $poster ?? '' }}"
        >
            <source src="{{ $availableQualities[$defaultQuality] ?? reset($availableQualities) }}" type="video/mp4" data-quality="{{ $defaultQuality }}">
            @if($subtitleUrl)
                <track src="{{ $subtitleUrl }}" kind="subtitles" srclang="mm" label="Myanmar" default>
            @endif
            <p>Your browser doesn't support HTML5 video.</p>
        </video>

        <!-- Double Tap Indicators -->
        <div class="rewind-indicator absolute left-0 top-0 w-1/3 h-full flex flex-col items-center justify-center bg-black/30 opacity-0 pointer-events-none transition-opacity duration-200 z-20">
            <svg class="w-8 h-8 text-white fill-current" viewBox="0 0 24 24"><path d="M12.5 19.38M11.5 19.38l-7-5 7-5v10zm8-10l-7 5 7-5v10z"/></svg>
            <span class="text-white text-xs font-semibold mt-1">-10s</span>
        </div>

        <div class="forward-indicator absolute right-0 top-0 w-1/3 h-full flex flex-col items-center justify-center bg-black/30 opacity-0 pointer-events-none transition-opacity duration-200 z-20">
            <svg class="w-8 h-8 text-white fill-current" viewBox="0 0 24 24"><path d="M4 19.38l7-5-7-5v10zm8 0l7-5-7-5v10z"/></svg>
            <span class="text-white text-xs font-semibold mt-1">+10s</span>
        </div>

        <!-- Center Play Button Overlay -->
        <div class="custom-play-overlay absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 md:w-16 md:h-16 bg-black/70 rounded-full flex items-center justify-center transition-all duration-300 pointer-events-none z-10 group-[.is-playing]:opacity-0 group-[.is-playing]:scale-75 group-[.is-playing]:invisible">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-white fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        </div>

        <!-- Bottom Custom Controls Bar -->
        <div class="custom-controls-bar absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent flex flex-col gap-2 p-3 md:p-4 opacity-0 transition-opacity duration-300 z-30" id="controls-bar-{{ $playerId }}">
            
            <!-- Progress/Seek Bar -->
            <div class="relative w-full h-1.5 bg-white/30 rounded-full flex items-center">
                <div class="progress-bar-fill absolute left-0 top-0 h-full bg-red-600 rounded-full w-0 pointer-events-none"></div>
                <input type="range" class="seek-slider absolute left-0 w-full h-full opacity-0 cursor-pointer m-0 z-40" min="0" max="100" value="0" step="0.1">
            </div>

            <!-- Controls Buttons Panel -->
            <div class="flex items-center justify-between w-full mt-1">
                <div class="flex items-center gap-4">
                    <!-- Play/Pause Button -->
                    <button type="button" class="toggle-play bg-none border-none p-0 cursor-pointer flex items-center text-white hover:text-red-500 transition-colors">
                        <svg class="play-svg w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <svg class="pause-svg w-6 h-6 fill-current hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </button>

                    <!-- Mute/Unmute Button -->
                    <button type="button" class="toggle-mute bg-none border-none p-0 cursor-pointer flex items-center text-white hover:text-red-500 transition-colors">
                        <svg class="volume-up-svg w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                        <svg class="volume-mute-svg w-6 h-6 fill-current hidden" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.21.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                    </button>

                    <!-- Time Display -->
                    <span class="time-display text-white text-xs md:text-sm font-sans whitespace-nowrap min-w-[65px]">0:00 / 0:00</span>
                </div>

                <div class="flex items-center gap-3 md:gap-4 relative select-none">
                    @if($subtitleUrl)
                    <button type="button" class="toggle-cc bg-none border-none p-0 cursor-pointer flex items-center text-white hover:text-red-500 transition-colors font-bold text-xs md:text-sm border border-white/40 rounded px-1 py-0.5 leading-none bg-white/10 group-[.cc-active]:bg-red-600 group-[.cc-active]:border-red-600">CC</button>
                    @endif

                    <!-- ============================================ -->
                    <!-- VIDEO QUALITY BUTTON - Auto to 144p -->
                    <!-- ============================================ -->
                    <button type="button" class="toggle-quality bg-none border-none p-0 cursor-pointer flex items-center text-white hover:text-red-500 font-sans font-bold text-xs md:text-sm transition-colors">{{ $defaultLabel }}</button>
                    <div class="quality-menu absolute bottom-8 right-16 bg-black/90 border border-white/10 rounded-md py-1 flex flex-col min-w-[80px] hidden shadow-xl z-50">
                        @foreach($qualities as $label => $data)
                            @php
                                $isAvailable = ($label === 'Auto') || isset($availableQualities[$label]);
                                $isActive = ($label === $defaultLabel);
                            @endphp
                            @if($isAvailable)
                                <button type="button" class="quality-opt text-white text-xs py-1.5 px-3 text-left hover:bg-white/20 transition-colors {{ $isActive ? 'font-bold text-red-500' : '' }}" 
                                        data-src="{{ $data['url'] }}" 
                                        data-label="{{ $label }}"
                                        data-auto="{{ $label === 'Auto' ? 'true' : 'false' }}">
                                    {{ $label }}
                                    @if($label === 'Auto')
                                        <span class="text-gray-500 text-[10px] ml-1">({{ $defaultQuality }})</span>
                                    @endif
                                </button>
                            @endif
                        @endforeach
                    </div>

                    <!-- Speed Changer Button -->
                    <button type="button" class="toggle-speed bg-none border-none p-0 cursor-pointer flex items-center text-white hover:text-red-500 font-sans font-bold text-xs md:text-sm transition-colors">1.0x</button>
                    <div class="speed-menu absolute bottom-8 right-8 bg-black/90 border border-white/10 rounded-md py-1 flex flex-col min-w-[70px] hidden shadow-xl z-50">
                        <button type="button" class="speed-opt text-white text-xs py-1.5 px-3 text-left hover:bg-white/20" data-speed="0.5">0.5x</button>
                        <button type="button" class="speed-opt text-white text-xs py-1.5 px-3 text-left hover:bg-white/20 font-bold text-red-500" data-speed="1.0">1.0x</button>
                        <button type="button" class="speed-opt text-white text-xs py-1.5 px-3 text-left hover:bg-white/20" data-speed="1.5">1.5x</button>
                        <button type="button" class="speed-opt text-white text-xs py-1.5 px-3 text-left hover:bg-white/20" data-speed="2.0">2.0x</button>
                    </div>

                    <!-- Fullscreen Button -->
                    <button type="button" class="toggle-fullscreen bg-none border-none p-0 cursor-pointer flex items-center text-white hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('vp-{{ $playerId }}');
    const video = document.getElementById('{{ $playerId }}');
    const controlsBar = document.getElementById('controls-bar-{{ $playerId }}');
    if (!video || !container || !controlsBar) return;

    const playBtn = container.querySelector('.toggle-play');
    const playSvg = playBtn.querySelector('.play-svg');
    const pauseSvg = playBtn.querySelector('.pause-svg');
    const muteBtn = container.querySelector('.toggle-mute');
    const volUpSvg = muteBtn.querySelector('.volume-up-svg');
    const volMuteSvg = muteBtn.querySelector('.volume-mute-svg');
    const speedBtn = container.querySelector('.toggle-speed');
    const speedMenu = container.querySelector('.speed-menu');
    const speedOpts = container.querySelectorAll('.speed-opt');
    const qualityBtn = container.querySelector('.toggle-quality');
    const qualityMenu = container.querySelector('.quality-menu');
    const qualityOpts = container.querySelectorAll('.quality-opt');
    const ccBtn = container.querySelector('.toggle-cc');
    const fullscreenBtn = container.querySelector('.toggle-fullscreen');
    const timeDisplay = container.querySelector('.time-display');
    const seekSlider = container.querySelector('.seek-slider');
    const barFill = container.querySelector('.progress-bar-fill');
    const videoWrapper = video.parentElement;
    let controlsTimeout, lastTap = 0;
    let isPlaying = false;
    let currentQuality = '{{ $defaultLabel }}';
    
    // Available qualities for Auto detection
    const availableQualities = @json($availableQualities);
    const qualityPriority = ['1080p', '720p', '480p', '360p', '240p', '144p'];
    let isAutoQuality = (currentQuality === 'Auto');

    function formatTime(seconds) {
        if (isNaN(seconds)) return "0:00";
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return mins + ":" + (secs < 10 ? "0" : "") + secs;
    }

    // ============================================
    // AUTO QUALITY DETECTION
    // ============================================
    function detectAutoQuality() {
        if (!isAutoQuality) return;
        
        let connectionType = 'slow';
        if (navigator.connection) {
            const conn = navigator.connection;
            if (conn.effectiveType === '4g') connectionType = 'fast';
            else if (conn.effectiveType === '3g') connectionType = 'medium';
            else connectionType = 'slow';
        }
        
        let selectedQuality = '720p';
        if (connectionType === 'fast') selectedQuality = '1080p';
        else if (connectionType === 'medium') selectedQuality = '720p';
        else selectedQuality = '480p';
        
        if (!availableQualities[selectedQuality]) {
            for (let q of qualityPriority) {
                if (availableQualities[q]) {
                    selectedQuality = q;
                    break;
                }
            }
        }
        
        const currentSrc = video.src;
        const newSrc = availableQualities[selectedQuality];
        if (newSrc && currentSrc !== newSrc) {
            const currentTime = video.currentTime;
            const isPaused = video.paused;
            const currentSpeed = video.playbackRate;
            
            video.src = newSrc;
            qualityBtn.textContent = 'Auto';
            
            video.addEventListener('loadedmetadata', function onLoad() {
                video.currentTime = currentTime;
                video.playbackRate = currentSpeed;
                if (!isPaused) video.play();
                video.removeEventListener('loadedmetadata', onLoad);
            }, { once: true });
        }
    }

    // ============================================
    // CONTROLS VISIBILITY
    // ============================================
    function showControls() {
        controlsBar.style.opacity = '1';
        clearTimeout(controlsTimeout);
    }

    function hideControls() {
        controlsBar.style.opacity = '0';
        speedMenu.classList.add('hidden');
        qualityMenu.classList.add('hidden');
    }

    function autoHideControls() {
        clearTimeout(controlsTimeout);
        if (isPlaying) {
            controlsTimeout = setTimeout(function() {
                hideControls();
            }, 2000);
        }
    }

    // ============================================
    // TOGGLE PLAY
    // ============================================
    function togglePlay() {
        if (video.paused) {
            video.play();
            container.classList.add('is-playing');
            playSvg.classList.add('hidden');
            pauseSvg.classList.remove('hidden');
            isPlaying = true;
            showControls();
            autoHideControls();
        } else {
            video.pause();
            container.classList.remove('is-playing');
            playSvg.classList.remove('hidden');
            pauseSvg.classList.add('hidden');
            isPlaying = false;
            showControls();
            clearTimeout(controlsTimeout);
        }
    }

    function toggleMute() {
        video.muted = !video.muted;
        if (video.muted) {
            volUpSvg.classList.add('hidden');
            volMuteSvg.classList.remove('hidden');
        } else {
            volUpSvg.classList.remove('hidden');
            volMuteSvg.classList.add('hidden');
        }
    }

    function toggleFullscreen() {
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        const targetElement = isMobile ? video : container;
        if (!document.fullscreenElement && !document.webkitFullscreenElement && !video.webkitDisplayingFullscreen) {
            if (targetElement.requestFullscreen) { targetElement.requestFullscreen(); }
            else if (targetElement.webkitRequestFullscreen) { targetElement.webkitRequestFullscreen(); }
            else if (video.webkitEnterFullscreen) { video.webkitEnterFullscreen(); }
        } else {
            if (document.exitFullscreen) { document.exitFullscreen(); }
            else if (document.webkitExitFullscreen) { document.webkitExitFullscreen(); }
        }
    }

    // ============================================
    // MOUSE EVENTS
    // ============================================
    container.addEventListener('mouseenter', function() {
        showControls();
        clearTimeout(controlsTimeout);
    });

    container.addEventListener('mouseleave', function() {
        if (isPlaying) {
            autoHideControls();
        }
    });

    container.addEventListener('mousemove', function() {
        if (isPlaying) {
            showControls();
            clearTimeout(controlsTimeout);
            controlsTimeout = setTimeout(function() {
                hideControls();
            }, 2000);
        }
    });

    // ============================================
    // TOUCH EVENTS
    // ============================================
    container.addEventListener('touchstart', function() {
        showControls();
        clearTimeout(controlsTimeout);
        if (isPlaying) {
            controlsTimeout = setTimeout(function() {
                hideControls();
            }, 2000);
        }
    });

    // ============================================
    // VIDEO CLICK
    // ============================================
    videoWrapper.addEventListener('click', function(e) {
        if (e.target.closest('.custom-controls-bar')) return;
        
        const now = Date.now();
        if (now - lastTap < 300) {
            const rect = videoWrapper.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            if (clickX < rect.width / 3) {
                video.currentTime = Math.max(0, video.currentTime - 10);
                triggerIndicator('.rewind-indicator');
            } else if (clickX > (rect.width * 2) / 3) {
                video.currentTime = Math.min(video.duration, video.currentTime + 10);
                triggerIndicator('.forward-indicator');
            }
        } else {
            togglePlay();
        }
        lastTap = now;
    });

    function triggerIndicator(selector) {
        const ind = container.querySelector(selector);
        if (ind) {
            ind.classList.remove('opacity-0'); 
            ind.classList.add('opacity-100');
            setTimeout(() => { 
                ind.classList.remove('opacity-100'); 
                ind.classList.add('opacity-0'); 
            }, 500);
        }
    }

    // ============================================
    // QUALITY - Auto + 1080p to 144p
    // ============================================
    if(qualityBtn) {
        qualityBtn.addEventListener('click', function(e) { 
            e.stopPropagation(); 
            qualityMenu.classList.toggle('hidden'); 
            speedMenu.classList.add('hidden'); 
            showControls(); 
            clearTimeout(controlsTimeout);
        });
        
        qualityOpts.forEach(opt => {
            opt.addEventListener('click', function(e) {
                e.stopPropagation();
                const newSrc = this.getAttribute('data-src');
                const label = this.getAttribute('data-label');
                const isAuto = this.getAttribute('data-auto') === 'true';
                
                if (label !== currentQuality) {
                    const currentTime = video.currentTime;
                    const isPaused = video.paused;
                    const currentSpeed = video.playbackRate;
                    
                    if (isAuto) {
                        isAutoQuality = true;
                        qualityBtn.textContent = 'Auto';
                        qualityOpts.forEach(o => o.classList.remove('font-bold', 'text-red-500'));
                        this.classList.add('font-bold', 'text-red-500');
                        detectAutoQuality();
                    } else if (newSrc && newSrc !== 'auto') {
                        isAutoQuality = false;
                        video.src = newSrc;
                        qualityBtn.textContent = label;
                        qualityOpts.forEach(o => o.classList.remove('font-bold', 'text-red-500'));
                        this.classList.add('font-bold', 'text-red-500');
                        currentQuality = label;
                        
                        video.addEventListener('loadedmetadata', function onLoad() {
                            video.currentTime = currentTime;
                            video.playbackRate = currentSpeed;
                            if (!isPaused) video.play();
                            video.removeEventListener('loadedmetadata', onLoad);
                        }, { once: true });
                    }
                }
                qualityMenu.classList.add('hidden');
            });
        });
    }

    // ============================================
    // CC
    // ============================================
    if(ccBtn) {
        container.classList.add('cc-active');
        ccBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (video.textTracks.length > 0) {
                const track = video.textTracks[0];
                track.mode = (track.mode === 'showing') ? 'hidden' : 'showing';
                container.classList.toggle('cc-active', track.mode === 'showing');
            }
        });
    }

    // ============================================
    // SPEED
    // ============================================
    speedBtn.addEventListener('click', function(e) { 
        e.stopPropagation(); 
        speedMenu.classList.toggle('hidden'); 
        if(qualityMenu) qualityMenu.classList.add('hidden'); 
        showControls(); 
        clearTimeout(controlsTimeout);
    });
    speedOpts.forEach(opt => {
        opt.addEventListener('click', function(e) {
            e.stopPropagation();
            const selectedSpeed = parseFloat(this.getAttribute('data-speed'));
            video.playbackRate = selectedSpeed; 
            speedBtn.textContent = selectedSpeed + 'x';
            speedOpts.forEach(o => o.classList.remove('font-bold', 'text-red-500')); 
            this.classList.add('font-bold', 'text-red-500');
            speedMenu.classList.add('hidden'); 
            showControls();
            clearTimeout(controlsTimeout);
        });
    });

    // ============================================
    // CLOSE MENUS
    // ============================================
    document.addEventListener('click', function() { 
        speedMenu.classList.add('hidden'); 
        if(qualityMenu) qualityMenu.classList.add('hidden'); 
    });

    // ============================================
    // BUTTON EVENTS
    // ============================================
    playBtn.addEventListener('click', function(e) { e.stopPropagation(); togglePlay(); });
    muteBtn.addEventListener('click', function(e) { e.stopPropagation(); toggleMute(); });
    fullscreenBtn.addEventListener('click', function(e) { e.stopPropagation(); toggleFullscreen(); });

    // ============================================
    // VIDEO EVENTS
    // ============================================
    video.addEventListener('timeupdate', function() {
        if (!video.duration) return;
        const current = video.currentTime, duration = video.duration, percentage = (current / duration) * 100;
        seekSlider.value = percentage; 
        barFill.style.width = percentage + '%';
        timeDisplay.textContent = formatTime(current) + " / " + formatTime(duration);
        localStorage.setItem('video_time_' + '{{ md5($src) }}', current);
    });

    video.addEventListener('loadedmetadata', function() {
        timeDisplay.textContent = formatTime(video.currentTime) + " / " + formatTime(video.duration);
        if(video.muted) { volUpSvg.classList.add('hidden'); volMuteSvg.classList.remove('hidden'); }
        const savedTime = localStorage.getItem('video_time_' + '{{ md5($src) }}');
        if (savedTime) { const saved = parseFloat(savedTime); if (saved < video.duration) video.currentTime = saved; }
        if (isAutoQuality) detectAutoQuality();
    });

    seekSlider.addEventListener('input', function() {
        if (!video.duration) return;
        video.currentTime = (seekSlider.value / 100) * video.duration;
        barFill.style.width = seekSlider.value + '%'; 
        showControls();
        clearTimeout(controlsTimeout);
    });

    // ============================================
    // VIDEO PLAY / PAUSE
    // ============================================
    video.addEventListener('play', function() {
        isPlaying = true;
        container.classList.add('is-playing');
        playSvg.classList.add('hidden');
        pauseSvg.classList.remove('hidden');
        showControls();
        autoHideControls();
    });

    video.addEventListener('pause', function() {
        isPlaying = false;
        container.classList.remove('is-playing');
        playSvg.classList.remove('hidden');
        pauseSvg.classList.add('hidden');
        showControls();
        clearTimeout(controlsTimeout);
    });

    video.addEventListener('ended', function() { 
        isPlaying = false;
        container.classList.remove('is-playing'); 
        playSvg.classList.remove('hidden'); 
        pauseSvg.classList.add('hidden'); 
    });

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (document.activeElement?.tagName === 'INPUT' || document.activeElement?.tagName === 'TEXTAREA') return;
        if (e.key === ' ') { e.preventDefault(); togglePlay(); }
        if (e.key === 'f' || e.key === 'F') { e.preventDefault(); toggleFullscreen(); }
        if (e.key === 'm' || e.key === 'M') { e.preventDefault(); toggleMute(); }
        if (e.key === 'ArrowRight') { e.preventDefault(); video.currentTime = Math.min(video.currentTime + 5, video.duration); showControls(); clearTimeout(controlsTimeout); if (isPlaying) { controlsTimeout = setTimeout(function() { hideControls(); }, 2000); } }
        if (e.key === 'ArrowLeft') { e.preventDefault(); video.currentTime = Math.max(video.currentTime - 5, 0); showControls(); clearTimeout(controlsTimeout); if (isPlaying) { controlsTimeout = setTimeout(function() { hideControls(); }, 2000); } }
    });

    // ============================================
    // PREVENT SCROLL
    // ============================================
    const wrapper = container.querySelector('.aspect-video');
    if (wrapper) {
        wrapper.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, { passive: false });
    }

    // ============================================
    // NETWORK CHANGE DETECTION (for Auto Quality)
    // ============================================
    if (navigator.connection) {
        navigator.connection.addEventListener('change', function() {
            if (isAutoQuality) {
                detectAutoQuality();
            }
        });
    }

    // ============================================
    // INIT
    // ============================================
    setTimeout(function() {
        if (isPlaying) {
            controlsBar.style.opacity = '0';
        }
        if (isAutoQuality) {
            setTimeout(detectAutoQuality, 1000);
        }
    }, 1000);

    console.log('✅ Video player ready');
});
</script>