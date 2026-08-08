<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\On;

class FloatingCategoryFilters extends Component
{
    public $selectedCategory = null;
    public bool $showFilter = false; // Menu ပွင့်/ပိတ် ထိန်းချုပ်မည့် State

    public function toggleFilter()
    {
        $this->showFilter = !$this->showFilter;
    }

    #[On('categorySelected')]
    public function setCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function filterCategory($category)
    {
        $this->selectedCategory = $category;
        $this->showFilter = false; // Category တစ်ခုခု နှိပ်လိုက်ပါက Menu အလိုအလျောက် ပြန်ပိတ်သွားမည်
        
        $this->dispatch('filter-by-category', category: $category);
    }

    public function render()
    {
        return view('livewire.post.floating-category-filters', [
            'categories' => Post::getCategories()
        ]);
    }
}
