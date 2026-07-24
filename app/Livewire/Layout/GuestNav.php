<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class GuestNav extends Component
{
    public $active = 'home';

    public function mount($active = 'home')
    {
        $this->active = $active;
    }

    public function render()
    {
        return view('livewire.layout.guest-nav');
    }
}