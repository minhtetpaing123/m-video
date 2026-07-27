<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;

class CreatePostCard extends Component
{
    public function openModal()
    {
        // Global event dispatch လုပ်ပြီး Create Post Modal ကို ဖွင့်ခိုင်းမည်
        $this->dispatch('open-create-post-modal');
    }

    public function render()
    {
        return view('livewire.dashboard.post.create-post-card');
    }
}
