<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Events\UserStatusChanged;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function logout()
    {
        $user = auth()->user();
        
        if ($user) {
            $user->last_seen_at = now();
            $user->save();
            broadcast(new UserStatusChanged($user, false));
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}