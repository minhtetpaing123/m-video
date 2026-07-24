<?php

namespace App\Livewire\Header;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserMenu extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        this->dispatch('authUpdated');
        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.header.user-menu');
    }
}
