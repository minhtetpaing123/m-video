<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;

class PostPage extends Component
{
    public $page = 1;
    public $pinnedPostId = null;

    public function mount($page = 1, $pinnedPostId = null)
    {
        $this->page = $page;
        $this->pinnedPostId = $pinnedPostId;
    }

    public function render()
    {
        $blockedIds = auth()->check() ? auth()->user()->blocked_user_ids : [];

        $posts = Post::with('user')
                    ->when(!empty($blockedIds), function ($query) use ($blockedIds) {
                        $query->whereNotIn('user_id', $blockedIds);
                    })
                    ->when($this->pinnedPostId && $this->page === 1, function ($query) {
                        $query->orderByRaw("FIELD(id, ?) DESC", [$this->pinnedPostId]);
                    })
                    ->latest()
                    ->paginate(10, ['*'], 'page', $this->page);

        return view('livewire.dashboard.post.post-page', [
            'posts' => $posts
        ]);
    }
}
