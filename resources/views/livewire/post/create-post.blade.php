<div>
    @if($showModal)
    <div x-data="createPostData()"
         class="fixed top-0 left-0 w-full h-full bg-black/60 z-[1000] flex items-center justify-center animate-fadeIn"
         wire:click.self="closeModal">
        
        <div class="bg-[#242526] rounded-2xl w-[92%] max-w-[520px] max-h-[90vh] overflow-y-auto shadow-2xl">
            
            {{-- Header --}}
            <div class="flex justify-between items-center px-5 py-4 border-b border-[#3e4042] sticky top-0 bg-[#242526] rounded-t-2xl z-10">
                <h3 class="text-xl font-bold text-[#e4e6eb] m-0">Create Post</h3>
                <button type="button" wire:click="closeModal" class="bg-[#3e4042] border-none w-9 h-9 rounded-full text-xl cursor-pointer flex items-center justify-center text-[#b0b3b8] hover:bg-[#4e4f51] transition">✕</button>
            </div>
            
            {{-- Content Form --}}
            <div class="px-5 py-4">
                {{-- User Info --}}
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#667eea] to-[#764ba2] flex items-center justify-center text-white font-bold">
                        {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                    </div>
                    <div>
                        <div class="text-[#e4e6eb] font-semibold">{{ auth()->user()->name ?? 'User' }}</div>
                        <select wire:model="privacy" class="bg-[#3e4042] border-none px-3 py-0.5 rounded-md text-xs text-[#b0b3b8]">
                            <option value="public">🌍 Public</option>
                            <option value="friends">👥 Friends</option>
                            <option value="onlyme">🔒 Only Me</option>
                        </select>
                    </div>
                </div>

                {{-- Title --}}
                <div class="mb-2.5">
                    <input type="text" 
                           wire:model.live="title" 
                           maxlength="100" 
                           placeholder="Video title (Required)" 
                           class="w-full bg-[#18191a] border border-[#3e4042] text-[#e4e6eb] px-3 py-3 rounded-xl text-[15px] focus:outline-none focus:border-[#2d88ff] transition">
                </div>

                {{-- Content --}}
                <div class="mb-2.5">
                    <input type="text" wire:model.live="content" maxlength="100" placeholder="What's on your mind?" class="w-full bg-[#18191a] border border-[#3e4042] text-[#e4e6eb] px-3 py-3 rounded-xl text-[15px] focus:outline-none focus:border-[#2d88ff] transition">
                </div>
                
                {{-- Description --}}
                <div class="mb-3">
                    <textarea wire:model.live="description" rows="3" class="w-full bg-[#18191a] border border-[#3e4042] text-[#e4e6eb] px-3 py-2.5 rounded-xl text-[15px] focus:outline-none focus:border-[#2d88ff] transition" placeholder="Video description (story, details - unlimited)"></textarea>
                </div>
                
                {{-- Category --}}
                <div class="my-2.5 mb-3">
                    <select wire:model.live="category" 
                            class="w-full bg-[#18191a] border border-[#3e4042] rounded-lg px-3 py-3 text-[#e4e6eb] text-[15px] focus:outline-none focus:border-[#2d88ff] transition">
                        <option value="">📂 Select Genre (Required)</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errorMessage && str_contains($errorMessage, 'genre/category'))
                        <div class="mt-1 text-red-500 text-xs">⚠️ {{ $errorMessage }}</div>
                    @endif
                </div>

                {{-- 18+ --}}
                <div class="flex items-center gap-2.5 px-3 py-2 bg-[#18191a] rounded-lg border border-[#3e4042] mb-3">
                    <label class="flex items-center gap-2 text-[#e4e6eb] text-sm cursor-pointer">
                        <input type="checkbox" 
                               wire:model.live="is_mature" 
                               value="1" 
                               class="w-4.5 h-4.5 accent-red-500">
                        <span>🔞 This content is for adults (18+)</span>
                    </label>
                    <span x-show="$wire.is_mature" class="text-[#2d88ff] text-[11px] bg-[#2d88ff20] px-2.5 py-0.5 rounded-full">Category not required</span>
                </div>
                
                {{-- Media Toolbar --}}
                <div class="flex items-center gap-2 px-3 py-2 bg-[#18191a] rounded-lg border border-[#3e4042] mb-3 flex-wrap">
                    
                    <label class="flex items-center gap-1.5 text-[#45bd62] cursor-pointer px-2 py-1 rounded-md hover:bg-[#3e4042] transition">
                        <span class="text-sm">🖼️ Photo</span>
                        <input type="file" x-ref="imageInput" @change="handleFileSelect($refs.imageInput, 'image')" accept="image/*" class="hidden">
                    </label>
                    
                    <label class="flex items-center gap-1.5 text-[#e74c3c] cursor-pointer px-2 py-1 rounded-md hover:bg-[#3e4042] transition">
                        <span class="text-sm">🎬 Video</span>
                        <input type="file" x-ref="videoInput" @change="handleFileSelect($refs.videoInput, 'video')" accept="video/*" class="hidden">
                    </label>
                    
                    <template x-if="showThumbnail">
                        <label class="flex items-center gap-1.5 text-[#f39c12] cursor-pointer px-2 py-1 rounded-md hover:bg-[#3e4042] transition">
                            <span class="text-sm">🎯 Thumbnail</span>
                            <input type="file" wire:model="video_thumbnail" accept="image/*" class="hidden">
                        </label>
                    </template>
                    
                    <span class="flex-1"></span>
                    <span class="text-[#8a8d91] text-[13px]" x-text="selectedFileName"></span>
                    
                    <button type="button" x-show="selectedFileName" @click="clearMedia()" class="bg-none border-none text-[#8a8d91] cursor-pointer text-base px-2 py-1 hover:text-white transition">✕</button>
                </div>

                {{-- Media Preview --}}
                <div x-show="mediaPreviewUrl" 
                     x-transition.duration.300ms
                     class="mb-3 rounded-xl overflow-hidden bg-[#18191a] border border-[#3e4042]">
                    
                    <div x-show="mediaType === 'video'" class="relative">
                        <video x-ref="videoPreview" 
                               :src="mediaPreviewUrl" 
                               controls 
                               class="w-full max-h-[300px] bg-black block">
                        </video>
                        <div class="absolute bottom-2.5 right-2.5 bg-black/70 text-white px-3 py-1 rounded-md text-xs pointer-events-none">
                            🎬 Video Preview
                        </div>
                    </div>
                    
                    <div x-show="mediaType === 'image'" class="relative">
                        <img :src="mediaPreviewUrl" 
                             class="w-full max-h-[300px] object-contain bg-black block">
                        <div class="absolute bottom-2.5 right-2.5 bg-black/70 text-white px-3 py-1 rounded-md text-xs pointer-events-none">
                            🖼️ Image Preview
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center px-3.5 py-2 bg-[#242526] border-t border-[#3e4042]">
                        <span class="text-[#b0b3b8] text-[13px]" x-text="selectedFileName"></span>
                        <span class="text-[#8a8d91] text-xs" x-text="fileSize"></span>
                    </div>
                </div>

                {{-- Error Message --}}
                @if($errorMessage && !str_contains($errorMessage, 'genre/category'))
                    <div class="mb-3 px-3 py-2.5 bg-red-500/20 border border-red-500 rounded-lg text-red-500">
                        {{ $errorMessage }}
                    </div>
                @endif
                
                {{-- ============================================ --}}
                {{-- 🔥 POST BUTTON - Tailwind + 2026 Icon --}}
                {{-- ============================================ --}}
                <button type="button" 
                        @click="uploadAndSave()" 
                        class="w-full py-4 rounded-2xl font-bold text-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3"
                        :class="isFormValid() ? 'bg-gradient-to-r from-[#2d88ff] to-[#0055cc] text-white shadow-lg shadow-[#2d88ff]/40 hover:shadow-[#2d88ff]/60 cursor-pointer' : 'bg-[#3e4042] text-[#8a8d91] cursor-not-allowed opacity-50 shadow-none'">
                    
                    {{-- 2026 Style POST Icon --}}
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <span>POST</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Alpine.js Data Registration --}}
    <script>
        document.addEventListener('alpine:init', function() {
            console.log('🎯 Alpine:init fired - registering createPostData');
            
            Alpine.data('createPostData', function() {
                return {
                    selectedFileName: '',
                    showThumbnail: false,
                    mediaPreviewUrl: '',
                    mediaType: '',
                    fileSize: '',
                    mediaFile: null,
                    
                    handleFileSelect(input, type) {
                        if (!input || !input.files || input.files.length === 0) {
                            this.clearMedia();
                            return;
                        }
                        
                        const file = input.files[0];
                        this.mediaFile = file;
                        this.selectedFileName = (type === 'video' ? '🎬 ' : '🖼️ ') + file.name;
                        this.fileSize = this.formatFileSize(file.size);
                        this.mediaType = type;
                        
                        if (this.mediaPreviewUrl) {
                            URL.revokeObjectURL(this.mediaPreviewUrl);
                        }
                        this.mediaPreviewUrl = URL.createObjectURL(file);
                        
                        if (type === 'video') {
                            this.showThumbnail = true;
                            this.$wire.video = file;
                            this.$wire.image = null;
                        } else {
                            this.showThumbnail = false;
                            this.$wire.image = file;
                            this.$wire.video = null;
                        }
                    },
                    
                    clearMedia() {
                        if (this.mediaPreviewUrl) {
                            URL.revokeObjectURL(this.mediaPreviewUrl);
                        }
                        this.mediaPreviewUrl = '';
                        this.selectedFileName = '';
                        this.showThumbnail = false;
                        this.mediaType = '';
                        this.fileSize = '';
                        this.mediaFile = null;
                        this.$wire.image = null;
                        this.$wire.video = null;
                        this.$wire.video_thumbnail = null;
                        
                        if (this.$refs.imageInput) this.$refs.imageInput.value = '';
                        if (this.$refs.videoInput) this.$refs.videoInput.value = '';
                        
                        this.$wire.call('clearMedia');
                    },
                    
                    formatFileSize(bytes) {
                        if (bytes === 0) return '0 B';
                        const k = 1024;
                        const sizes = ['B', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                    },
                    
                    isFormValid() {
                        let title = this.$wire.title || '';
                        let category = this.$wire.category || '';
                        let isMature = this.$wire.is_mature || false;
                        
                        if (!title || title.trim() === '') {
                            return false;
                        }
                        
                        if (!isMature && (!category || category === '')) {
                            return false;
                        }
                        
                        return true;
                    },
                    
                    uploadAndSave() {
                        if (!this.isFormValid()) {
                            console.warn('⚠️ Form is not valid!');
                            return;
                        }
                        
                        let fileInput = null;
                        let propertyName = '';
                        let progress = null;

                        console.log('🔍🔍🔍 uploadAndSave STARTED');

                        if (window.progressBars && window.progressBars['uploadProgress']) {
                            progress = window.progressBars['uploadProgress'];
                            console.log('✅ Progress bar found');
                        }

                        if (this.showThumbnail && this.$refs.videoInput && this.$refs.videoInput.files.length > 0) {
                            fileInput = this.$refs.videoInput;
                            propertyName = 'video';
                            console.log('🎬 Video file selected');
                        } else if (this.$refs.imageInput && this.$refs.imageInput.files.length > 0) {
                            fileInput = this.$refs.imageInput;
                            propertyName = 'image';
                            console.log('🖼️ Image file selected');
                        } else {
                            console.log('⚠️ No file selected');
                        }

                        if (fileInput && fileInput.files.length > 0) {
                            let file = fileInput.files[0];
                            let startTime = Date.now();

                            @this.set('showModal', false);
                            console.log('✅ Modal closed');

                            if (progress) {
                                progress.show('Uploading your post...');
                                progress.totalSize = file.size;
                                progress.update(0, 'Starting upload...', 0, file.size);
                            }

                            @this.upload(
                                propertyName, 
                                file, 
                                function(uploadedUrl) {
                                    console.log('🟢🟢🟢 UPLOAD SUCCESS!');
                                    
                                    if (progress) {
                                        progress.update(100, 'Saving post...', file.size, file.size);
                                    }
                                    
                                    @this.call('save');
                                    
                                    setTimeout(function() {
                                        if (progress) {
                                            progress.hide();
                                        }
                                    }, 100);
                                }, 
                                function(error) {
                                    console.error('🔴🔴🔴 UPLOAD ERROR:', error);
                                    if (progress) progress.hide();
                                    alert('Upload failed! File size might be too large.');
                                }, 
                                function(event) {
                                    let progressPercent = event.detail.progress;
                                    let uploaded = (progressPercent / 100) * file.size;

                                    if (progress) {
                                        progress.update(
                                            progressPercent, 
                                            'Uploading file to server...', 
                                            uploaded, 
                                            file.size
                                        );
                                    }
                                }
                            );
                        } else {
                            console.log('🟡🟡🟡 No file selected, saving directly');
                            @this.set('showModal', false);
                            
                            if (progress) {
                                progress.show('Saving your post...');
                                progress.update(100, 'Saving...', 1, 1);
                            }
                            
                            @this.call('save');
                            
                            setTimeout(function() {
                                if (progress) {
                                    progress.hide();
                                }
                            }, 100);
                        }
                    }
                };
            });
            
            console.log('✅ Alpine.data("createPostData") registered successfully');
        });
    </script>
</div>