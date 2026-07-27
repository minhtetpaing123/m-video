<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Layout('livewire.layout.guest-layout')]
class ResetPassword extends Component
{
    public $token;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $message = '';

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('message', __('Password reset successfully! Please login.'));
            return redirect()->route('login');
        } else {
            $this->message = __('Invalid token or email.');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}