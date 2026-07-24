<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Models\Post;

class Feed extends Component
{
    public $post;

    public function mount(Post $post)
    {
        $this->post = $post;

        // Guest User ဖြစ်ပါက Session ငြိမနေစေရန် Clean လုပ်ခြင်း
        if (!auth()->check()) {
            session()->forget('url.intended');
        }
    }

    public function render()
    {
        // ✅ livewire.layout.home-layout သို့ လမ်းကြောင်းအတိအကျညွှန်ပြပေးထားပါသည်
        return view('livewire.post.feed')
            ->layout('livewire.layout.home-layout'); 
    }
}
