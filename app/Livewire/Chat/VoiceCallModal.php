<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Call;
use App\Events\VoiceCallEvent;
use Illuminate\Support\Str;

class VoiceCallModal extends Component
{
    public ?User $user = null;
    public ?Call $currentCall = null;
    public bool $isCalling = false;
    public bool $isIncoming = false;
    public bool $isMuted = false;
    public bool $isSpeakerOn = false;
    public string $callType = 'voice';

    #[On('start-voice-call')]
    public function startCall($userId = null, $callType = 'voice')
    {
        if (is_array($userId)) {
            $callType = $userId['callType'] ?? $userId['type'] ?? 'voice';
            $userId = $userId['userId'] ?? $userId['id'] ?? null;
        }

        if (!$userId) return;

        $this->callType = $callType;
        $this->user = User::find($userId);
        if (!$this->user) return;

        $this->currentCall = Call::create([
            'caller_id' => auth()->id(),
            'receiver_id' => $this->user->id,
            'channel_name' => 'call_' . Str::uuid(),
            'type' => $this->callType,
            'status' => 'calling',
            'is_encrypted' => true,
        ]);

        $this->isCalling = true;
        $this->isIncoming = false;

        $this->dispatch('initiate-webrtc-caller', 
            callId: $this->currentCall->id,
            receiverId: $this->user->id,
            channelName: $this->currentCall->channel_name,
            type: $this->callType
        );
    }

    #[On('incoming-voice-call')]
    public function handleIncomingCall($callId = null, $fromUser = null, $callType = 'voice')
    {
        $fromUserId = is_array($fromUser) ? ($fromUser['id'] ?? null) : $fromUser;

        if (!$fromUserId || !$callId) return;

        $this->callType = $callType;
        $this->user = User::find($fromUserId);
        $this->currentCall = Call::find($callId);
        
        if ($this->currentCall) {
            // DB တွင် call type ပါဝင်ပါက standard တန်ဖိုး ရယူမည်
            if (isset($this->currentCall->type)) {
                $this->callType = $this->currentCall->type;
            }
            $this->currentCall->update(['status' => 'ringing']);
        }

        $this->isCalling = true;
        $this->isIncoming = true;
    }

    public function acceptCall()
    {
        $this->isIncoming = false;
        
        if ($this->currentCall) {
            $this->currentCall->update([
                'status' => 'accepted',
                'started_at' => now()
            ]);

            // Ringtone ရပ်ရန်
            $this->dispatch('stop-ringtone');

            // Receiver ဘက်မှ တိကျသော Call Type ပါ ပို့ပေးရန်
            $this->dispatch('webrtc-accept-call', 
                callId: $this->currentCall->id,
                callerId: $this->user->id,
                type: $this->callType
            );

            broadcast(new VoiceCallEvent(
                $this->currentCall->id, 
                auth()->user(), 
                $this->user->id, 
                'accept'
            ));
        }
    }

    public function rejectCall()
    {
        if ($this->currentCall && $this->user) {
            $targetUserId = $this->user->id;
            $callId = $this->currentCall->id;

            // Database တွင် rejected ဟု တိုက်ရိုက်သိမ်းမည်
            $this->currentCall->markAsEnded('rejected');
            
            broadcast(new VoiceCallEvent(
                $callId, 
                auth()->user(), 
                $targetUserId, 
                'reject'
            ));
        }

        // 🔥 Ringtone ချက်ချင်း ရပ်ပစ်မည်
        $this->dispatch('stop-ringtone');
        $this->dispatch('webrtc-terminate');
        $this->dispatch('loadMessages');
        $this->resetCallState();
    }

    public function endCall()
    {
        if ($this->currentCall && $this->user) {
            $targetUserId = $this->user->id;
            $callId = $this->currentCall->id;
            $reason = ($this->currentCall->status === 'accepted') ? 'completed' : 'cancelled';
            
            $this->currentCall->markAsEnded($reason);

            broadcast(new VoiceCallEvent(
                $callId, 
                auth()->user(), 
                $targetUserId, 
                'end'
            ));
        }

        // 🔥 Ringtone ချက်ချင်း ရပ်ပစ်မည်
        $this->dispatch('stop-ringtone');
        $this->dispatch('webrtc-terminate');
        $this->dispatch('loadMessages');
        $this->resetCallState();
    }

    #[On('send-webrtc-signal')]
    public function sendSignal($callId = null, $toUserId = null, $type = null, $sdpData = null)
    {
        if (!$callId || !$toUserId || !$type) {
            return;
        }

        broadcast(new VoiceCallEvent(
            (int) $callId,
            auth()->user(),
            (int) $toUserId,
            (string) $type,
            $sdpData
        ));
    }

    #[On('force-dismiss-call')]
    public function forceDismissCall()
    {
        $this->dispatch('stop-ringtone');
        $this->dispatch('loadMessages');
        $this->resetCallState();
    }

    public function toggleMute()
    {
        $this->isMuted = !$this->isMuted;
        $this->dispatch('toggle-audio-mute', isMuted: $this->isMuted);
    }

    public function toggleSpeaker()
    {
        $this->isSpeakerOn = !$this->isSpeakerOn;
        $this->dispatch('toggle-speaker', isSpeakerOn: $this->isSpeakerOn);
    }

    private function resetCallState()
    {
        $this->isCalling = false;
        $this->isIncoming = false;
        $this->isMuted = false;
        $this->isSpeakerOn = false;
        $this->callType = 'voice';
        $this->currentCall = null;
        $this->user = null;
    }

    public function render()
    {
        return view('livewire.chat.voice-call-modal');
    }
}
