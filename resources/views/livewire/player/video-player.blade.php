<div class="relative w-full bg-black group overflow-hidden rounded-xl shadow-lg aspect-video flex items-center justify-center"
     x-data="videoPlayer()"
     x-init="init()"
     x-ref="playerContainer"
     @mousemove="triggerControls()"
     @mouseleave="if(playing) showControls = false"
     @pauseVideoPlayer.window="pauseVideo()"
     @play.window="handlePlayEvent($event)"
     style="min-height: 200px; max-height: 80vh;">

    <!-- 🔄 LOADING SPINNER OVERLAY -->
    <div x-show="isLoading" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 z-30 pointer-events-none">
        <div class="w-12 h-12 md:w-16 md:h-16 border-4 border-white/20 border-t-blue-500 rounded-full animate-spin"></div>
        <span class="text-white/80 text-xs md:text-sm mt-3 font-medium tracking-wide">Loading...</span>
    </div>

    <!-- Video Element -->
    <video x-ref="videoPlayer"
           @click="togglePlay()"
           @timeupdate="updateProgress()"
           @loadedmetadata="onLoadedMetadata()"
           @ended="onEnded()"
           @play="onPlay()"
           @pause="onPause()"
           @waiting="isLoading = true"
           @seeking="isLoading = true"
           @seeked="isLoading = false"
           @canplay="isLoading = false"
           @playing="isLoading = false"
           src="{{ $videoUrl }}"
           poster="{{ $thumbnailUrl }}"
           class="w-full h-full object-contain cursor-pointer"
           style="max-height: 80vh;">
    </video>

    <!-- Big Play Button Overlay (အလင်းပေါက်နေတဲ့ Play Button) -->
    <div x-show="!playing && !isLoading" 
         @click="togglePlay()"
         class="absolute inset-0 flex items-center justify-center bg-black/20 cursor-pointer group-hover:bg-black/30 transition z-10">
        <button class="w-16 h-16 md:w-20 md:h-20 bg-white/20 hover:bg-white/30 text-white backdrop-blur-sm rounded-full flex items-center justify-center shadow-2xl transform hover:scale-110 transition duration-200 border border-white/30">
            <svg class="w-7 h-7 md:w-10 md:h-10 fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        </button>
    </div>

    <!-- Video Controls (Only show when playing AND controls are visible) -->
    <div x-show="showControls && playing"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-2 sm:p-3 md:p-4 flex flex-col gap-2 md:gap-3 select-none z-20">
        
        <!-- Progress Bar -->
        <div class="w-full h-1.5 sm:h-2 bg-white/30 rounded-full cursor-pointer relative group/progress"
             x-ref="progressContainer"
             @click="handleSeek($event)">
            
            <!-- Active Fill Progress -->
            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full absolute top-0 left-0 pointer-events-none transition-none"
                 :style="`width: ${progress}%`"></div>
                 
            <!-- Seek Handle -->
            <div class="absolute w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 bg-white rounded-full -top-0.5 sm:-top-1 shadow-lg opacity-0 group-hover/progress:opacity-100 transition-opacity pointer-events-none"
                 :style="`left: calc(${progress}% - 6px)`"></div>
        </div>

        <!-- Controls Row -->
        <div class="flex items-center justify-between text-white">
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                <!-- Play/Pause -->
                <button @click="togglePlay()" class="hover:text-blue-400 transition transform active:scale-90 p-1">
                    <template x-if="!playing">
                        <svg class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </template>
                    <template x-if="playing">
                        <svg class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </template>
                </button>

                <!-- Mute -->
                <button @click="toggleMute()" class="hover:text-blue-400 transition p-1">
                    <template x-if="!muted">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                    </template>
                    <template x-if="muted">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.21.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.85 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                    </template>
                </button>

                <!-- Time -->
                <div class="text-[10px] sm:text-xs md:text-sm font-medium tracking-wide">
                    <span x-text="formatTime(currentTime)">0:00</span>
                    <span class="opacity-60">/</span>
                    <span class="opacity-60" x-text="formatTime(duration)">0:00</span>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center gap-1 sm:gap-2">
                <!-- Speed Control -->
                <div class="relative">
                    <button @click="toggleSpeedMenu()" 
                            class="hover:text-blue-400 transition p-1"
                            title="Playback Speed">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                        </svg>
                    </button>
                    
                    <!-- Speed Dropdown -->
                    <div x-show="showSpeedMenu" 
                         @click.away="showSpeedMenu = false"
                         class="absolute bottom-full right-0 mb-2 bg-black/90 backdrop-blur-sm rounded-lg shadow-xl p-2 z-30 min-w-[100px]">
                        <div class="flex flex-col gap-0.5">
                            <template x-for="speedOption in speedOptions" :key="speedOption">
                                <button @click="setSpeed(speedOption)" 
                                        class="px-3 py-1.5 text-xs sm:text-sm text-white hover:bg-white/10 rounded transition text-left flex items-center justify-between"
                                        :class="{'bg-white/10': speed === speedOption}">
                                    <span x-text="speedOption === 1 ? 'Normal' : speedOption + 'x'"></span>
                                    <span x-show="speed === speedOption" class="text-blue-400 text-xs ml-2">✓</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Quality Control -->
                <div class="relative">
                    <button @click="toggleQualityMenu()" 
                            class="hover:text-blue-400 transition p-1"
                            title="Video Quality">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M3 9h4V5H3v4zm0 5h4v-4H3v4zm5 0h4v-4H8v4zm5 0h4v-4h-4v4zM8 9h4V5H8v4zm5-4v4h4V5h-4zm5 9h4v-4h-4v4zM3 19h4v-4H3v4zm5 0h4v-4H8v4zm5 0h4v-4h-4v4zm5 0h4v-4h-4v4zm0-14v4h4V5h-4z"/>
                        </svg>
                    </button>
                    
                    <!-- Quality Dropdown -->
                    <div x-show="showQualityMenu" 
                         @click.away="showQualityMenu = false"
                         class="absolute bottom-full right-0 mb-2 bg-black/90 backdrop-blur-sm rounded-lg shadow-xl p-2 z-30 min-w-[100px]">
                        <div class="flex flex-col gap-0.5">
                            <template x-for="qualityOption in qualityOptions" :key="qualityOption">
                                <button @click="setQuality(qualityOption)" 
                                        class="px-3 py-1.5 text-xs sm:text-sm text-white hover:bg-white/10 rounded transition text-left flex items-center justify-between"
                                        :class="{'bg-white/10': currentQuality === qualityOption}">
                                    <span x-text="qualityOption === 'auto' ? 'Auto' : qualityOption + 'p'"></span>
                                    <span x-show="currentQuality === qualityOption" class="text-blue-400 text-xs ml-2">✓</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Picture Mode -->
                <button @click="togglePictureMode()" 
                        class="hover:text-blue-400 transition p-1"
                        title="Picture Mode">
                    <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM9 8c0 1.66-1.34 3-3 3s-3-1.34-3-3 1.34-3 3-3 3 1.34 3 3zm-3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm6-3h6v2h-6V6zm0 4h6v2h-6v-2zm0 4h6v2h-6v-2zM8 15c0 1.66-1.34 3-3 3s-3-1.34-3-3 1.34-3 3-3 3 1.34 3 3zm-3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/>
                    </svg>
                </button>

                <!-- Fullscreen -->
                <button @click="toggleFullscreen()" class="hover:text-blue-400 transition transform active:scale-95 p-1">
                    <template x-if="!isFullscreen">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                    </template>
                    <template x-if="isFullscreen">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 24 24"><path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/></svg>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function videoPlayer() {
    return {
        playing: false,
        muted: false,
        isLoading: true,
        currentTime: 0,
        duration: 0,
        showControls: false,
        controlsTimeout: null,
        progress: 0,
        isDragging: false,
        isFullscreen: false,
        video: null,
        progressContainer: null,
        playerContainer: null,
        videoId: null,
        isPausedBySystem: false,
        resizeObserver: null,
        
        // Speed settings
        speed: 1.0,
        speedOptions: [0.25, 0.5, 0.75, 1.0, 1.25, 1.5, 1.75, 2.0],
        showSpeedMenu: false,
        
        // Quality settings
        qualityOptions: ['auto', '1080', '720', '480', '360', '240'],
        currentQuality: 'auto',
        showQualityMenu: false,
        
        // Picture modes
        pictureModes: ['standard', 'cinema', 'vivid', 'game'],
        currentPictureMode: 'standard',
        pictureModeLabel: 'Standard',

        init() {
            this.video = this.$refs.videoPlayer;
            this.progressContainer = this.$refs.progressContainer;
            this.playerContainer = this.$refs.playerContainer;
            this.videoId = 'video-' + Math.random().toString(36).substr(2, 9);
            
            this.setupDragEvents();
            this.setupFullscreenListeners();
            this.setupResizeObserver();
            this.applyPictureMode('standard');

            if (this.video && this.video.readyState >= 3) {
                this.isLoading = false;
            }

            document.addEventListener('scroll', () => {
                this.closeAllMenus();
            });
        },

        setupDragEvents() {
            if (!this.progressContainer) return;
            
            this.progressContainer.addEventListener('mousedown', (e) => {
                this.startDrag(e);
            });

            this.progressContainer.addEventListener('touchstart', (e) => {
                this.startDrag(e);
            }, { passive: false });
        },

        setupFullscreenListeners() {
            const fullscreenEvents = [
                'fullscreenchange',
                'webkitfullscreenchange',
                'mozfullscreenchange',
                'MSFullscreenChange'
            ];
            
            fullscreenEvents.forEach(event => {
                document.addEventListener(event, () => {
                    this.isFullscreen = !!(document.fullscreenElement || 
                                          document.webkitFullscreenElement || 
                                          document.mozFullScreenElement || 
                                          document.msFullscreenElement);
                });
            });
        },

        setupResizeObserver() {
            if (window.ResizeObserver) {
                this.resizeObserver = new ResizeObserver(() => {});
                this.resizeObserver.observe(this.playerContainer);
            }
        },

        startDrag(e) {
            e.preventDefault();
            this.isDragging = true;
            this.updateSeek(e);
            
            const moveHandler = (ev) => {
                if (this.isDragging) {
                    this.updateSeek(ev);
                }
            };
            
            const endHandler = (ev) => {
                if (this.isDragging) {
                    this.isDragging = false;
                    this.updateSeek(ev);
                    this.triggerControls();
                    
                    document.removeEventListener('mousemove', moveHandler);
                    document.removeEventListener('mouseup', endHandler);
                    document.removeEventListener('touchmove', moveHandler);
                    document.removeEventListener('touchend', endHandler);
                }
            };
            
            document.addEventListener('mousemove', moveHandler);
            document.addEventListener('mouseup', endHandler);
            document.addEventListener('touchmove', moveHandler, { passive: false });
            document.addEventListener('touchend', endHandler);
            
            this._moveHandler = moveHandler;
            this._endHandler = endHandler;
        },

        updateSeek(e) {
            if (this.duration <= 0 || !this.progressContainer) return;
            
            let clientX;
            if (e.touches) {
                clientX = e.touches[0].clientX;
                e.preventDefault();
            } else {
                clientX = e.clientX;
            }
            
            const rect = this.progressContainer.getBoundingClientRect();
            let pos = (clientX - rect.left) / rect.width;
            pos = Math.min(Math.max(pos, 0), 1);
            
            const newTime = pos * this.duration;
            this.video.currentTime = newTime;
            this.currentTime = newTime;
            this.progress = pos * 100;
        },

        handleSeek(e) {
            if (this.duration <= 0 || !this.progressContainer) return;
            
            const rect = this.progressContainer.getBoundingClientRect();
            let pos = (e.clientX - rect.left) / rect.width;
            pos = Math.min(Math.max(pos, 0), 1);
            
            const newTime = pos * this.duration;
            this.video.currentTime = newTime;
            this.currentTime = newTime;
            this.progress = pos * 100;
        },

        formatTime(secs) {
            if (isNaN(secs) || !secs || secs === Infinity) return '0:00';
            const m = Math.floor(secs / 60);
            const s = Math.floor(secs % 60);
            return m + ':' + (s < 10 ? '0' : '') + s;
        },

        togglePlay() {
            if (!this.video) return;
            
            if (this.playing) {
                this.video.pause();
                this.playing = false;
                this.showControls = false;
            } else {
                this.pauseAllOtherVideos();
                this.video.play().catch(e => console.log('Play error:', e));
                this.playing = true;
                this.showControls = true;
                this.triggerControls();
            }
            this.closeAllMenus();
        },

        pauseAllOtherVideos() {
            window.dispatchEvent(new CustomEvent('pauseAllVideos', { 
                detail: { exclude: this.videoId } 
            }));
        },

        pauseVideo() {
            if (this.playing && this.video) {
                this.video.pause();
                this.playing = false;
                this.isPausedBySystem = true;
                this.showControls = false;
            }
        },

        handlePlayEvent(event) {
            if (this.playing && event.detail?.videoId !== this.videoId && this.video) {
                this.video.pause();
                this.playing = false;
                this.showControls = false;
            }
        },

        onPlay() {
            this.playing = true;
            this.isPausedBySystem = false;
            this.showControls = true;
            this.triggerControls();
        },

        onPause() {
            if (!this.isPausedBySystem) {
                this.playing = false;
                this.showControls = false;
            }
            this.isPausedBySystem = false;
        },

        toggleMute() {
            if (!this.video) return;
            this.muted = !this.muted;
            this.video.muted = this.muted;
        },

        updateProgress() {
            if (!this.isDragging && this.video) {
                this.currentTime = this.video.currentTime;
                if (this.duration > 0) {
                    this.progress = (this.currentTime / this.duration) * 100;
                }
            }
        },

        onLoadedMetadata() {
            if (this.video) {
                this.duration = this.video.duration;
            }
            this.isLoading = false;
        },

        onEnded() {
            this.playing = false;
            this.showControls = true;
            setTimeout(() => {
                this.showControls = false;
            }, 3000);
        },

        toggleSpeedMenu() {
            this.showSpeedMenu = !this.showSpeedMenu;
            this.showQualityMenu = false;
            this.triggerControls();
        },

        setSpeed(speed) {
            this.speed = speed;
            if (this.video) {
                this.video.playbackRate = speed;
            }
            this.showSpeedMenu = false;
            this.triggerControls();
        },

        toggleQualityMenu() {
            this.showQualityMenu = !this.showQualityMenu;
            this.showSpeedMenu = false;
            this.triggerControls();
        },

        setQuality(quality) {
            this.currentQuality = quality;
            this.showQualityMenu = false;
            this.triggerControls();
        },

        togglePictureMode() {
            const currentIndex = this.pictureModes.indexOf(this.currentPictureMode);
            const nextIndex = (currentIndex + 1) % this.pictureModes.length;
            this.currentPictureMode = this.pictureModes[nextIndex];
            this.pictureModeLabel = this.currentPictureMode.charAt(0).toUpperCase() + 
                                   this.currentPictureMode.slice(1);
            this.applyPictureMode(this.currentPictureMode);
            this.triggerControls();
            this.closeAllMenus();
        },

        applyPictureMode(mode) {
            if (!this.video) return;
            
            const video = this.video;
            video.style.filter = 'none';
            video.style.brightness = '1';
            video.style.contrast = '1';
            video.style.saturation = '1';
            
            switch(mode) {
                case 'cinema':
                    video.style.filter = 'brightness(0.9) contrast(1.1) saturate(1.2)';
                    break;
                case 'vivid':
                    video.style.filter = 'brightness(1.1) contrast(1.2) saturate(1.5)';
                    break;
                case 'game':
                    video.style.filter = 'brightness(1.05) contrast(1.15) saturate(1.3)';
                    break;
                case 'standard':
                default:
                    break;
            }
        },

        toggleFullscreen() {
            const element = this.playerContainer || this.$el;
            
            if (!document.fullscreenElement && 
                !document.webkitFullscreenElement && 
                !document.mozFullScreenElement && 
                !document.msFullscreenElement) {
                
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
            this.closeAllMenus();
        },

        closeAllMenus() {
            this.showSpeedMenu = false;
            this.showQualityMenu = false;
        },

        triggerControls() {
            if (!this.playing) return;
            this.showControls = true;
            clearTimeout(this.controlsTimeout);
            this.controlsTimeout = setTimeout(() => { 
                if (this.playing) {
                    this.showControls = false; 
                }
            }, 2500);
        },

        destroy() {
            if (this._moveHandler) {
                document.removeEventListener('mousemove', this._moveHandler);
                document.removeEventListener('mouseup', this._endHandler);
                document.removeEventListener('touchmove', this._moveHandler);
                document.removeEventListener('touchend', this._endHandler);
            }
            
            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
            }
        }
    }
}

// Global event listener for pausing all videos
window.addEventListener('pauseAllVideos', (event) => {
    document.querySelectorAll('video').forEach(video => {
        if (!video.paused) {
            video.pause();
        }
    });
});
</script>