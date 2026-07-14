<!-- resources/views/components/post/create-modal.blade.php -->
@props(['show' => false, 'id' => 'createPostModal'])

@php
    $displayStyle = $show ? 'flex' : 'none';
    $categories = App\Models\Post::getCategories();
@endphp

<div id="{{ $id }}" 
     style="display: {{ $displayStyle }}; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; animation: fadeIn 0.2s ease;">
    
    <div style="background: #242526; border-radius: 16px; width: 92%; max-width: 520px; max-height: 90vh; overflow-y: auto; box-shadow: 0 12px 40px rgba(0,0,0,0.5);">
        
        {{-- HEADER --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #3e4042; position: sticky; top: 0; background: #242526; border-radius: 16px 16px 0 0; z-index: 10;">
            <h3 style="font-size: 20px; font-weight: 700; margin: 0; color: #e4e6eb;">Create Post</h3>
            <button type="button" onclick="closeModal('{{ $id }}')" 
                    style="background: #3e4042; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #b0b3b8; transition: all 0.2s;">
                ✕
            </button>
        </div>
        
        {{-- FORM --}}
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="createPostForm">
            @csrf
            
            <div style="padding: 16px 20px 20px;">
                
                {{-- USER INFO --}}
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px; flex-shrink: 0;">
                        {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                    </div>
                    <div>
                        <div style="color: #e4e6eb; font-weight: 600; font-size: 15px;">{{ auth()->user()->name ?? 'User' }}</div>
                        <select name="privacy" style="background: #3e4042; border: none; padding: 2px 12px 2px 10px; border-radius: 6px; font-size: 12px; color: #b0b3b8; cursor: pointer; outline: none;">
                            <option value="public" style="background: #242526;">🌍 Public</option>
                            <option value="friends" style="background: #242526;">👥 Friends</option>
                            <option value="onlyme" style="background: #242526;">🔒 Only Me</option>
                        </select>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- TITLE INPUT - Max 100 characters --}}
                {{-- ============================================ --}}
                <div style="margin-bottom: 10px;">
                    <input type="text" 
                           name="title" 
                           id="titleInput"
                           maxlength="100"
                           value="{{ old('title') }}"
                           placeholder="Video title (max 100 characters)"
                           style="width: 100%; border: none; font-size: 16px; outline: none; font-family: inherit; color: #e4e6eb; background: #18191a; padding: 12px 16px; border-radius: 12px; border: 1px solid #3e4042; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#2d88ff'"
                           onblur="this.style.borderColor='#3e4042'">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; padding: 0 4px;">
                        <span style="font-size: 11px; color: #5a5d61;">Video title</span>
                        <span style="font-size: 11px; color: #5a5d61;">
                            <span id="titleCount">0</span> / 100
                        </span>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CONTENT - Max 100 characters --}}
                {{-- ============================================ --}}
                <div style="margin-bottom: 10px;">
                    <input type="text" 
                           name="content" 
                           id="contentInput"
                           maxlength="100"
                           value="{{ old('content') }}"
                           placeholder="What's on your mind? (max 100 characters)"
                           style="width: 100%; border: none; font-size: 16px; outline: none; font-family: inherit; color: #e4e6eb; background: #18191a; padding: 12px 16px; border-radius: 12px; border: 1px solid #3e4042; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#2d88ff'"
                           onblur="this.style.borderColor='#3e4042'">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; padding: 0 4px;">
                        <span style="font-size: 11px; color: #5a5d61;">What's on your mind?</span>
                        <span style="font-size: 11px; color: #5a5d61;">
                            <span id="contentCount">0</span> / 100
                        </span>
                    </div>
                </div>
                
                {{-- ============================================ --}}
                {{-- DESCRIPTION TEXTAREA (Video Info - Unlimited) --}}
                {{-- ============================================ --}}
                <div style="margin-bottom: 12px;">
                    <textarea name="description" 
                              id="descriptionInput"
                              rows="3" 
                              style="width: 100%; border: 1px solid #3e4042; font-size: 14px; outline: none; resize: none; font-family: inherit; color: #8a8d91; background: #18191a; padding: 10px 14px; border-radius: 12px; transition: all 0.3s;"
                              placeholder="Video description (story, details - unlimited)"
                              onfocus="this.style.borderColor='#2d88ff'"
                              onblur="this.style.borderColor='#3e4042'">{{ old('description') }}</textarea>
                    <div style="display: flex; justify-content: flex-end; margin-top: 4px; padding: 0 4px;">
                        <span style="font-size: 11px; color: #5a5d61;">
                            <span id="descCount">0</span> characters
                        </span>
                    </div>
                </div>
                
                {{-- ============================================ --}}
                {{-- CATEGORY DROPDOWN - Required --}}
                {{-- ============================================ --}}
                <div style="margin: 10px 0 12px 0;">
                    <select name="category" 
                            required
                            style="width: 100%; background: #18191a; border: 1px solid #3e4042; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #e4e6eb; outline: none; cursor: pointer; appearance: none;"
                            onchange="this.style.borderColor='#2d88ff'">
                        <option value="">📂 Select Genre (Required)</option>
                        <optgroup label="🎬 Action & Adventure">
                            <option value="action">Action & Adventure</option>
                            <option value="action_comedy">Action Comedy</option>
                            <option value="action_thriller">Action Thriller</option>
                            <option value="martial_arts">Martial Arts</option>
                            <option value="spy">Spy</option>
                        </optgroup>
                        <optgroup label="😂 Comedy">
                            <option value="comedy">Comedy</option>
                            <option value="romantic_comedy">Romantic Comedy</option>
                            <option value="dark_comedy">Dark Comedy</option>
                            <option value="standup">Stand-up Comedy</option>
                            <option value="satire">Satire</option>
                        </optgroup>
                        <optgroup label="😢 Drama">
                            <option value="drama">Drama</option>
                            <option value="period_drama">Period Drama</option>
                            <option value="crime_drama">Crime Drama</option>
                            <option value="teen_drama">Teen Drama</option>
                            <option value="melodrama">Melodrama</option>
                        </optgroup>
                        <optgroup label="❤️ Romance">
                            <option value="romance">Romance</option>
                            <option value="romantic_drama">Romantic Drama</option>
                        </optgroup>
                        <optgroup label="😱 Horror & Thriller">
                            <option value="horror">Horror</option>
                            <option value="thriller">Thriller</option>
                            <option value="psychological">Psychological</option>
                        </optgroup>
                        <optgroup label="🔬 Sci-Fi & Fantasy">
                            <option value="sci_fi">Sci-Fi</option>
                            <option value="fantasy">Fantasy</option>
                            <option value="superhero">Superhero</option>
                            <option value="space">Space</option>
                        </optgroup>
                        <optgroup label="📖 Documentary">
                            <option value="documentary">Documentary</option>
                            <option value="biography">Biography</option>
                            <option value="history">History</option>
                            <option value="nature">Nature</option>
                        </optgroup>
                        <optgroup label="🎵 Music">
                            <option value="music">Music</option>
                            <option value="concert">Concert</option>
                            <option value="musical">Musical</option>
                        </optgroup>
                        <optgroup label="⚽ Sports">
                            <option value="sports">Sports</option>
                            <option value="fitness">Fitness</option>
                        </optgroup>
                        <optgroup label="📚 Kids & Family">
                            <option value="kids">Kids</option>
                            <option value="family">Family</option>
                            <option value="animation">Animation</option>
                            <option value="anime">Anime</option>
                        </optgroup>
                        <optgroup label="📺 TV Shows">
                            <option value="crime">Crime</option>
                            <option value="reality">Reality TV</option>
                            <option value="talk_show">Talk Show</option>
                        </optgroup>
                        <optgroup label="🍳 Lifestyle">
                            <option value="cooking">Cooking</option>
                            <option value="travel">Travel</option>
                            <option value="fashion">Fashion</option>
                            <option value="education">Education</option>
                            <option value="technology">Technology</option>
                            <option value="gaming">Gaming</option>
                        </optgroup>
                        <optgroup label="📌 Other">
                            <option value="other">Other</option>
                        </optgroup>
                    </select>
                    @error('category')
                        <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">Please select a category</span>
                    @enderror
                </div>

                {{-- ============================================ --}}
                {{-- 18+ CONTENT CHECKBOX --}}
                {{-- ============================================ --}}
                <div style="display: flex; align-items: center; gap: 10px; margin: 8px 0 12px 0; padding: 8px 12px; background: #18191a; border-radius: 8px; border: 1px solid #3e4042;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #e4e6eb; font-size: 14px; cursor: pointer;">
                        <input type="checkbox" name="is_mature" value="1" id="matureCheckbox" 
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
                        <span>Photo</span>
                        <input type="file" name="image" accept="image/*" style="display: none;" onchange="handleImageSelect(this)">
                    </label>
                    
                    {{-- Video Button --}}
                    <label style="display: flex; align-items: center; gap: 6px; color: #e74c3c; font-size: 13px; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all 0.2s;"
                           onmouseover="this.style.background='#3e4042'"
                           onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;">🎬</span>
                        <span>Video</span>
                        <input type="file" name="video" accept="video/*" style="display: none;" onchange="handleVideoSelect(this)">
                    </label>
                    
                    {{-- Thumbnail Button --}}
                    <div id="thumbnailBtnContainer" style="display: none;">
                        <label style="display: flex; align-items: center; gap: 6px; color: #f39c12; font-size: 13px; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all 0.2s;"
                               onmouseover="this.style.background='#3e4042'"
                               onmouseout="this.style.background='transparent'">
                            <span style="font-size: 18px;">🎯</span>
                            <span>Thumbnail</span>
                            <input type="file" name="video_thumbnail" accept="image/*" style="display: none;" onchange="handleThumbnailSelect(this)">
                        </label>
                    </div>
                    
                    <span style="flex: 1;"></span>
                    
                    {{-- Selected file name --}}
                    <span id="selectedFileName" style="color: #8a8d91; font-size: 12px;"></span>
                    
                    {{-- Clear button --}}
                    <button type="button" onclick="clearAllMedia()" 
                            style="display: none; background: none; border: none; color: #8a8d91; font-size: 16px; cursor: pointer; padding: 0 4px;" 
                            id="clearMediaBtn">
                        ✕
                    </button>
                </div>

                {{-- ============================================ --}}
                {{-- MEDIA PREVIEW --}}
                {{-- ============================================ --}}
                <div id="mediaPreviewContainer" style="display: none; margin-bottom: 12px; position: relative;">
                    <div style="border-radius: 8px; overflow: hidden; border: 1px solid #3e4042; background: #000;">
                        <img id="imagePreview" src="" style="width: 100%; max-height: 280px; object-fit: contain; display: none;">
                        <video id="videoPreview" controls style="width: 100%; max-height: 280px; display: none; background: #000;">
                            <source id="videoSource" src="">
                        </video>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- OR DIVIDER --}}
                {{-- ============================================ --}}
                <div style="display: flex; align-items: center; margin: 12px 0;">
                    <div style="flex: 1; height: 1px; background: #3e4042;"></div>
                    <span style="padding: 0 14px; color: #5a5d61; font-size: 13px; font-weight: 500;">OR</span>
                    <div style="flex: 1; height: 1px; background: #3e4042;"></div>
                </div>

                {{-- ============================================ --}}
                {{-- LINK INPUT --}}
                {{-- ============================================ --}}
                <div>
                    <div style="display: flex; align-items: center; background: #18191a; border: 1px solid #3e4042; border-radius: 8px; padding: 4px 4px 4px 14px; transition: all 0.3s;">
                        <input type="url" 
                               name="link" 
                               id="linkInput"
                               value="{{ old('link') }}"
                               placeholder="Paste video link (YouTube, TikTok, etc.)" 
                               style="flex: 1; border: none; background: transparent; outline: none; font-size: 14px; padding: 10px 0; color: #e4e6eb;">
                        <button type="button" 
                                onclick="processLink()"
                                style="background: #2d88ff; color: white; border: none; padding: 6px 20px; border-radius: 6px; font-size: 14px; cursor: pointer; font-weight: 600; transition: all 0.2s;">
                            Add
                        </button>
                    </div>
                    <div style="margin-top: 6px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <span style="font-size: 11px; color: #5a5d61;">Supported:</span>
                        <span style="font-size: 11px; color: #2d88ff;">YouTube</span>
                        <span style="font-size: 11px; color: #2d88ff;">TikTok</span>
                        <span style="font-size: 11px; color: #2d88ff;">Instagram</span>
                        <span style="font-size: 11px; color: #2d88ff;">Facebook</span>
                        <span style="font-size: 11px; color: #2d88ff;">Twitter</span>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- LINK PREVIEW --}}
                {{-- ============================================ --}}
                <div id="linkPreviewContainer" style="display: none; margin-top: 12px; border: 1px solid #3e4042; border-radius: 8px; overflow: hidden; background: #18191a;">
                    <div style="display: flex; padding: 12px 14px; align-items: center;">
                        <div id="linkThumbnail" style="width: 80px; height: 48px; background: #3e4042; border-radius: 6px; margin-right: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <span style="font-size: 24px;">🎬</span>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div id="linkTitle" style="color: #e4e6eb; font-weight: 500; font-size: 13px; margin-bottom: 2px;">Loading video info...</div>
                            <div id="linkDomain" style="color: #5a5d61; font-size: 11px;"></div>
                        </div>
                        <button type="button" onclick="clearLink()" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #5a5d61; padding: 4px;">✕</button>
                    </div>
                </div>
                
                {{-- ============================================ --}}
                {{-- POST BUTTON --}}
                {{-- ============================================ --}}
                <button type="submit" id="submitBtn" 
                        style="width: 100%; background: #2d88ff; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.2s; margin-top: 14px;">
                    Post
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}

