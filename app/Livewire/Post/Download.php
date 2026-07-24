<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.home-layout')]
class Download extends Component
{
    public $post;

    public function mount($post)
    {
        $this->post = Post::with('user')->findOrFail($post);
    }

    public function render()
    {
        return view('livewire.post.download', [
            'post' => $this->post
        ]);
    }
}