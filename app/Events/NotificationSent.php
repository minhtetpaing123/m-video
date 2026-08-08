<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $playSound;

    public function __construct(Notification $notification, $playSound = true)
    {
        $this->notification = $notification;
        $this->playSound = $playSound;

        // 🟢 Tab ပိတ်ထားချိန် / Offline Push ပို့ပေးရန်
        $this->sendFcmOfflinePush();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->notification->user_id);
    }

    public function broadcastAs()
    {
        return 'NotificationSent';
    }

    /**
     * Echo / JS သို့ Dynamic Noti Payload အပြည့်အစုံ ပို့ပေးခြင်း
     */
    public function broadcastWith()
    {
        $info = $this->resolveNotificationData();

        return [
            'id' => $this->notification->id,
            'title' => $info['title'],
            'message' => $info['message'],
            'body' => $info['message'],
            'icon' => $info['icon'],
            'url' => $info['url'],
            'user_id' => $this->notification->user_id,
            'playSound' => $this->playSound,
            'type' => $this->notification->type ?? $this->notification->action_type ?? 'general',
            'created_at' => $this->notification->created_at ? $this->notification->created_at->toIso8601String() : null,
        ];
    }

    /**
     * DB Columns (title, content_snippet, action_url, image_url) မှ Dynamic Data ရယူခြင်း
     */
    private function resolveNotificationData()
    {
        // 1. Title Resolution
        $title = $this->notification->title;
        if (empty($title)) {
            $fromUser = $this->notification->fromUser ?? User::find($this->notification->from_user_id);
            $senderName = $fromUser ? $fromUser->name : 'အကြောင်းကြားစာ';
            $action = $this->notification->action_type ?? $this->notification->type ?? 'အကြောင်းကြားစာ';
            $title = "{$senderName} ထံမှ {$action}";
        }

        // 2. Message/Content Resolution (content_snippet ထဲမှ စာသားကို ယူမည်)
        $message = $this->notification->content_snippet 
                ?? $this->notification->message 
                ?? 'နိုတီဖီကေးရှင်း အသစ် ရောက်ရှိလာပါသည်။';

        // 3. Icon / Avatar Resolution
        $icon = $this->notification->image_url;
        if (empty($icon)) {
            $fromUser = $this->notification->fromUser ?? User::find($this->notification->from_user_id);
            $icon = $fromUser->avatar ?? '/favicon.ico';
        }

        // 4. Action URL Resolution
        $url = $this->notification->action_url ?? '/noti';

        return [
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'url' => $url,
        ];
    }

    /**
     * App/Tab ပိတ်ထားချိန် FCM HTTP v1 / Pure Push သို့ စာသားအပြည့်အစုံဖြင့် ပို့ပေးမည်
     */
    private function sendFcmOfflinePush()
    {
        try {
            $targetUser = User::find($this->notification->user_id);

            // User ရဲ့ notify_push သို့မဟုတ် notify_in_app setting ဖွင့်ထားပြီး fcm_token ရှိမှ ပို့မည်
            if ($targetUser && !empty($targetUser->fcm_token)) {
                
                // User က Push Notification ပိတ်ထားပါက မပို့ပါ
                if (isset($targetUser->notify_push) && !$targetUser->notify_push) {
                    return;
                }

                $info = $this->resolveNotificationData();

                // FcmService Call
                if (class_exists(\App\Services\FcmService::class)) {
                    \App\Services\FcmService::sendPush(
                        $targetUser->fcm_token,
                        $info['title'],
                        $info['message'],
                        $info['url'],
                        $info['icon']
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM Push Dispatch Error: ' . $e->getMessage());
        }
    }
}
