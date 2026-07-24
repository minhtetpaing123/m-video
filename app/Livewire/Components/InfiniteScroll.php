<?php

namespace App\Livewire\Components;

use Livewire\Component;

class InfiniteScroll extends Component
{
    public $hasMorePages = true;
    public $loadingText = 'Loading more...';
    public $endText = '🎉 You\'ve reached the end!';
    public $page = 1;

    public function mount($page = 1)
    {
        $this->page = $page;
    }

    public function loadMore()
    {
        $this->dispatch('load-more');
    }

    public function render()
    {
        return view('livewire.components.infinite-scroll', [
            'page' => $this->page,
        ]);
    }
}