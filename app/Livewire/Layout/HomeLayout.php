<?php

namespace App\Livewire\Layout;  // ✅ ဒီ namespace မှန်ကန်ဖို့လိုတယ်

use Livewire\Component;

class HomeLayout extends Component
{
    public function render()
    {
        return view('livewire.layout.home-layout');
    }
}