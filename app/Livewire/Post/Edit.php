<?php

namespace App\Models;

namespace App\Livewire\Post;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use App\Models\Post;

class Edit extends Component
{
    use WithFileUploads;

    public Post $post;
    
    // Livewire v4 Real-time Reactive Validation Properties
    #[Rule('nullable|string|max:100')]
    public $title = '';

    #[Rule('nullable|string|max:100')]
    public $content = '';

    #[Rule('nullable|string')]
    public $description = '';

    #[Rule('required|in:public,friends,onlyme')]
    public $privacy = 'public';

    #[Rule('required|string')]
    public $category = '';

    #[Rule('boolean')]
    public $is_mature = false;
    
    // Media Uploads
    #[Rule('nullable|image|max:10240')]
    public $image;

    #[Rule('nullable|file|mimetypes:video/mp4,video/quicktime,video/x-matroska|max:102400')]
    public $video;

    #[Rule('nullable|image|max:5120')]
    public $video_thumbnail;
    
    // Existing Media State Tracker
    public $existingImage;
    public $existingVideoPath;
    public $clearExistingMedia = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Livewire v4 Component Properties Initialization
        $this->title = $post->title;
        $this->content = $post->content;
        $this->description = $post->description;
        $this->privacy = $post->privacy;
        $this->category = $post->category;
        $this->is_mature = (bool) $post->is_mature;
        
        $this->existingImage = $post->image_url;
        $this->existingVideoPath = $post->video_path;
    }

    public function update()
    {
        $this->validate();

        $imageData = $this->post->image;
        
        if ($this->clearExistingMedia) {
            if ($this->post->video_path) {
                $this->post->deleteVideoFromBunny();
            }
            $imageData = null;
        }

        if ($this->image) {
            if ($this->post->video_path) {
                $this->post->deleteVideoFromBunny();
            }
            $imageData = $this->image->store('photos', 'public');
        }

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
            'description' => $this->description,
            'privacy' => $this->privacy,
            'category' => $this->category,
            'is_mature' => $this->is_mature,
            'image' => $imageData,
        ]);

        if ($this->video) {
            if ($this->post->video_path) {
                $this->post->deleteVideoFromBunny();
            }
            
            if ($this->video_thumbnail) {
                $thumbPath = $this->video_thumbnail->store('thumbnails', 'public');
                $this->post->update([
                    'video_thumbnail' => $thumbPath,
                    'video_thumbnail_url' => asset('storage/' . $thumbPath)
                ]);
            }

            $this->post->uploadVideoToBunny($this->video);
        }

        session()->flash('success', 'Post updated successfully!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.post.edit')->layout('layouts.app');
    }
}
