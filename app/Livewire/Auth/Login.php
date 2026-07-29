<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Events\UserStatusChanged;

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

        // ✅ Login ဝင်တာနဲ့ Online ဖြစ်ကြောင်း Broadcast
        broadcast(new UserStatusChanged($user, true));

        return redirect()->intended('/');
    }

    /**
     * ✅ Logout Method
     */
    public function logout()
    {
        $user = auth()->user();
        
        if ($user) {
            // ✅ Logout ထွက်တာနဲ့ last_seen_at ကို Update လုပ်
            $user->last_seen_at = now();
            $user->save();
            
            // ✅ Offline ဖြစ်ကြောင်း Broadcast
            broadcast(new UserStatusChanged($user, false));
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}