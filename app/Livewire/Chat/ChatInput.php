<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Message;

class ChatInput extends Component
{
    public $user;
    public $message = '';
    public $hasText = false;

    public $replyMessage = null;
    public $editingMessage = null;

    // 🔥 Livewire v4 Listeners ကြေညာချက်
    protected function getListeners()
    {
        return [
            'set-reply' => 'handleSetReply',
            'set-edit' => 'handleSetEdit',
        ];
    }

    // 🔴 မိမိက ထို User ကို Block ထားခြင်း ရှိ/မရှိ စစ်ဆေးခြင်း
    public function getIBlockedUserProperty()
    {
        if (!Auth::check() || !$this->user) {
            return false;
        }

        return DB::table('blocks')
            ->where('user_id', Auth::id())
            ->where('blocked_user_id', $this->user->id)
            ->whereNull('unblocked_at')
            ->exists();
    }

    // 🔴 ထို User က မိမိအား Block ထားခြင်း ရှိ/မရှိ စစ်ဆေးခြင်း
    public function getIAmBlockedByProperty()
    {
        if (!Auth::check() || !$this->user) {
            return false;
        }

        return DB::table('blocks')
            ->where('user_id', $this->user->id)
            ->where('blocked_user_id', Auth::id())
            ->whereNull('unblocked_at')
            ->exists();
    }

    // Unblock ပြုလုပ်ရန် Action
    public function unblockUser()
    {
        if (!Auth::check()) return;

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $authUser->unblockUser($this->user->id);

        $this->dispatch('toast', message: 'User unblocked successfully.');
        $this->dispatch('user-blocked');
    }

    public function handleSetReply($messageId)
    {
        $this->cancelInput();
        $this->replyMessage = Message::find($messageId);
    }

    public function handleSetEdit($messageId)
    {
        $this->cancelInput();
        $msg = Message::find($messageId);
        if ($msg && $msg->sender_id === Auth::id()) {
            $this->editingMessage = $msg;
            $this->message = $msg->message;
            $this->hasText = true;
        }
    }

    public function cancelInput()
    {
        $this->replyMessage = null;
        $this->editingMessage = null;
        $this->message = '';
        $this->hasText = false;
    }
    
    public function mount($user)
    {
        $this->user = $user;
    }
    
    public function sendMessage()
    {
        // 🔴 အချင်းချင်း Block ဖြစ်နေပါက Message ပို့ခွင့်မပြုပါ
        if ($this->iBlockedUser || $this->iAmBlockedBy) {
            $this->dispatch('toast', message: 'You cannot send messages in this conversation.');
            return;
        }

        $this->validate([
            'message' => 'required|string|max:1000'
        ]);

        if ($this->editingMessage) {
            $this->editingMessage->update([
                'message' => $this->message,
                'is_edited' => true,
            ]);

            $this->cancelInput();
            $this->dispatch('message-sent');
            return;
        }
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->user->id,
            'message' => $this->message,
            'reply_to_id' => $this->replyMessage ? $this->replyMessage->id : null,
        ]);
        
        $this->cancelInput();
        
        $this->dispatch('message-sent');
        $this->dispatch('play-send-sound');
        
        broadcast(new \App\Events\MessageSent($message))->toOthers();
    }
    
    public function render()
    {
        return view('livewire.chat.chat-input');
    }
}
