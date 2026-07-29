<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Call;

class CallLogItem extends Component
{
    public Call $callLog;

    public function mount(Call $callLog)
    {
        $this->callLog = $callLog;
    }

    public function render()
    {
        return view('livewire.chat.call-log-item');
    }
}
