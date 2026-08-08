<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AppearanceSettings extends Component
{
    public string $theme = 'dark'; // Default: 'dark', 'light', 'system'

    public function mount()
    {
        $user = Auth::user();
        
        if ($user && Schema::hasColumn('users', 'theme_preference')) {
            $this->theme = $user->theme_preference ?? 'dark';
        } else {
            $this->theme = session('theme', 'dark');
        }
    }

    public function updateTheme(string $mode)
    {
        $this->theme = $mode;
        $user = Auth::user();

        if ($user && Schema::hasColumn('users', 'theme_preference')) {
            $user->update(['theme_preference' => $mode]);
        }
        
        session(['theme' => $mode]);

        // Alpine.js / Frontend သို့ Theme ပြောင်းလဲရန် Event ပို့ခြင်း
        $this->dispatch('theme-changed', theme: $mode);

        session()->flash('message', 'Appearance preference updated!');
    }

    public function render()
    {
        return view('livewire.settings.appearance-settings');
    }
}
