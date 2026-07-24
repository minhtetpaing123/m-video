<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class ViewToggle extends Component
{
    public $viewMode = 'grid';

    public function mount()
    {
        // Session ကနေ viewMode ကိုဖတ်မယ်
        $this->viewMode = session()->get('view_mode', 'grid');
    }

    public function toggle()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
        
        // Session မှာ သိမ်းမယ်
        session()->put('view_mode', $this->viewMode);
        
        // တခြား Component တွေကို အသိပေးမယ်
        $this->dispatch('view-mode-changed', mode: $this->viewMode);
        $this->dispatch('settings-updated', type: 'view_mode', value: $this->viewMode);
    }

    // Settings ကနေ update လာရင် နားထောင်မယ်
    protected $listeners = [
        'settings-updated' => 'updateFromSettings',
    ];

    public function updateFromSettings($type, $value)
    {
        if ($type === 'view_mode') {
            $this->viewMode = $value;
        }
    }

    public function render()
    {
        return view('livewire.layout.view-toggle');
    }
}