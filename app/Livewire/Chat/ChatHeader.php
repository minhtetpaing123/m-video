<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;

class ChatHeader extends Component
{
    public $user;
    public $isUserOnline = false;

    public function mount($user)
    {
        $this->user = User::find($user->id ?? $user);
        $this->checkOnlineStatus();
    }

    // 🔥 Periodic/Background Sync Check
    public function checkOnlineStatus()
    {
        $targetUser = User::find($this->user->id);
        if ($targetUser) {
            $this->user = $targetUser;
            
            // 1 မိနစ်အတွင်း last_seen ရှိမှသာ Active now ဟု သတ်မှတ်မည်
            $wasOnline = $this->isUserOnline;
            $this->isUserOnline = $targetUser->last_seen && $targetUser->last_seen->gt(now()->subMinute());

            // State ပြောင်းသွားပါက JavaScript/Alpine View သို့ Dispatch ပို့မည်
            if ($wasOnline !== $this->isUserOnline) {
                $this->dispatch('sync-online-status', online: $this->isUserOnline);
            }
        }
    }

    #[On('updateOnlineUsers')]
    public function updateOnlineUsers($payload = [])
    {
        if (isset($payload['users']) && is_array($payload['users'])) {
            $onlineIds = array_column($payload['users'], 'id');
            $this->isUserOnline = in_array($this->user->id, $onlineIds);
            $this->dispatch('sync-online-status', online: $this->isUserOnline);
        }
    }

    public function render()
    {
        return view('livewire.chat.chat-header');
    }
}
