<?php

namespace App\Livewire\Category;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('livewire.layout.home-layout')]
class EighteenPlus extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    
    public $perPage = 20;
    public $showWarning = true;

    public function confirmAge()
    {
        // ✅ Warning ကိုပိတ်ပြီး Content ကိုပြမယ်
        $this->showWarning = false;
    }

    public function render()
    {
        $posts = Post::query()
            ->where('privacy', 'public')
            ->where('is_mature', 1)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.category.eighteen-plus', [
            'posts' => $posts
        ]);
    }
}