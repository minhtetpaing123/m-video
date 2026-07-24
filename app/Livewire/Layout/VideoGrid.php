<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Livewire\Attributes\On;

class VideoGrid extends Component
{
    public $posts;
    public $emptyMessage = 'No posts found';
    public $cardType = 'guest';
    public $hasMorePages = false;
    public $viewMode = 'grid';

    public function mount($posts, $emptyMessage = 'No posts found', $cardType = 'guest', $hasMorePages = false)
    {
        $this->posts = $posts;
        $this->emptyMessage = $emptyMessage;
        $this->cardType = $cardType;
        $this->hasMorePages = $hasMorePages;
        
        // Session ကနေ viewMode ကိုဖတ်မယ်
        $this->viewMode = session()->get('view_mode', 'grid');
    }

    #[On('view-mode-changed')]
    public function updateViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        return view('livewire.layout.video-grid');
    }
}