<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoiceCallEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $callId;
    public array $fromUser;
    public int $toUserId;
    public string $type; // 'offer', 'answer', 'candidate', 'accept', 'reject', 'end'
    public mixed $sdpData;

    /**
     * Create a new event instance.
     */
    public function __construct(int $callId, $fromUser, int $toUserId, string $type, mixed $sdpData = null)
    {
        $this->callId = $callId;
        
        // Front-end သို့ ပို့ပေးရန် User Payload ပြင်ဆင်ခြင်း
        $this->fromUser = [
            'id' => is_object($fromUser) ? $fromUser->id : ($fromUser['id'] ?? null),
            'name' => is_object($fromUser) ? $fromUser->name : ($fromUser['name'] ?? 'Unknown'),
            'avatar_url' => is_object($fromUser) ? ($fromUser->avatar_url ?? null) : ($fromUser['avatar_url'] ?? null),
        ];
        
        $this->toUserId = $toUserId;
        $this->type = $type;
        $this->sdpData = $sdpData;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->toUserId)
        ];
    }

    /**
     * Broadcast Event Alias Name
     */
    public function broadcastAs(): string
    {
        return 'voice.call';
    }
}
