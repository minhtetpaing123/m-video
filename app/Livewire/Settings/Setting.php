<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('livewire.layout.setting-layout')]
#[Title('Settings')]
class Setting extends Component
{
    public $viewMode = 'grid';  // grid, list, netflix, youtube
    public $theme = 'dark';
    public $language = 'en';
    public $autoplay = true;
    public $quality = 'auto';
    public $notifications = true;
    public $fontSize = 'medium'; // small, medium, large

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->viewMode = session()->get('view_mode', 'grid');
        $this->theme = session()->get('theme', 'dark');
        $this->language = session()->get('language', 'en');
        $this->autoplay = session()->get('autoplay', true);
        $this->quality = session()->get('quality', 'auto');
        $this->notifications = session()->get('notifications', true);
        $this->fontSize = session()->get('font_size', 'medium');
    }

    public function updateViewMode($mode)
    {
        $this->viewMode = $mode;
        session()->put('view_mode', $mode);
        
        $this->dispatch('view-mode-changed', mode: $mode);
        session()->flash('message', 'View mode updated to ' . ucfirst($mode));
    }

    public function updateTheme($theme)
    {
        $this->theme = $theme;
        session()->put('theme', $theme);
        
        $this->dispatch('theme-updated', theme: $theme);
        session()->flash('message', 'Theme updated to ' . ucfirst($theme));
    }

    public function updateLanguage($language)
    {
        $this->language = $language;
        session()->put('language', $language);
        session()->put('locale', $language);
        
        app()->setLocale($language);
        
        $this->dispatch('language-updated', language: $language);
        session()->flash('message', 'Language updated to ' . ($language === 'en' ? 'English' : 'မြန်မာ'));
    }

    public function toggleAutoplay()
    {
        $this->autoplay = !$this->autoplay;
        session()->put('autoplay', $this->autoplay);
        
        $this->dispatch('settings-updated', type: 'autoplay', value: $this->autoplay);
        session()->flash('message', 'Autoplay ' . ($this->autoplay ? 'enabled' : 'disabled'));
    }

    public function updateQuality($quality)
    {
        $this->quality = $quality;
        session()->put('quality', $quality);
        
        $this->dispatch('settings-updated', type: 'quality', value: $quality);
        session()->flash('message', 'Quality updated to ' . $quality);
    }

    public function toggleNotifications()
    {
        $this->notifications = !$this->notifications;
        session()->put('notifications', $this->notifications);
        
        $this->dispatch('settings-updated', type: 'notifications', value: $this->notifications);
        session()->flash('message', 'Notifications ' . ($this->notifications ? 'enabled' : 'disabled'));
    }

    public function updateFontSize($size)
    {
        $this->fontSize = $size;
        session()->put('font_size', $size);
        
        $this->dispatch('font-size-changed', size: $size);
        $this->dispatch('settings-updated', type: 'font_size', value: $size);
        
        session()->flash('message', 'Font size updated to ' . ucfirst($size));
    }

    public function resetSettings()
    {
        session()->forget(['view_mode', 'theme', 'language', 'autoplay', 'quality', 'notifications', 'font_size', 'locale']);
        
        $this->viewMode = 'grid';
        $this->theme = 'dark';
        $this->language = 'en';
        $this->autoplay = true;
        $this->quality = 'auto';
        $this->notifications = true;
        $this->fontSize = 'medium';
        
        app()->setLocale('en');
        
        $this->dispatch('settings-reset');
        $this->dispatch('view-mode-changed', mode: 'grid');
        $this->dispatch('theme-updated', theme: 'dark');
        $this->dispatch('language-updated', language: 'en');
        $this->dispatch('font-size-changed', size: 'medium');
        
        session()->flash('message', 'All settings reset to default');
    }

    public function render()
    {
        return view('livewire.settings.setting');
    }
}