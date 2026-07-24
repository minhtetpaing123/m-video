<?php

namespace App\Livewire\Home;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

#[Layout('livewire.layout.home-layout')]
class Home extends Component
{
    #[Url(history: true)]
    public $page = 1;

    public $perPage = 12;
    public $hasMorePages = true;
    public $posts = [];
    public $totalPosts = 0;

    public function mount()
    {
        if ($this->page == 1) {
            $this->posts = [];
        }
        
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $query = Post::query()
            ->whereNull('deleted_at')
            ->where('is_mature', 0)
            ->orderBy('created_at', 'desc');

        $paginator = $query->paginate($this->perPage, ['*'], 'page', $this->page);

        if ($this->page > 1) {
            $allPosts = [];
            for ($i = 1; $i <= $this->page; $i++) {
                $pageQuery = Post::query()
                    ->whereNull('deleted_at')
                    ->where('is_mature', 0)
                    ->orderBy('created_at', 'desc')
                    ->paginate($this->perPage, ['*'], 'page', $i);
                
                $allPosts = array_merge($allPosts, $pageQuery->items());
            }
            $this->posts = $allPosts;
        } else {
            $this->posts = $paginator->items();
        }
        
        $this->hasMorePages = $paginator->hasMorePages();
        $this->totalPosts = count($this->posts);
    }

    #[On('load-more')]
    public function loadMore()
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadPosts();
            $this->dispatch('load-more-complete');
        }
    }

    public function render()
    {
        return view('livewire.home.home', [
            'posts' => $this->posts,
            'hasMorePages' => $this->hasMorePages,
            'currentPage' => $this->page,
            'totalPosts' => $this->totalPosts,
        ]);
    }
}