<div>
    {{-- ============================================ --}}
    {{-- PROGRESS BAR (Livewire v4 - Alpine.js) --}}
    {{-- ============================================ --}}
    @if($showProgress)
        <div x-data
             x-init="$watch('$wire.progress', value => {
                 if (value >= 100) {
                     setTimeout(() => {
                         $wire.showProgress = false;
                         $wire.dispatch('hide-progress');
                     }, 3000);
                 }
             })"
             class="fixed top-0 left-0 right-0 z-[9999] bg-[#18191a] p-4 border-b-2 border-[#2d88ff] shadow-lg">
            <div class="max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-white text-sm font-medium" x-text="$wire.progressStatus"></span>
                    <span class="text-white text-sm font-bold" x-text="$wire.progress + '%'"></span>
                </div>
                <div class="w-full h-2 bg-[#3e4042] rounded-full overflow-hidden">
                    <div class="h-full bg-[#2d88ff] rounded-full transition-all duration-300" 
                         :style="'width: ' + $wire.progress + '%'"></div>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-[#5a5d61] text-xs">Uploading...</span>
                    <span class="text-[#5a5d61] text-xs">Please don't close the page</span>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================ --}}
    {{-- CREATE POST MODAL --}}
    {{-- ============================================ --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/60 z-[1000] flex items-center justify-center animate-fadeIn" 
             wire:click.self="closeModal">
            
            <div class="bg-[#242526] rounded-2xl w-[92%] max-w-[520px] max-h-[90vh] overflow-y-auto shadow-2xl">
                
                {{-- HEADER --}}
                <div class="flex justify-between items-center px-5 py-4 border-b border-[#3e4042] sticky top-0 bg-[#242526] rounded-t-2xl z-10">
                    <h3 class="text-xl font-bold m-0 text-[#e4e6eb]">Create Post</h3>
                    <button type="button" wire:click="closeModal" 
                            class="bg-[#3e4042] border-none w-9 h-9 rounded-full text-xl cursor-pointer flex items-center justify-center text-[#b0b3b8] hover:bg-[#4e5052] transition-all duration-200">
                        ✕
                    </button>
                </div>
                
                {{-- FORM --}}
                <form wire:submit.prevent="submit" enctype="multipart/form-data" class="px-5 py-4 pb-5">
                    
                    {{-- USER INFO --}}
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#667eea] to-[#764ba2] flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                            {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                        </div>
                        <div>
                            <div class="text-[#e4e6eb] font-semibold text-[15px]">{{ auth()->user()->name ?? 'User' }}</div>
                            <select wire:model="privacy" class="bg-[#3e4042] border-none py-0.5 px-3 pr-2.5 rounded-md text-xs text-[#b0b3b8] cursor-pointer outline-none">
                                <option value="public">🌍 Public</option>
                                <option value="friends">👥 Friends</option>
                                <option value="onlyme">🔒 Only Me</option>
                            </select>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-2.5">
                        <input type="text" 
                               wire:model.live="title"
                               maxlength="100"
                               placeholder="Video title (max 100 characters)"
                               class="w-full border-none text-base outline-none font-inherit text-[#e4e6eb] bg-[#18191a] py-3 px-4 rounded-xl border border-[#3e4042] transition-all duration-300 focus:border-[#2d88ff]">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <div class="flex justify-between items-center mt-1 px-1">
                            <span class="text-[11px] text-[#5a5d61]">Video title</span>
                            <span class="text-[11px] text-[#5a5d61]">
                                <span>{{ strlen($title) }}</span> / 100
                            </span>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="mb-2.5">
                        <input type="text" 
                               wire:model.live="content"
                               maxlength="100"
                               placeholder="What's on your mind? (max 100 characters)"
                               class="w-full border-none text-base outline-none font-inherit text-[#e4e6eb] bg-[#18191a] py-3 px-4 rounded-xl border border-[#3e4042] transition-all duration-300 focus:border-[#2d88ff]">
                        @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <div class="flex justify-between items-center mt-1 px-1">
                            <span class="text-[11px] text-[#5a5d61]">What's on your mind?</span>
                            <span class="text-[11px] text-[#5a5d61]">
                                <span>{{ strlen($content) }}</span> / 100
                            </span>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <textarea wire:model.live="description"
                                  rows="3"
                                  placeholder="Video description (story, details - unlimited)"
                                  class="w-full border border-[#3e4042] text-sm outline-none resize-none font-inherit text-[#8a8d91] bg-[#18191a] py-2.5 px-3.5 rounded-xl transition-all duration-300 focus:border-[#2d88ff]"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- CATEGORY --}}
                    <div class="my-2.5 mb-3">
                        <select wire:model.live="category"
                                class="w-full bg-[#18191a] border border-[#3e4042] rounded-lg py-2.5 px-3.5 text-sm text-[#e4e6eb] outline-none cursor-pointer appearance-none focus:border-[#2d88ff]">
                            <option value="">📂 Select Genre {{ $is_mature ? '(Optional)' : '(Required)' }}</option>
                            @foreach($this->categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- 18+ --}}
                    <div class="flex items-center gap-2.5 my-2 mb-3 py-2 px-3 bg-[#18191a] rounded-lg border border-[#3e4042]">
                        <label class="flex items-center gap-2 text-[#e4e6eb] text-sm cursor-pointer">
                            <input type="checkbox" wire:model="is_mature" class="w-[18px] h-[18px] accent-red-500 cursor-pointer">
                            <span class="text-lg">🔞</span>
                            <span>This content is for adults (18+)</span>
                        </label>
                    </div>

                    {{-- MEDIA TOOLBAR --}}
                    <div class="flex items-center gap-2 py-2 px-3 bg-[#18191a] rounded-lg border border-[#3e4042] my-2 mb-3 flex-wrap">
                        
                        <label class="flex items-center gap-1.5 text-[#45bd62] text-[13px] cursor-pointer py-1 px-2.5 rounded-md transition-all duration-200 hover:bg-[#3e4042]">
                            <span class="text-lg">🖼️</span>
                            <span>Photo</span>
                            <input type="file" wire:model="image" accept="image/*" class="hidden">
                        </label>
                        
                        <label class="flex items-center gap-1.5 text-red-500 text-[13px] cursor-pointer py-1 px-2.5 rounded-md transition-all duration-200 hover:bg-[#3e4042]">
                            <span class="text-lg">🎬</span>
                            <span>Video</span>
                            <input type="file" wire:model="video" accept="video/*" class="hidden">
                        </label>
                        
                        @if($video)
                            <label class="flex items-center gap-1.5 text-yellow-500 text-[13px] cursor-pointer py-1 px-2.5 rounded-md transition-all duration-200 hover:bg-[#3e4042]">
                                <span class="text-lg">🎯</span>
                                <span>Thumbnail</span>
                                <input type="file" wire:model="video_thumbnail" accept="image/*" class="hidden">
                            </label>
                        @endif
                        
                        <span class="flex-1"></span>
                        <span class="text-[#8a8d91] text-xs">
                            @if($image) 📷 {{ $image->getClientOriginalName() }} @endif
                            @if($video) 🎬 {{ $video->getClientOriginalName() }} @endif
                        </span>
                    </div>

                    {{-- MEDIA PREVIEW --}}
                    @if($image || $video)
                        <div class="mb-3 relative">
                            <div class="rounded-lg overflow-hidden border border-[#3e4042] bg-black">
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full max-h-[280px] object-contain">
                                @endif
                                @if($video)
                                    <video controls class="w-full max-h-[280px] bg-black">
                                        <source src="{{ $video->temporaryUrl() }}">
                                    </video>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- SUBMIT --}}
                    <button type="submit" 
                            class="w-full bg-[#2d88ff] text-white border-none py-3 rounded-lg font-semibold text-base cursor-pointer transition-all duration-200 mt-3.5 hover:bg-[#1a7ae6] disabled:opacity-70 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Post</span>
                        <span wire:loading wire:target="submit">Uploading...</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease;
    }
    </style>
</div>