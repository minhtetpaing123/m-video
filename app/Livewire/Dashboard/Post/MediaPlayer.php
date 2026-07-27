<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;

class MediaPlayer extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.dashboard.post.media-player');
    }
}
