<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Layout('livewire.layout.guest-layout')]
class Login extends Component
{
    public $login = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'login' => 'required|string',
        'password' => 'required|string',
    ];

    protected $messages = [
        'login.required' => 'Email or Phone number is required.',
        'password.required' => 'Password is required.',
    ];

    public function login()
    {
        $this->validate();

        $loginValue = trim($this->login);

        // Email သို့မဟုတ် Phone နဲ့ User ကိုရှာပါ
        $user = User::where('email', $loginValue)
                    ->orWhere('phone', $loginValue)
                    ->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            $this->addError('login', 'Email/Phone or Password is incorrect.');
            return;
        }

        Auth::login($user, $this->remember);
        session()->regenerate();

        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}