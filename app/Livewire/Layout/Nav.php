<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Nav extends Component
{
    public $active = 'home';
    public $unreadCount = 0;

    public function mount($active = 'home')
    {
        $this->active = $active;
        
        if (auth()->check()) {
            try {
                $this->unreadCount = auth()->user()->unreadNotifications()->count() ?? 0;
            } catch (\Exception $e) {
                $this->unreadCount = 0;
            }
        }
    }

    public function getListeners()
    {
        return [
            'notification-read' => 'refreshCount',
            'notification-read-all' => 'refreshCount',
            'notification-created' => 'refreshCount',
        ];
    }

    public function refreshCount()
    {
        if (auth()->check()) {
            try {
                $this->unreadCount = auth()->user()->unreadNotifications()->count() ?? 0;
            } catch (\Exception $e) {
                $this->unreadCount = 0;
            }
        }
    }

    public function render()
    {
        return view('livewire.layout.nav');
    }
}