<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;

class Chat extends Component
{
    public $user;
    public $message = '';
    public $messages = [];

    protected $listeners = [
        'echo:chat.{user.id},message-sent' => 'loadMessagesAndScroll',
        'refreshChat' => 'loadMessagesAndScroll',
    ];

    public function mount($userId)
    {
        $this->user = User::findOrFail($userId);
        $this->loadMessages();
        
        // Messages များကို Read လုပ်မည်
        $updated = Message::where('sender_id', $this->user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // 🔥 စာဖတ်လိုက်ပါက တစ်ဖက်လူဆီ Seen ဖြစ်အောင် Event လွှတ်မည်
        if ($updated > 0) {
            broadcast(new \App\Events\MessagesRead($this->user->id, auth()->id()));
        }
    }

    public function loadMessages()
    {
        $this->messages = Message::where(function ($query) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $this->user->id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->user->id)
                  ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();
    }

    public function loadMessagesAndScroll()
    {
        $this->loadMessages();
        
        // 🔥 စာဝင်လာချိန် Chat ဖွင့်ထားလျှင် Read လုပ်ပြီး Event လွှတ်မည်
        $updated = Message::where('sender_id', $this->user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        if ($updated > 0) {
            broadcast(new \App\Events\MessagesRead($this->user->id, auth()->id()));
        }

        $this->dispatch('message-sent-scroll');
    }

    public function sendMessage()
    {
        if (empty(trim($this->message))) {
            return;
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->user->id,
            'message' => $this->message,
        ]);

        broadcast(new MessageSent($message));

        $this->message = '';
        $this->loadMessages();

        // 🔊 စာပို့ပြီးပါက အသံမြည်ရန် Event ပို့မည်
        $this->dispatch('play-send-sound');

        // 📜 စာပို့ပြီးပါက အောက်ဆုံးသို့ တန်းဆွဲချရန် Frontend သို့ Event ပို့မည်
        $this->dispatch('message-sent-scroll');
    }

    public function render()
    {
        return view('livewire.chat.chat')
            ->layout('livewire.layout.app');
    }
}
