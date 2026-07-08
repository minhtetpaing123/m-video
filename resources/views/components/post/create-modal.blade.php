<!-- resources/views/components/post/create-modal.blade.php -->
@props(['show' => false, 'id' => 'createPostModal'])

@php
    $displayStyle = $show ? 'flex' : 'none';
@endphp

<div id="{{ $id }}" 
     style="display: {{ $displayStyle }}; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    
    <div style="background: white; border-radius: 16px; width: 90%; max-width: 550px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 30px rgba(0,0,0,0.3);">
        
        {{-- Modal Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #e0e0e0; position: sticky; top: 0; background: white; border-radius: 16px 16px 0 0;">
            <h3 style="font-size: 22px; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Create Post</h3>
            <button type="button" onclick="closeModal('{{ $id }}')" style="background: #f0f2f5; border: none; width: 38px; height: 38px; border-radius: 50%; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #050505; transition: all 0.3s;">✕</button>
        </div>
        
        {{-- Form Start --}}
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="createPostForm">
            @csrf
            
            <div style="padding: 20px;">
                {{-- Error Messages --}}
                @if($errors->any())
                    <div style="background: #fee; color: #c00; padding: 12px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #fcc;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Success Message --}}
                @if(session('success'))
                    <div style="background: #d4edda; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #c3e6cb;">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- User Info --}}
                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-right: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px; box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);">
                        {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #050505; margin-bottom: 4px; font-size: 16px;">{{ auth()->user()->name ?? 'User' }}</div>
                        <select name="privacy" style="background: #f0f2f5; border: none; padding: 6px 12px; border-radius: 20px; font-size: 13px; color: #050505; cursor: pointer; outline: none;">
                            <option value="public">🌍 Public</option>
                            <option value="friends">👥 Friends</option>
                            <option value="onlyme">🔒 Only Me</option>
                        </select>
                    </div>
                </div>
                
                {{-- Textarea --}}
                <textarea name="content" rows="4" 
                          style="width: 100%; border: none; font-size: 16px; outline: none; resize: none; margin-bottom: 20px; font-family: inherit; color: #050505; background: #f8f9fa; padding: 12px; border-radius: 12px;"
                          placeholder="What's on your mind, {{ auth()->user()->name ?? 'User' }}?">{{ old('content') }}</textarea>
                
                {{-- Combined Media Upload --}}
                <div style="margin-bottom: 20px;">
                    <div onclick="document.getElementById('mediaInput').click()" 
                         style="border: 2px dashed #667eea; border-radius: 12px; padding: 30px 20px; text-align: center; background: #f8faff; cursor: pointer; transition: all 0.3s;">
                        
                        <input type="file" name="media" accept="image/*,video/*" style="display: none;" id="mediaInput" onchange="handleMediaSelect(this)">
                        
                        <div style="font-size: 48px; margin-bottom: 10px;">📎</div>
                        <div style="font-size: 18px; font-weight: 600; color: #667eea; margin-bottom: 5px;">Add Photos/Videos</div>
                        <div style="font-size: 14px; color: #666;">Click to browse or drag and drop</div>
                        <div style="font-size: 12px; color: #999; margin-top: 8px;">JPG, PNG, GIF, MP4, MOV (Max 100MB)</div>
                        <div id="mediaFileName" style="margin-top: 10px; font-size: 14px; color: #667eea; font-weight: 500;"></div>
                    </div>
                </div>

                {{-- OR Divider --}}
                <div style="display: flex; align-items: center; margin: 20px 0;">
                    <div style="flex: 1; height: 1px; background: #e0e0e0;"></div>
                    <span style="padding: 0 15px; color: #999; font-size: 14px;">OR</span>
                    <div style="flex: 1; height: 1px; background: #e0e0e0;"></div>
                </div>

                {{-- External Link Input --}}
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 30px; padding: 5px 5px 5px 20px;">
                        <input type="url" 
                               name="link" 
                               id="linkInput"
                               value="{{ old('link') }}"
                               placeholder="Paste video link (unlimit data support, etc.)" 
                               style="flex: 1; border: none; background: transparent; outline: none; font-size: 14px; padding: 10px 0;">
                        <button type="button" 
                                onclick="processLink()"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 25px; border-radius: 25px; font-size: 14px; cursor: pointer; font-weight: 500;">
                            Add
                        </button>
                    </div>
                    <div style="margin-top: 8px; font-size: 12px; color: #999;">
                        Supported: YouTube, TikTok, Instagram, Facebook, Twitter
                    </div>
                </div>

                {{-- Media Preview --}}
                <div id="mediaPreviewContainer" style="display: none; margin-bottom: 20px; position: relative;">
                    <div style="border-radius: 12px; overflow: hidden; border: 1px solid #e0e0e0;">
                        <img id="imagePreview" src="" style="width: 100%; max-height: 300px; object-fit: contain; display: none;">
                        <video id="videoPreview" controls style="width: 100%; max-height: 300px; display: none;">
                            <source id="videoSource" src="">
                        </video>
                    </div>
                    <button type="button" onclick="clearMedia()" 
                            style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        ✕
                    </button>
                </div>

                {{-- Link Preview --}}
                <div id="linkPreviewContainer" style="display: none; margin-bottom: 20px; border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden;">
                    <div style="display: flex; padding: 12px; background: #f8f9fa;">
                        <div id="linkThumbnail" style="width: 120px; height: 68px; background: #e0e0e0; border-radius: 8px; margin-right: 12px; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 24px;">🎬</span>
                        </div>
                        <div style="flex: 1;">
                            <div id="linkTitle" style="font-weight: 600; margin-bottom: 4px;">Loading video info...</div>
                            <div id="linkDomain" style="font-size: 12px; color: #666;"></div>
                        </div>
                        <button type="button" onclick="clearLink()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #999;">✕</button>
                    </div>
                </div>
                
                {{-- Hidden inputs for actual submission --}}
                <input type="file" name="image" accept="image/*" style="display: none;" id="imageInput">
                <input type="file" name="video" accept="video/*" style="display: none;" id="videoInput">
                
                {{-- Post Button --}}
                <button type="submit" id="submitBtn" 
                        style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 14px; border-radius: 40px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                    Post
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Combined media handling
function handleMediaSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Clear link if any
    clearLink();
    
    // Show file name
    document.getElementById('mediaFileName').textContent = '✓ ' + file.name;
    
    // Prepare for submission
    if (file.type.startsWith('image/')) {
        // Set to image input
        const imageInput = document.getElementById('imageInput');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        imageInput.files = dataTransfer.files;
        
        // Show image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('videoPreview').style.display = 'none';
            document.getElementById('mediaPreviewContainer').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else if (file.type.startsWith('video/')) {
        // Set to video input
        const videoInput = document.getElementById('videoInput');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        videoInput.files = dataTransfer.files;
        
        // Show video preview
        const url = URL.createObjectURL(file);
        document.getElementById('videoSource').src = url;
        document.getElementById('videoPreview').load();
        document.getElementById('videoPreview').style.display = 'block';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('mediaPreviewContainer').style.display = 'block';
    }
}

// Clear media
function clearMedia() {
    document.getElementById('mediaInput').value = '';
    document.getElementById('imageInput').value = '';
    document.getElementById('videoInput').value = '';
    document.getElementById('mediaPreviewContainer').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('videoPreview').style.display = 'none';
    document.getElementById('imagePreview').src = '';
    document.getElementById('videoSource').src = '';
    document.getElementById('mediaFileName').textContent = '';
}

// Process link
function processLink() {
    const linkInput = document.getElementById('linkInput');
    const link = linkInput.value.trim();
    
    if (!link) {
        alert('Please enter a link');
        return;
    }
    
    // Clear media if any
    clearMedia();
    
    // Validate URL
    if (!link.startsWith('http://') && !link.startsWith('https://')) {
        alert('Please enter a valid URL starting with http:// or https://');
        return;
    }
    
    // Extract domain
    let domain = '';
    try {
        const url = new URL(link);
        domain = url.hostname.replace('www.', '');
    } catch (e) {
        domain = 'unknown';
    }
    
    // Show preview
    document.getElementById('linkDomain').textContent = domain;
    
    // Set title based on domain
    if (domain.includes('youtube.com') || domain.includes('youtu.be')) {
        document.getElementById('linkTitle').textContent = '🎬 YouTube Video';
    } else if (domain.includes('tiktok.com')) {
        document.getElementById('linkTitle').textContent = '🎵 TikTok Video';
    } else if (domain.includes('instagram.com')) {
        document.getElementById('linkTitle').textContent = '📸 Instagram Post';
    } else if (domain.includes('facebook.com') || domain.includes('fb.com')) {
        document.getElementById('linkTitle').textContent = '👥 Facebook Video';
    } else if (domain.includes('twitter.com') || domain.includes('x.com')) {
        document.getElementById('linkTitle').textContent = '🐦 Twitter Video';
    } else if (domain.includes('vimeo.com')) {
        document.getElementById('linkTitle').textContent = '🎥 Vimeo Video';
    } else {
        document.getElementById('linkTitle').textContent = '🔗 External Link';
    }
    
    document.getElementById('linkPreviewContainer').style.display = 'block';
}

// Clear link
function clearLink() {
    document.getElementById('linkInput').value = '';
    document.getElementById('linkPreviewContainer').style.display = 'none';
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    clearMedia();
    clearLink();
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createPostForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const content = form.querySelector('textarea[name="content"]').value.trim();
            const image = document.getElementById('imageInput').files[0];
            const video = document.getElementById('videoInput').files[0];
            const link = document.getElementById('linkInput').value.trim();
            
            if (!content && !image && !video && !link) {
                e.preventDefault();
                alert('Please write something or add a photo/video/link to post.');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            submitBtn.textContent = 'Posting...';
        });
    }
    
    // Close modal when clicking outside
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