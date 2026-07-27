<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class SettingsModal extends Component
{
    public bool $isOpen = false;
    public string $layoutMode = 'grid'; // Default: YouTube Grid Style

    protected $listeners = ['open-settings-modal' => 'openModal'];

    public function mount()
    {
        // Session တွင် မရှိသေးပါက 'grid' (YouTube) ကို Default ထားမည်
        $this->layoutMode = session('user_feed_layout', 'grid');
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function changeLayout($mode)
    {
        $this->layoutMode = $mode;
        session(['user_feed_layout' => $mode]);

        // Layout အမည်အလိုက် Toast စာသား သတ်မှတ်ခြင်း
        $names = [
            'normal'  => 'Mobile Compact',
            'wide'    => 'Expanded Wide',
            'grid'    => 'YouTube Grid',
            'netflix' => 'Netflix Row/Card'
        ];

        $selectedName = $names[$mode] ?? 'Default';

        // Toast Notification Event Dispatch လုပ်ခြင်း
        $this->dispatch('notify', [
            'message' => "Layout switched to {$selectedName} style!",
            'type'    => 'success'
        ]);

        $this->dispatch('layout-changed', mode: $mode);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.dashboard.settings-modal');
    }
}
