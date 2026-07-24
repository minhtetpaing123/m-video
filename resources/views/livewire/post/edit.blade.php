<div style="display: flex; align-items: center; justify-content: center; min-height: 90vh; padding: 20px 0; background: #18191a; font-family: inherit;">
    
    {{-- Livewire v4 Target Loading Indicator --}}
    <div wire:loading wire:target="image, video, video_thumbnail, update" style="position: fixed; top: 0; left: 0; width: 100%; height: 4px; background: #2d88ff; z-index: 9999; animation: pulse 1.5s infinite;"></div>

    <div style="background: #242526; border-radius: 16px; width: 92%; max-width: 520px; box-shadow: 0 12px 40px rgba(0,0,0,0.5); border: 1px solid #3e4042;">
        
        {{-- HEADER --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #3e4042;">
            <h3 style="font-size: 20px; font-weight: 700; margin: 0; color: #e4e6eb;">Edit Post</h3>
            <a href="{{ route('dashboard') }}" 
               style="background: #3e4042; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #b0b3b8; text-decoration: none; transition: all 0.2s;">
                ✕
            </a>
        </div>
        
        {{-- FORM --}}
        <form wire:submit="update">
            <div style="padding: 16px 20px 20px;">
                
                {{-- USER INFO --}}
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px; flex-shrink: 0;">
                        {{ substr($post->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <div style="color: #e4e6eb; font-weight: 600; font-size: 15px;">{{ $post->user->name ?? 'User' }}</div>
                        <select wire:model="privacy" style="background: #3e4042; border: none; padding: 2px 12px 2px 10px; border-radius: 6px; font-size: 12px; color: #b0b3b8; cursor: pointer; outline: none;">
                            <option value="public" style="background: #242526;">🌍 Public</option>
                            <option value="friends" style="background: #242526;">👥 Friends</option>
                            <option value="onlyme" style="background: #242526;">🔒 Only Me</option>
                        </select>
                    </div>
                </div>

                {{-- TITLE INPUT --}}
                <div style="margin-bottom: 10px;">
                    <input type="text" 
                           wire:model.live="title" 
                           maxlength="100"
                           placeholder="Video title (max 100 characters)"
                           style="width: 100%; font-size: 16px; outline: none; font-family: inherit; color: #e4e6eb; background: #18191a; padding: 12px 16px; border-radius: 12px; border: 1px solid #3e4042; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#2d88ff'"
                           onblur="this.style.borderColor='#3e4042'">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; padding: 0 4px;">
                        <span style="font-size: 11px; color: #5a5d61;">Video title</span>
                        <span style="font-size: 11px; color: #5a5d61;">
                            {{-- Pure Livewire v4 Reactive Character Counter --}}
                            <span>{{ strlen($title ?? '') }}</span> / 100
                        </span>
                    </div>
                    @error('title') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                {{-- CONTENT INPUT --}}
                <div style="margin-bottom: 10px;">
                    <input type="text" 
                           wire:model.live="content" 
                           maxlength="100"
                           placeholder="What's on your mind? (max 100 characters)"
                           style="width: 100%; font-size: 16px; outline: none; font-family: inherit; color: #e4e6eb; background: #18191a; padding: 12px 16px; border-radius: 12px; border: 1px solid #3e4042; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#2d88ff'"
                           onblur="this.style.borderColor='#3e4042'">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; padding: 0 4px;">
                        <span style="font-size: 11px; color: #5a5d61;">What's on your mind?</span>
                        <span style="font-size: 11px; color: #5a5d61;">
                            <span>{{ strlen($content ?? '') }}</span> / 100
                        </span>
                    </div>
                    @error('content') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
                
                {{-- DESCRIPTION TEXTAREA --}}
                <div style="margin-bottom: 12px;">
                    <textarea wire:model.live="description" 
                              rows="3" 
                              style="width: 100%; border: 1px solid #3e4042; font-size: 14px; outline: none; resize: none; font-family: inherit; color: #8a8d91; background: #18191a; padding: 10px 14px; border-radius: 12px; transition: all 0.3s;"
                              placeholder="Video description (story, details - unlimited)"
                              onfocus="this.style.borderColor='#2d88ff'"
                              onblur="this.style.borderColor='#3e4042'"></textarea>
                    <div style="display: flex; justify-content: flex-end; margin-top: 4px; padding: 0 4px;">
                        <span style="font-size: 11px; color: #5a5d61;">
                            <span>{{ strlen($description ?? '') }}</span> characters
                        </span>
                    </div>
                    @error('description') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
                
                {{-- CATEGORY SELECT --}}
                <div style="margin: 10px 0 12px 0;">
                    <select wire:model="category" 
                            required
                            style="width: 100%; background: #18191a; border: 1px solid #3e4042; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #e4e6eb; outline: none; cursor: pointer;"
                            onchange="this.style.borderColor='#2d88ff'">
                        <option value="">📂 Select Genre (Required)</option>
                        @foreach(App\Models\Post::getCategories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- 18+ MATURE CONTENT CHECKBOX --}}
                <div style="display: flex; align-items: center; gap: 10px; margin: 8px 0 12px 0; padding: 8px 12px; background: #18191a; border-radius: 8px; border: 1px solid #3e4042;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #e4e6eb; font-size: 14px; cursor: pointer;">
                        <input type="checkbox" wire:model="is_mature" id="matureCheckbox" 
                               style="width: 18px; height: 18px; accent-color: #e74c3c; cursor: pointer;">
                        <span style="font-size: 18px;">🔞</span>
                        <span>This content is for adults (18+)</span>
                    </label>
                </div>
                
                {{-- ============================================ --}}
                {{-- MEDIA TOOLBAR --}}
                {{-- ============================================ --}}
                <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #18191a; border-radius: 8px; border: 1px solid #3e4042; margin: 8px 0 12px 0; flex-wrap: wrap;">
                    
                    {{-- Photo Button --}}
                    <label style="display: flex; align-items: center; gap: 6px; color: #45bd62; font-size: 13px; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all 0.2s;"
                           onmouseover="this.style.background='#3e4042'"
                           onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;">🖼️</span>
                        <span>Change Photo</span>
                        <input type="file" wire:model="image" accept="image/*" style="display: none;">
                    </label>
                    
                    {{-- Video Button --}}
                    <label style="display: flex; align-items: center; gap: 6px; color: #e74c3c; font-size: 13px; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all 0.2s;"
                           onmouseover="this.style.background='#3e4042'"
                           onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;">🎬</span>
                        <span>Change Video</span>
                        <input type="file" wire:model="video" accept="video/*" style="display: none;">
                    </label>
                    
                    {{-- Livewire v4 Alpine-tied Thumbnail Button --}}
                    <div style="display: {{ ($video || ($existingVideoPath && !$image && !$clearExistingMedia)) ? 'block' : 'none' }};">
                        <label style="display: flex; align-items: center; gap: 6px; color: #f39c12; font-size: 13px; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all 0.2s;"
                               onmouseover="this.style.background='#3e4042'"
                               onmouseout="this.style.background='transparent'">
                            <span style="font-size: 18px;">🎯</span>
                            <span>Thumbnail</span>
                            <input type="file" wire:model="video_thumbnail" accept="image/*" style="display: none;">
                        </label>
                    </div>
                    
                    <span style="flex: 1;"></span>
                    <span style="color: #8a8d91; font-size: 12px;">
                        @if($image) 📷 New Photo Selected 
                        @elseif($video) 🎬 New Video Selected
                        @elseif($existingImage && !$clearExistingMedia) 🖼️ Current Photo
                        @elseif($existingVideoPath && !$clearExistingMedia) 🎥 Current Video
                        @else Media Empty
                        @endif
                    </span>
                    
                    {{-- Reset Media Trigger --}}
                    <button type="button" wire:click="$set('clearExistingMedia', true)"
                            style="display: {{ ($existingImage || $existingVideoPath || $image || $video) && !$clearExistingMedia ? 'inline' : 'none' }}; background: none; border: none; color: #8a8d91; font-size: 16px; cursor: pointer; padding: 0 4px;">
                        ✕
                    </button>
                </div>

                {{-- MEDIA DYNAMIC PREVIEW CONTAINER --}}
                @if(($existingImage || $existingVideoPath || $image || $video) && !$clearExistingMedia)
                <div style="margin-bottom: 12px; position: relative;">
                    <div style="border-radius: 8px; overflow: hidden; border: 1px solid #3e4042; background: #000;">
                        
                        {{-- Livewire New Image Temporary Preview --}}
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" style="width: 100%; max-height: 280px; object-fit: contain;">
                        {{-- Existing Bunny Image Preview --}}
                        @elseif ($existingImage && !$video)
                            <img src="{{ $existingImage }}" style="width: 100%; max-height: 280px; object-fit: contain;">
                        @endif

                        {{-- Livewire New Video Temporary Preview --}}
                        @if ($video)
                            <video controls style="width: 100%; max-height: 280px; background: #000; display: block;">
                                <source src="{{ $video->temporaryUrl() }}">
                            </video>
                        {{-- Existing Bunny CDN Video Preview --}}
                        @elseif ($post->video_cdn_url && !$image)
                            <video controls style="width: 100%; max-height: 280px; background: #000; display: block;">
                                <source src="{{ $post->video_cdn_url }}">
                            </video>
                        @endif
                        
                    </div>
                </div>
                @endif
                
                {{-- SUBMIT BUTTON --}}
                <button type="submit" id="submitBtn" wire:loading.attr="disabled"
                        style="width: 100%; background: #2d88ff; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.2s; margin-top: 14px;">
                    <span wire:loading.remove wire:target="update">Save Changes</span>
                    <span wire:loading wire:target="update">Uploading & Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes pulse {
    0% { opacity: 0.6; }
    50% { opacity: 1; }
    100% { opacity: 0.6; }
}
select option {
    padding: 8px;
    background: #242526;
}
</style>
