<?php

namespace App\Livewire\Category;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('livewire.layout.home-layout')]
class Filter extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    
    public $category;
    public $perPage = 20;
    public $viewMode = 'grid';
    public $page = 1;

    public function mount($category)
    {
        $this->category = $category;
        $this->viewMode = session()->get('view_mode', 'grid');

        if (!auth()->check()) {
            session()->forget('url.intended');
        }
    }

    #[On('load-more')]
    public function loadMore()
    {
        $this->page++;
    }

    public function getPostsProperty()
    {
        $query = Post::query()
            ->where('privacy', 'public')
            ->whereNull('deleted_at');

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if ($this->category === '18_plus' || $this->category === '18plus') {
            $query->where('is_mature', 1);
        } else {
            $query->where('is_mature', 0);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'page', $this->page);
    }

    public function render()
    {
        $posts = $this->posts;

        return view('livewire.category.filter', [
            'posts' => $posts,
            'categoryLabel' => Post::getCategories()[$this->category] ?? ($this->category === 'all' ? 'All Posts' : $this->category),
            'viewMode' => $this->viewMode,
        ]);
    }
}