#{{ $id }} > div::-webkit-scrollbar {
    width: 4px;
}
#{{ $id }} > div::-webkit-scrollbar-track {
    background: transparent;
}
#{{ $id }} > div::-webkit-scrollbar-thumb {
    background: #3e4042;
    border-radius: 10px;
}

#videoPreview::-webkit-media-controls {
    background: rgba(0,0,0,0.7);
}
#videoPreview::-webkit-media-controls-panel {
    background: rgba(0,0,0,0.7);
}

select[name="category"] option {
    padding: 8px;
}
select[name="category"] optgroup {
    color: #8a8d91;
    font-weight: 600;
    font-size: 12px;
}
</style>

<script>
// ============================================
// TITLE CHARACTER COUNTER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('titleInput');
    const titleCount = document.getElementById('titleCount');
    
    if (titleInput && titleCount) {
        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });
    }
});

// ============================================
// CONTENT CHARACTER COUNTER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const contentInput = document.getElementById('contentInput');
    const contentCount = document.getElementById('contentCount');
    
    if (contentInput && contentCount) {
        contentInput.addEventListener('input', function() {
            contentCount.textContent = this.value.length;
        });
    }
});

// ============================================
// DESCRIPTION CHARACTER COUNTER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const descInput = document.getElementById('descriptionInput');
    const descCount = document.getElementById('descCount');
    
    if (descInput && descCount) {
        descInput.addEventListener('input', function() {
            descCount.textContent = this.value.length;
        });
    }
});

