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

    // 💡 Livewire Dynamic Listeners
    public function getListeners()
    {
        $authId = auth()->id();
        $userId = $this->user ? $this->user->id : null;

        return [
            "echo:chat.{$userId},message-sent" => 'loadMessagesAndScroll',
            'refreshChat' => 'loadMessagesAndScroll',
            
            // 🔔 Chat Page ရောက်နေချိန် အခြား Like/Comment/Message Push Notification များ မိစေရန် Private Channel Listener ထည့်သွင်းခြင်း
            "echo-private:App.Models.User.{$authId},NotificationSent" => 'handleIncomingNotification',
            "echo-private:App.Models.User.{$authId},.NotificationSent" => 'handleIncomingNotification',
            "echo-private:App.Models.User.{$authId},Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleIncomingNotification',
        ];
    }

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

    // 🔔 Chat Page ရောက်နေစဉ် Notification ဝင်လာပါက Noti Pop-up တက်စေမည့် Handler
    public function handleIncomingNotification($event = null)
    {
        $this->dispatch('play-notification-sound');
        
        $this->dispatch('noti-received', [
            'title' => $event['title'] ?? $event['data']['title'] ?? 'အကြောင်းကြားစာ အသစ်',
            'message' => $event['message'] ?? $event['body'] ?? $event['data']['message'] ?? 'တစ်စုံတစ်ခု ပြုလုပ်ခဲ့ပါသည်။',
            'url' => $event['url'] ?? $event['data']['url'] ?? '/notifications',
            'icon' => $event['icon'] ?? $event['data']['icon'] ?? '/favicon.ico'
        ]);
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

        // 1️⃣ မူလ Chat Event Broadcast လုပ်ခြင်း
        broadcast(new MessageSent($message));

        // 2️⃣ 🔥 စာလက်ခံရရှိသူ (Receiver) ၏ ဖုန်းထဲသို့ Push Pop-up တက်စေရန် Notification Event လွှတ်ပေးခြင်း
        if (class_exists('\App\Events\NotificationSent')) {
            broadcast(new \App\Events\NotificationSent(
                $this->user->id,
                auth()->user()->name ?? 'New Message',
                $this->message,
                route('chat', ['userId' => auth()->id()])
            ));
        }

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
