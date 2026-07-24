<?php

namespace App\Livewire\Category;

use App\Models\Post;
use Livewire\Component;

class FloatingCategoryFilters extends Component
{
    public $showHome = true;
    public $show18plus = true;
    public $isOpen = false;

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function setIsOpen($value)
    {
        $this->isOpen = $value;
    }

    public function getCategoriesProperty()
    {
        return Post::getCategories();
    }

    public function render()
    {
        return view('livewire.category.floating-category-filters', [
            'categories' => $this->categories,
        ]);
    }
}