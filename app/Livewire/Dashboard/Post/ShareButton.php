<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;

class ShareButton extends Component
{
    public Post $post;

    public function sharePost()
    {
        // database ထဲက shares_count ကို +1 တိုးမည်
        $this->post->increment('shares_count');

        $this->dispatch('notify', [
            'message' => 'Post shared successfully!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.post.share-button');
    }
}
