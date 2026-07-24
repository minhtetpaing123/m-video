<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class FloatingCategoryFilters extends Component
{
    public $showHome = true;
    public $show18plus = true;
    public $open = false;

    public function mount($showHome = true, $show18plus = true)
    {
        $this->showHome = $showHome;
        $this->show18plus = $show18plus;
    }

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function render()
    {
        $categories = Post::getCategories();
        return view('livewire.floating-category-filters', [
            'categories' => $categories,
        ]);
    }
}