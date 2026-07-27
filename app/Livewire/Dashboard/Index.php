<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use App\Models\Post;

#[Layout('livewire.layout.app')]
class Index extends Component
{
    use WithPagination;

    public $notification = null;
    public $restoredPostId = null;

    #[On('notify')]
    public function showNotification($data = null)
    {
        if ($data) {
            $this->notification = $data;
        }
    }

    #[On('clear-notification')]
    public function clearNotification()
    {
        $this->notification = null;
    }

    #[On('undo-delete')]
    public function undoDelete($data = null)
    {
        $postId = is_array($data) ? ($data['postId'] ?? null) : $data;

        if ($postId) {
            $post = Post::withTrashed()->find($postId);
            
            if ($post) {
                $post->restore();
                $this->restoredPostId = $postId;
                $this->dispatch('post-restored', postId: $postId);
                $this->notification = [
                    'message' => 'Post restored successfully! ✅',
                    'type' => 'success',
                    'undo' => false
                ];
            }
        }
    }

    #[On('force-delete')]
    public function forceDelete($data = null)
    {
        $postId = is_array($data) ? ($data['postId'] ?? null) : $data;

        if ($postId) {
            $post = Post::withTrashed()->find($postId);
            if ($post) {
                $post->forceDelete();
            }
        }
    }

    #[On('post-deleted')]
    public function handlePostDeleted($postId = null)
    {
        // Toast ဆက်ပေါ်နေမှာပါ
    }

    #[On('post-created')]
    public function refreshPosts()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::with('user')
                    ->latest()
                    ->paginate(10);

        return view('livewire.dashboard.index', [
            'posts' => $posts
        ]);
    }
}