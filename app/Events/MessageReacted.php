<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReacted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $receiverId;

    public function __construct($messageId, $receiverId)
    {
        $this->messageId = $messageId;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->receiverId),
        ];
    }
}
