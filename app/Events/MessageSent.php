<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'receiver']);
    }

    public function broadcastOn()
    {
        // Private Channel - Receiver အတွက်[span_0](start_span)[span_0](end_span)
        return new PrivateChannel('chat.' . $this->message->receiver_id);
    }

    public function broadcastAs()
    {
        return 'message-sent';
    }

    public function broadcastWith()
    {
        return [
            // မူလ Logic များ အပြည့်အစုံ[span_1](start_span)[span_1](end_span)
            'id'            => $this->message->id,
            'sender_id'     => $this->message->sender_id,
            'sender_name'   => $this->message->sender->name,
            'sender_avatar' => $this->message->sender->avatar_url,
            'message'       => $this->message->message,
            'created_at'    => $this->message->created_at->diffForHumans(),
            'is_read'       => $this->message->is_read,

            // System Push Notification အတွက် ထည့်ထားသော Data များ
            'title'         => $this->message->sender->name,
            'icon'          => $this->message->sender->avatar_url ?? '/favicon.ico',
            'url'           => '/chat/' . $this->message->sender_id,
        ];
    }
}
