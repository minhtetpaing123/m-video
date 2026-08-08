<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationBadge extends Component
{
    public $unreadCount = 0;

    protected function getListeners()
    {
        $userId = Auth::id();

        return [
            // Reverb Private Channel Listeners ( Event နာမည် မတူတာမျိုးမဖြစ်အောင် အကုန်ဖမ်းထားပါသည် )
            "echo-private:App.Models.User.{$userId},NotificationSent" => 'handleNotificationSent',
            "echo-private:App.Models.User.{$userId},.NotificationSent" => 'handleNotificationSent',
            "echo-private:App.Models.User.{$userId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleNotificationSent',
            "echo-private:App.Models.User.{$userId},Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleNotificationSent',
        ];
    }

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        if (Auth::check()) {
            $this->unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
    }

    public function handleNotificationSent($event = null)
    {
        // 1. Unread Counter Badge တိုးမည်
        $this->unreadCount++;

        // 2. Reverb မှ ရောက်လာသော JSON Payload ကို အကုန်စစ်ပြီး Data ဆွဲထုတ်ခြင်း
        // Reverb Data Structure က $event အဆင့်၊ $event['data'] အဆင့် သို့မဟုတ် $event['notification'] အဆင့် အမျိုးမျိုး ဖြစ်နိုင်သည်
        $payload = [];

        if (is_array($event)) {
            if (isset($event['data']) && is_array($event['data'])) {
                $payload = $event['data'];
            } else {
                $payload = $event;
            }
        }

        $title = $payload['title'] ?? 'အကြောင်းကြားစာ အသစ်';
        $message = $payload['message'] ?? $payload['body'] ?? 'နိုတီဖီကေးရှင်း အသစ် ရောက်ရှိလာပါသည်။';
        $icon = $payload['icon'] ?? '/favicon.ico';
        $url = $payload['url'] ?? '/noti';

        // 3. Browser JS (Blade) သို့ Payload အပြည့်အစုံဖြင့် Event Dispatch လုပ်မည်
        $this->dispatch('noti-received', [
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'url' => $url,
        ]);
    }

    #[On('notificationRead')]
    public function decrementCount()
    {
        if ($this->unreadCount > 0) {
            $this->unreadCount--;
        }
    }

    #[On('notificationsMarkedAllRead')]
    public function resetCount()
    {
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.notification.notification-badge');
    }
}
