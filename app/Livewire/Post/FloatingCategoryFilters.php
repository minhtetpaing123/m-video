<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\On; // 👈 Livewire v4 ရဲ့ Event Listener Attribute

class FloatingCategoryFilters extends Component
{
    public $selectedCategory = null;

    /**
     * Livewire v4 တွင် Event ကို Listen လုပ်ရန် #[On] Attribute ကို သုံးပါသည်
     */
    #[On('categorySelected')]
    public function setCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function filterCategory($category)
    {
        $this->selectedCategory = $category;
        
        // Livewire v4 standard စံနှုန်းအတိုင်း Event dispatch လုပ်ခြင်း
        $this->dispatch('filter-by-category', category: $category);
    }

    public function render()
    {
        return view('livewire.post.floating-category-filters', [
            'categories' => Post::getCategories()
        ]);
    }
}
