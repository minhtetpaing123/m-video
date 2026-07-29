<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagesRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiverId;
    public $senderId;

    public function __construct($receiverId, $senderId)
    {
        $this->receiverId = $receiverId;
        $this->senderId = $senderId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->senderId);
    }

    public function broadcastAs()
    {
        return 'messages-read';
    }
}
