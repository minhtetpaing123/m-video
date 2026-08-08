<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;

class PostsFeedIsland extends Component
{
    public $posts;
    public $layoutMode;

    public function mount($posts, $layoutMode)
    {
        $this->posts = $posts;
        $this->layoutMode = $layoutMode;
    }

    public function render()
    {
        return view('livewire.dashboard.post.posts-feed-island');
    }
}