<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Settings extends Component
{
    public function render()
    {
        return view('livewire.profile.settings')
            ->layout('layouts.app');
    }
}