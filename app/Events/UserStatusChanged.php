<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $isOnline;

    public function __construct(User $user, bool $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('online'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'UserStatusChanged';
    }
}
