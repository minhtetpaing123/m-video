<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Services\BunnyStorageService;
use App\Jobs\ProcessVideoJob;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class CreatePost extends Component
{
    use WithFileUploads;

    public $postId = null; // ✅ Edit အတွက် Post ID သိမ်းရန်
    public $title = '';
    public $content = '';
    public $description = '';
    public $privacy = 'public';
    public $category = '';
    public $is_mature = false;
    
    public $image = null;
    public $video = null;
    public $video_thumbnail = null;

    public $showModal = false;
    public $errorMessage = '';

    protected $bunny;

    protected $listeners = [
        'open-create-post-modal' => 'openModal',
        'closeModal' => 'closeModal',
    ];

    public function boot(BunnyStorageService $bunny)
    {
        $this->bunny = $bunny;
    }

    public function openModal($postId = null)
    {
        $this->resetForm();

        // 🔥 Array အနေဖြင့် ပါလာပါက စစ်ဆေးယူခြင်း
        if (is_array($postId)) {
            $postId = $postId['postId'] ?? null;
        }

        // 🔥 Edit Mode: Post ID ပါလာပါက Data ဆွဲထုတ်ပြီး Form တွင် ဖြည့်ပေးမည်
        if ($postId) {
            $post = Post::find($postId);
            if ($post) {
                $this->postId = $post->id;
                $this->title = $post->title;
                $this->content = $post->content;
                $this->description = $post->description;
                $this->privacy = $post->privacy ?? 'public';
                $this->category = $post->category ?? '';
                $this->is_mature = (bool) $post->is_mature;
            }
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->postId = null; // ✅ Reset လုပ်ချိန်တွင် Post ID ပါ ရှင်းထုတ်မည်
        $this->title = '';
        $this->content = '';
        $this->description = '';
        $this->privacy = 'public';
        $this->category = '';
        $this->is_mature = false;
        $this->image = null;
        $this->video = null;
        $this->video_thumbnail = null;
        $this->errorMessage = '';
    }

    public function clearMedia()
    {
        $this->image = null;
        $this->video = null;
        $this->video_thumbnail = null;
    }

    public function save()
    {
        // Title က Required
        if (!$this->title || trim($this->title) === '') {
            $this->errorMessage = 'Please enter a title.';
            $this->showModal = true;
            return;
        }

        // 18+ မရွေးထားရင် Category က Required
        if (!$this->is_mature && !$this->category) {
            $this->errorMessage = 'Please select a genre/category.';
            $this->showModal = true;
            return;
        }

        try {
            // 🔥 Edit Mode (အဟောင်းပြင်ခြင်း) သို့မဟုတ် Create Mode (အသစ်တင်ခြင်း) ခွဲခြားခြင်း
            $post = $this->postId ? Post::find($this->postId) : new Post();

            if (!$post) {
                $post = new Post();
            }

            if (!$post->exists) {
                $post->user_id = Auth::id();
            }

            $post->title = $this->title;
            $post->content = $this->content ?? '';
            $post->description = $this->description;
            $post->privacy = $this->privacy;
            $post->category = $this->category;
            $post->is_mature = $this->is_mature ? true : false;
            
            if (!$post->exists) {
                $post->video_status = 'pending';
            }

            // 🔥 Image Upload - array ဖြစ်နေရင် ပြင်ဆင်
            if ($this->image) {
                $imageFile = $this->image;
                
                // 🔥 array ဖြစ်နေရင် ပထမ file ကိုယူ
                if (is_array($imageFile)) {
                    $imageFile = reset($imageFile);
                }
                
                if ($imageFile instanceof UploadedFile) {
                    $filename = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
                    $path = "images/{$filename}";
                    $this->bunny->upload(file_get_contents($imageFile->getRealPath()), $path, $imageFile->getMimeType());
                    $post->image = $path;
                }
            }

            // 🔥 Video Upload - array ဖြစ်နေရင် ပြင်ဆင်
            if ($this->video) {
                $videoFile = $this->video;
                
                // 🔥 array ဖြစ်နေရင် ပထမ file ကိုယူ
                if (is_array($videoFile)) {
                    $videoFile = reset($videoFile);
                }
                
                if ($videoFile instanceof UploadedFile) {
                    $filename = time() . '_' . Str::random(10) . '.' . $videoFile->getClientOriginalExtension();
                    $path = "videos/{$filename}";
                    
                    $result = $this->bunny->upload(file_get_contents($videoFile->getRealPath()), $path, $videoFile->getMimeType());

                    if ($result['success']) {
                        $post->video_path = $path;
                        $post->video_cdn_url = $result['cdn_url'];
                        $post->video_original = $videoFile->getClientOriginalName();
                        $post->video_size = $videoFile->getSize();
                        $post->video_status = 'uploaded';
                    }
                }
            }

            $post->save();

            if ($this->video && $post->video_path) {
                ProcessVideoJob::dispatch($post);
            }

            $this->dispatch('post-created');
            $this->closeModal(); // ✅ Save ပြီးရင် Modal ပိတ်ပြီး Form Reset လုပ်မည်

        } catch (\Exception $e) {
            Log::error('Error creating/updating post: ' . $e->getMessage());
            $this->errorMessage = 'Failed to save post.';
            $this->showModal = true;
        }
    }

    public function render()
    {
        return view('livewire.post.create-post', [
            'categories' => Post::getCategories()
        ]);
    }
}
