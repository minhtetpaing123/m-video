<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Post;

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
        // Livewire v4 Array/Object data parameter mapping
        $postId = is_array($data) ? ($data['postId'] ?? null) : $data;

        if ($postId) {
            $post = Post::withTrashed()->find($postId);
            
            if ($post) {
                $post->restore();
                $this->restoredPostId = $postId;
                
                // ⚡ Frontend AlpineJS ဆီ ပြန်ပြဖို့ ပို့ပေးမယ်
                $this->dispatch('post-restored', postId: $postId);
                
                // ⚡ Success Notification ပြမယ်
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
        // ⚡ ဒီနေရာမှာ စာမျက်နှာကို Reset မလုပ်သေးပါဘူး (ဒါမှ ဒေတာပျောက်မသွားဘဲ Toast က ဆက်ပေါ်နေမှာပါ)
    }

    // 🔥 Post သစ်တင်လိုက်ရင် component ကို refresh လုပ်မယ်
    #[On('post-created')]
    public function refreshPosts()
    {
        // 🔥 Pagination ကို reset လုပ်ပြီး ပထမစာမျက်နှာကိုပြမယ်
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::with('user')
                    ->latest()
                    ->paginate(10);

        return view('livewire.dashboard.index', [
            'posts' => $posts
        ])->layout('layouts.app');
    }
}