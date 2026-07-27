<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;

#[Layout('livewire.layout.guest-layout')]
class ForgotPassword extends Component
{
    public $email = '';
    public $message = '';

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->message = __('We have emailed your password reset link!');
            $this->email = '';
        } else {
            $this->message = __('We could not find a user with that email address.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}