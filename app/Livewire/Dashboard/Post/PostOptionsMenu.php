<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;

class PostOptionsMenu extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function savePost()
    {
        // Save Post Logic
        $this->dispatch('notify', [
            'message' => 'Post saved successfully! 🔖',
            'type' => 'success'
        ]);
    }

    public function editPost()
    {
        // CreatePostModal ထံသို့ Post ID ပါဝင်သော event ကိုDispatch လုပ်၍ Edit Mode ဖွင့်ခိုင်းခြင်း
        $this->dispatch('open-create-post-modal', postId: $this->post->id);
    }

    public function deletePost()
    {
        // Soft delete logic with toast notification
        $this->dispatch('notify', [
            'message' => 'Post deleted! 🗑️',
            'type' => 'info',
            'undo' => true,
            'postId' => $this->post->id
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.post.post-options-menu');
    }
}
