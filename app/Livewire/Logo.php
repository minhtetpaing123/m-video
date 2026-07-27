<?php

namespace App\Livewire;

use Livewire\Component;

class Logo extends Component
{
    public $subtitle = 'Create your account';

    public function render()
    {
        return view('livewire.logo');
    }
}