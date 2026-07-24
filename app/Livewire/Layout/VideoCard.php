<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class VideoCard extends Component
{
    public $post;
    public $index = 0;
    public $type = 'guest';
    public $viewMode = 'grid';

    public function mount($post, $index = 0, $type = 'guest', $viewMode = 'grid')
    {
        $this->post = $post;
        $this->index = $index;
        $this->type = $type;
        $this->viewMode = $viewMode;
    }

    public function render()
    {
        return view('livewire.layout.video-card');
    }
}