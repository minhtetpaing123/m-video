<?php

namespace App\Livewire\Layout;

use App\Models\Post;
use Livewire\Component;

class Category extends Component
{
    public $categories = [];

    public function mount()
    {
        $this->categories = Post::getCategories();
    }

    /**
     * လက်ရှိ Category Active ဖြစ်မဖြစ် စစ်ပေးမည့် Function
     */
    public function isActive($slug)
    {
        // 18+ Route မဟုတ်ရင် သက်ဆိုင်ရာ slug ဟုတ်မဟုတ် စစ်မယ်
        if (request()->routeIs('category.18plus')) {
            return false;
        }

        return request()->route('category') === $slug;
    }

    /**
     * "All" Button Active ဖြစ်မဖြစ် စစ်ပေးမည့် Function
     */
    public function isAllActive()
    {
        // 1. Home Route ဖြစ်နေလျှင် သို့မဟုတ်
        // 2. Category Route ဖြစ်ပြီး category parameter မှာ 'all' သို့မဟုတ် null ဖြစ်နေလျှင်
        // 3. 18+ Route မဟုတ်လျှင်
        if (request()->routeIs('category.18plus')) {
            return false;
        }

        $currentCategory = request()->route('category');

        return request()->routeIs('home') || !$currentCategory || $currentCategory === 'all';
    }

    /**
     * 18+ Active ဖြစ်မဖြစ် စစ်ပေးမည့် Function
     */
    public function is18PlusActive()
    {
        return request()->routeIs('category.18plus') || request()->route('category') === '18_plus' || request()->route('category') === '18plus';
    }

    public function render()
    {
        $isHomePage = request()->routeIs('home') || request()->routeIs('category.filter') || request()->routeIs('category.18plus');

        return view('livewire.layout.category', [
            'isHomePage' => $isHomePage,
        ]);
    }
}