// ============================================
// IMAGE SELECT
// ============================================
function handleImageSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    clearLink();
    clearVideoInput();
    showMediaPreview(file, 'image');
    document.getElementById('selectedFileName').textContent = '📷 ' + file.name;
    document.getElementById('clearMediaBtn').style.display = 'inline';
    document.getElementById('thumbnailBtnContainer').style.display = 'none';
}

// ============================================
// VIDEO SELECT
// ============================================
function handleVideoSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    clearLink();
    clearImageInput();
    showMediaPreview(file, 'video');
    document.getElementById('selectedFileName').textContent = '🎬 ' + file.name;
    document.getElementById('clearMediaBtn').style.display = 'inline';
    document.getElementById('thumbnailBtnContainer').style.display = 'block';
}

// ============================================
// THUMBNAIL SELECT
// ============================================
function handleThumbnailSelect(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('selectedFileName').textContent = '🎯 Thumbnail: ' + file.name;
}

// ============================================
// SHOW MEDIA PREVIEW
// ============================================
function showMediaPreview(file, type) {
    const container = document.getElementById('mediaPreviewContainer');
    const img = document.getElementById('imagePreview');
    const video = document.getElementById('videoPreview');
    const source = document.getElementById('videoSource');
    
    container.style.display = 'block';
    
    if (type === 'image') {
        img.style.display = 'block';
        video.style.display = 'none';
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        img.style.display = 'none';
        video.style.display = 'block';
        const url = URL.createObjectURL(file);
        source.src = url;
        video.load();
    }
}

