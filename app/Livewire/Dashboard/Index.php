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
    public $pinnedPostId = null;

    // 🟢 စတင်ချိန်တွင် Post ၁၀ ခုပြမည်
    public $perPage = 10;

    // 🟢 Scroll အောက်ရောက်ပါက နောက်ထပ် 10 ခု ထပ်တိုးမည်
    public function loadMore()
    {
        $this->perPage += 10;
    }

    #[On('pin-post-to-top')]
    public function pinPost($postId = null)
    {
        $this->pinnedPostId = is_array($postId) ? ($postId['postId'] ?? null) : $postId;
        $this->perPage = 10;
        $this->resetPage();
    }

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
        // Toast
    }

    #[On('post-created')]
    #[On('user-blocked')]
    #[On('refresh-feed')]
    public function refreshPosts()
    {
        $this->perPage = 10;
        $this->resetPage();
    }

    public function render()
    {
        $blockedIds = auth()->check() ? auth()->user()->blocked_user_ids : [];

        $posts = Post::with('user')
                    ->when(!empty($blockedIds), function ($query) use ($blockedIds) {
                        $query->whereNotIn('user_id', $blockedIds);
                    })
                    ->when($this->pinnedPostId, function ($query) {
                        $query->orderByRaw("FIELD(id, ?) DESC", [$this->pinnedPostId]);
                    })
                    ->latest()
                    ->paginate($this->perPage);

        return view('livewire.dashboard.index', [
            'posts' => $posts
        ]);
    }
}
