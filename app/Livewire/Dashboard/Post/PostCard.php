<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;

class PostCard extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        // Post Card ကို render မလုပ်မီ views_count ကို 1 တိုးမည်
        $this->post->increment('views_count');
    }

    public function deletePost()
    {
        // Post ကို soft delete လုပ်ပြီး Main component သို့ notification dispatch ပေးမည်
        $postId = $this->post->id;
        $this->post->delete();

        $this->dispatch('post-deleted', postId: $postId);
        $this->dispatch('notify', [
            'message' => 'Post deleted successfully! 🗑️',
            'type' => 'info',
            'undo' => true,
            'postId' => $postId
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.post.post-card');
    }
}
