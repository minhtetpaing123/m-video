<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\MessageReaction;
use App\Events\MessageReacted;
use App\Models\Call; 

class ChatMessages extends Component
{
    public $user;
    public $messages = [];

    public function mount($user)
    {
        $this->user = $user;
        $this->loadMessages();
        $this->dispatch('scroll-to-bottom');
    }

    #[On('refreshMessages')]
    #[On('message-sent')]
    public function refreshMessages()
    {
        $this->loadMessagesData();
        $this->dispatch('scroll-to-bottom');
        $this->dispatch('messageReceived', senderId: $this->user->id);
    }
    
    #[On('loadMessagesSilently')]
    public function loadMessagesSilently()
    {
        $this->loadMessagesData();
    }

    #[On('loadMessages')]
    public function loadMessages()
    {
        $this->loadMessagesData();
    }

    private function loadMessagesData()
    {
        $messages = Message::with(['replyTo', 'reactions'])
            ->where(function($query) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $this->user->id)
                      ->where('deleted_for_sender', false);
            })->orWhere(function($query) {
                $query->where('sender_id', $this->user->id)
                      ->where('receiver_id', Auth::id())
                      ->where('deleted_for_receiver', false);
            })->get();

        $callLogs = Call::where(function($query) {
                $query->where('caller_id', Auth::id())
                      ->where('receiver_id', $this->user->id);
            })->orWhere(function($query) {
                $query->where('caller_id', $this->user->id)
                      ->where('receiver_id', Auth::id());
            })->get();

        $this->messages = collect($messages)->concat($callLogs)->sortBy('created_at')->values();

        $this->dispatch('messageReceived', senderId: $this->user->id);
    }

    #[On('setReply')]
    public function setReply($messageId)
    {
        $this->dispatch('set-reply', messageId: $messageId);
    }

    #[On('setEdit')]
    public function setEdit($messageId)
    {
        $this->dispatch('set-edit', messageId: $messageId);
    }

    #[On('reactToMessage')]
    public function reactToMessage($messageId, $emoji)
    {
        $existing = MessageReaction::where('message_id', $messageId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if ($existing->emoji === $emoji) {
                $existing->delete();
            } else {
                $existing->update(['emoji' => $emoji]);
            }
        } else {
            MessageReaction::create([
                'message_id' => $messageId,
                'user_id' => Auth::id(),
                'emoji' => $emoji,
            ]);
        }

        broadcast(new MessageReacted($messageId, $this->user->id))->toOthers();
        $this->loadMessagesData();
    }

    #[On('deleteForEveryone')]
    public function deleteForEveryone($messageId)
    {
        $message = Message::find($messageId);
        if ($message && $message->sender_id === Auth::id()) {
            $message->deleted_for_everyone = true;
            $message->message = 'This message was unsent';
            $message->save();

            $this->loadMessagesData();
        }
    }

    #[On('deleteForMe')]
    public function deleteForMe($messageId)
    {
        $message = Message::find($messageId);
        if ($message) {
            if ($message->sender_id === Auth::id()) {
                $message->deleted_for_sender = true;
            } else {
                $message->deleted_for_receiver = true;
            }
            $message->save();

            $this->loadMessagesData();
        }
    }
    
    public function render()
    {
        return view('livewire.chat.chat-messages');
    }
}
