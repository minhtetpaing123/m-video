<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Layout('livewire.layout.guest-layout')]
class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $agree = false;

    protected $rules = [
        'name' => 'required|min:2|max:255|unique:users,name',
        'email' => 'required|string|max:255',
        'password' => 'required|min:6|confirmed',
        'agree' => 'accepted',
    ];

    protected $messages = [
        'name.required' => 'Full name is required.',
        'name.min' => 'Name must be at least 2 characters.',
        'name.unique' => 'This name is already taken. Please choose a different name.',
        'email.required' => 'Email or Phone number is required.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 6 characters.',
        'password.confirmed' => 'Passwords do not match.',
        'agree.accepted' => 'You must agree to the Terms of Service and Privacy Policy.',
    ];

    // Real-time Validation
    public function updated($field)
    {
        $this->validateOnly($field);
        
        if ($field === 'name' && !empty($this->name)) {
            $this->checkNameExists($this->name);
        }
        
        if ($field === 'email' && !empty($this->email)) {
            $this->checkEmailOrPhoneExists($this->email);
        }
    }

    // Real-time Name Duplicate Check
    public function checkNameExists($value)
    {
        if (User::where('name', $value)->exists()) {
            $this->addError('name', 'This name is already taken. Please choose a different name.');
            return true;
        }
        $this->resetErrorBag('name');
        return false;
    }

    // Real-time Email/Phone Duplicate Check
    public function checkEmailOrPhoneExists($value)
    {
        $email = $value;
        $phone = null;

        if (preg_match('/^[0-9]{10,15}$/', $value)) {
            $phone = $value;
            $email = null;
        }

        if ($email && User::where('email', $email)->exists()) {
            $this->addError('email', 'This email is already registered. Please use a different email or login.');
            return true;
        }

        if ($phone && User::where('phone', $phone)->exists()) {
            $this->addError('email', 'This phone number is already registered. Please use a different phone number or login.');
            return true;
        }

        $this->resetErrorBag('email');
        return false;
    }

    public function register()
    {
        $this->validate();

        if (User::where('name', $this->name)->exists()) {
            $this->addError('name', 'This name is already taken. Please choose a different name.');
            return;
        }

        $email = $this->email;
        $phone = null;

        if (preg_match('/^[0-9]{10,15}$/', $this->email)) {
            $phone = $this->email;
            $email = null;
        }

        if ($email && User::where('email', $email)->exists()) {
            $this->addError('email', 'This email is already registered. Please use a different email or login.');
            return;
        }

        if ($phone && User::where('phone', $phone)->exists()) {
            $this->addError('email', 'This phone number is already registered. Please use a different phone number or login.');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        session()->regenerate();

        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}