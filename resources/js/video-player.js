document.addEventListener('alpine:init', () => {
    Alpine.data('videoPlayer', () => ({
        playing: false,
        muted: false,
        currentTime: 0,
        duration: 0,
        showControls: true,
        controlsTimeout: null,
        progress: 0,
        bufferedProgress: 0,
        isDragging: false,
        isFullscreen: false,
        isLoading: true,
        loadingProgress: 0,
        loadingText: 'Loading video...',
        loadingSubText: 'Please wait',
        video: null,
        progressContainer: null,
        playerContainer: null,
        videoId: null,
        isPausedBySystem: false,
        resizeObserver: null,
        loadingTimeout: null,
        _loadingInterval: null,
        
        speed: 1.0,
        speedOptions: [0.25, 0.5, 0.75, 1.0, 1.25, 1.5, 1.75, 2.0],
        showSpeedMenu: false,
        
        qualityOptions: ['auto', '1080', '720', '480', '360', '240'],
        currentQuality: 'auto',
        showQualityMenu: false,
        
        pictureModes: ['standard', 'cinema', 'vivid', 'game'],
        currentPictureMode: 'standard',
        pictureModeLabel: 'Standard',

        init() {
            console.log('Video Player Initialized');
            
            this.video = this.$refs.videoPlayer;
            this.progressContainer = this.$refs.progressContainer;
            this.playerContainer = this.$refs.playerContainer;
            this.videoId = 'video-' + Math.random().toString(36).substr(2, 9);
            
            this.setupDragEvents();
            this.setupFullscreenListeners();
            this.setupResizeObserver();
            this.applyPictureMode('standard');

            this.isLoading = true;
            this.loadingProgress = 0;
            this.loadingText = 'Loading video...';
            this.loadingSubText = 'Please wait';
            
            this.simulateLoading();

            setTimeout(() => {
                if (!this.playing) {
                    this.showControls = false;
                }
            }, 3000);

            document.addEventListener('scroll', () => {
                this.closeAllMenus();
            });
        },

        simulateLoading() {
            const interval = setInterval(() => {
                if (this.isLoading) {
                    this.loadingProgress += Math.random() * 3 + 1;
                    
                    if (this.loadingProgress > 95) {
                        this.loadingProgress = 95;
                    }
                    
                    if (this.loadingProgress < 20) {
                        this.loadingText = 'Connecting to server...';
                        this.loadingSubText = 'Establishing connection';
                    } else if (this.loadingProgress < 40) {
                        this.loadingText = 'Loading video data...';
                        this.loadingSubText = 'Fetching content';
                    } else if (this.loadingProgress < 60) {
                        this.loadingText = 'Buffering...';
                        this.loadingSubText = 'Loading chunks';
                    } else if (this.loadingProgress < 80) {
                        this.loadingText = 'Processing video...';
                        this.loadingSubText = 'Almost ready';
                    } else {
                        this.loadingText = 'Almost ready...';
                        this.loadingSubText = 'Finalizing';
                    }
                } else {
                    clearInterval(interval);
                }
            }, 200);

            this._loadingInterval = interval;
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
            const fullscreenEvents = ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'];
            
            fullscreenEvents.forEach(event => {
                document.addEventListener(event, () => {
                    this.isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
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
                this.showControls = true;
                setTimeout(() => {
                    if (!this.playing) {
                        this.showControls = false;
                    }
                }, 3000);
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
                this.showControls = true;
                setTimeout(() => {
                    if (!this.playing) {
                        this.showControls = false;
                    }
                }, 3000);
            }
        },

        handlePlayEvent(event) {
            if (this.playing && event.detail?.videoId !== this.videoId && this.video) {
                this.video.pause();
                this.playing = false;
                this.showControls = true;
                setTimeout(() => {
                    if (!this.playing) {
                        this.showControls = false;
                    }
                }, 3000);
            }
        },

        onPlay() {
            this.playing = true;
            this.isPausedBySystem = false;
            this.showControls = true;
            this.isLoading = false;
            this.loadingProgress = 100;
            this.triggerControls();
            clearTimeout(this.loadingTimeout);
            clearInterval(this._loadingInterval);
        },

        onPause() {
            if (!this.isPausedBySystem) {
                this.playing = false;
                this.showControls = true;
                setTimeout(() => {
                    if (!this.playing) {
                        this.showControls = false;
                    }
                }, 3000);
            }
            this.isPausedBySystem = false;
        },

        onLoadStart() {
            this.isLoading = true;
            this.loadingText = 'Starting to load...';
            this.loadingSubText = 'Connecting';
            this.loadingProgress = 5;
        },

        onWaiting() {
            this.isLoading = true;
            this.loadingText = 'Buffering...';
            this.loadingSubText = 'Loading more data';
        },

        onCanPlay() {
            this.isLoading = false;
            this.loadingProgress = 100;
            clearTimeout(this.loadingTimeout);
            clearInterval(this._loadingInterval);
        },

        onCanPlayThrough() {
            this.isLoading = false;
            this.loadingProgress = 100;
            clearTimeout(this.loadingTimeout);
            clearInterval(this._loadingInterval);
        },

        onStalled() {
            this.isLoading = true;
            this.loadingText = 'Connection stalled, retrying...';
            this.loadingSubText = 'Attempting to reconnect';
        },

        onError() {
            this.isLoading = false;
            this.loadingText = 'Error loading video';
            this.loadingSubText = 'Please try again';
            console.error('Video loading error');
            clearInterval(this._loadingInterval);
        },

        onProgress() {
            if (this.video && this.video.buffered.length > 0) {
                const bufferedEnd = this.video.buffered.end(this.video.buffered.length - 1);
                if (this.duration > 0) {
                    const buffered = (bufferedEnd / this.duration) * 100;
                    this.bufferedProgress = Math.min(buffered, 100);
                    
                    if (this.isLoading) {
                        this.loadingProgress = Math.min(buffered, 95);
                    }
                }
            }
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
                
                if (this.video.buffered.length > 0) {
                    const bufferedEnd = this.video.buffered.end(this.video.buffered.length - 1);
                    if (this.duration > 0) {
                        this.bufferedProgress = (bufferedEnd / this.duration) * 100;
                    }
                }
            }
        },

        onLoadedMetadata() {
            if (this.video) {
                this.duration = this.video.duration;
                this.isLoading = false;
                this.loadingProgress = 100;
                clearTimeout(this.loadingTimeout);
                clearInterval(this._loadingInterval);
            }
        },

        onEnded() {
            this.playing = false;
            this.showControls = true;
            setTimeout(() => {
                if (!this.playing) {
                    this.showControls = false;
                }
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
            this.pictureModeLabel = this.currentPictureMode.charAt(0).toUpperCase() + this.currentPictureMode.slice(1);
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
            
            if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
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
            this.showControls = true;
            clearTimeout(this.controlsTimeout);
            if (this.playing) {
                this.controlsTimeout = setTimeout(() => { 
                    if (this.playing) {
                        this.showControls = false; 
                    }
                }, 2500);
            }
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
            
            clearTimeout(this.loadingTimeout);
            clearTimeout(this.controlsTimeout);
            clearInterval(this._loadingInterval);
        }
    }));
});

// Global event listener for pausing all videos
window.addEventListener('pauseAllVideos', (event) => {
    document.querySelectorAll('video').forEach(video => {
        if (!video.paused) {
            video.pause();
        }
    });
});