// ============================================
// CLEAR FUNCTIONS
// ============================================
function clearImageInput() {
    document.querySelector('input[name="image"]').value = '';
}

function clearVideoInput() {
    document.querySelector('input[name="video"]').value = '';
    document.getElementById('thumbnailBtnContainer').style.display = 'none';
    document.querySelector('input[name="video_thumbnail"]').value = '';
}

function clearAllMedia() {
    document.querySelector('input[name="image"]').value = '';
    document.querySelector('input[name="video"]').value = '';
    document.querySelector('input[name="video_thumbnail"]').value = '';
    document.getElementById('mediaPreviewContainer').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('videoPreview').style.display = 'none';
    document.getElementById('imagePreview').src = '';
    document.getElementById('videoSource').src = '';
    document.getElementById('selectedFileName').textContent = '';
    document.getElementById('clearMediaBtn').style.display = 'none';
    document.getElementById('thumbnailBtnContainer').style.display = 'none';
}

// ============================================
// LINK PROCESSING (ပြင်ဆင်ထားတယ် - Vimeo အတွက်)
// ============================================
function processLink() {
    const linkInput = document.getElementById('linkInput');
    let link = linkInput.value.trim();
    
    if (!link) {
        alert('Please enter a link');
        return;
    }
    
    clearAllMedia();
    
    if (!link.startsWith('http://') && !link.startsWith('https://')) {
        alert('Please enter a valid URL starting with http:// or https://');
        return;
    }
    
    // =============================================
    // VIMEO LINK ကို သန့်ရှင်းအောင်လုပ်မယ်
    // =============================================
    if (link.includes('vimeo.com')) {
        // ? နဲ့စတဲ့ Parameters တွေကိုဖြုတ်မယ်
        link = link.split('?')[0];
        linkInput.value = link;
    }
    
    let domain = '';
    try {
        const url = new URL(link);
        domain = url.hostname.replace('www.', '');
    } catch (e) {
        domain = 'unknown';
    }
    
    document.getElementById('linkDomain').textContent = domain;
    
    const titles = {
        'youtube.com': '🎬 YouTube Video',
        'youtu.be': '🎬 YouTube Video',
        'tiktok.com': '🎵 TikTok Video',
        'instagram.com': '📸 Instagram Post',
        'facebook.com': '👥 Facebook Video',
        'fb.com': '👥 Facebook Video',
        'twitter.com': '🐦 Twitter Video',
        'x.com': '🐦 Twitter Video',
        'vimeo.com': '🎥 Vimeo Video'
    };
    
    let title = '🔗 External Link';
    for (const [key, value] of Object.entries(titles)) {
        if (domain.includes(key)) {
            title = value;
            break;
        }
    }
    
    document.getElementById('linkTitle').textContent = title;
    document.getElementById('linkPreviewContainer').style.display = 'block';
}

function clearLink() {
    document.getElementById('linkInput').value = '';
    document.getElementById('linkPreviewContainer').style.display = 'none';
}

// ============================================
// CLOSE MODAL
// ============================================
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    clearAllMedia();
    clearLink();
}

// ============================================
// FORM SUBMISSION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createPostForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = document.getElementById('titleInput').value.trim();
            const content = document.getElementById('contentInput').value.trim();
            const image = document.querySelector('input[name="image"]').files[0];
            const video = document.querySelector('input[name="video"]').files[0];
            const link = document.getElementById('linkInput').value.trim();
            
            if (!title && !content && !image && !video && !link) {
                e.preventDefault();
                alert('Please write a title, content, or add a photo/video/link to post.');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Posting...';
            submitBtn.style.opacity = '0.7';
        });
    }
    
    const modal = document.getElementById('{{ $id }}');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal('{{ $id }}');
            }
        });
    }
});
</script>