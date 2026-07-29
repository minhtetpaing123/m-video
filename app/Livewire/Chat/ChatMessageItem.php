<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatMessageItem extends Component
{
    public $msg;
    public $user;
    public $isLast;

    public function mount($msg, $user, $isLast = false)
    {
        $this->msg = $msg;
        $this->user = $user;
        $this->isLast = $isLast;
    }

    public function reactToMessage($emoji)
    {
        // Parent ChatMessages component ဆီ Event တိုက်ရိုက်ရောက်စေရန် .to() ထည့်သွင်းထားပါသည်
        $this->dispatch('reactToMessage', messageId: $this->msg->id, emoji: $emoji)->to(ChatMessages::class);
    }

    public function setReply()
    {
        $this->dispatch('setReply', messageId: $this->msg->id)->to(ChatMessages::class);
    }

    public function setEdit()
    {
        $this->dispatch('setEdit', messageId: $this->msg->id)->to(ChatMessages::class);
    }

    public function deleteForEveryone()
    {
        $this->dispatch('deleteForEveryone', messageId: $this->msg->id)->to(ChatMessages::class);
    }

    public function deleteForMe()
    {
        $this->dispatch('deleteForMe', messageId: $this->msg->id)->to(ChatMessages::class);
    }

    public function render()
    {
        return view('livewire.chat.chat-message-item');
    }
}
