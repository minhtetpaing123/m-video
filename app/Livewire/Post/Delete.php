<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Models\Post;

class Delete extends Component
{
    public Post $post;
    public $isOpen = false;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function deletePost()
    {
        try {
            // ⚡ Soft Delete
            $this->post->delete();
            
            // ⚡ Event ပို့ပါ
            $this->dispatch('post-deleted', postId: $this->post->id);
            
            // ⚡ Toast Notification with Undo
            $this->dispatch('notify', [
                'message' => 'Post deleted!',
                'type' => 'success',
                'undo' => true,
                'postId' => $this->post->id,
                'postTitle' => $this->post->title ?? 'Untitled'
            ]);
            
            // ⚡ Close modal
            $this->isOpen = false;
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to delete post! ❌',
                'type' => 'error'
            ]);
        }
    }

    // ⚡ Force Delete
    public function forceDelete()
    {
        try {
            $post = Post::withTrashed()->find($this->post->id);
            if ($post) {
                $post->forceDelete();
            }
        } catch (\Exception $e) {
            // Silent fail
        }
    }

    public function render()
    {
        return view('livewire.post.delete');
    }
}