<?php

namespace App\Livewire\Player;

use Livewire\Component;

class VideoPlayer extends Component
{
    public $post;
    public $videoUrl;
    public $thumbnailUrl;
    
    // Event listener for auto pause
    protected $listeners = ['pauseAllVideos' => 'pauseVideo'];

    public function mount($post)
    {
        $this->post = $post;
        $this->videoUrl = $post->video_url;
        $this->thumbnailUrl = $post->video_thumbnail_url;
    }

    public function pauseVideo()
    {
        $this->dispatch('pauseVideoPlayer');
    }

    public function render()
    {
        return view('livewire.player.video-player');
    }
}