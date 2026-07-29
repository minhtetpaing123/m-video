<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Livewire\Attributes\On;

class ChatList extends Component
{
    public $search = '';
    public $userId;
    public $onlineUserIds = [];

    protected $listeners = [
        'echo:chat.{userId},message-sent' => '$refresh',
    ];

    public function mount()
    {
        $this->userId = auth()->id();
    }

    #[On('updateOnlineUsers')]
    public function updateOnlineUsers($payload = [])
    {
        if (isset($payload['users']) && is_array($payload['users'])) {
            $this->onlineUserIds = array_column($payload['users'], 'id');
        }
    }

    public function getChatUsersProperty()
    {
        $userId = auth()->id();

        $chatUserIds = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->pluck('sender_id')
            ->merge(Message::where('receiver_id', $userId)->pluck('receiver_id'))
            ->unique()
            ->reject(fn($id) => $id == $userId)
            ->values()
            ->toArray();

        $query = User::whereIn('id', $chatUserIds);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();
        }

        return $users;
    }

    public function render()
    {
        return view('livewire.chat.chat-list', [
            'chatUsers' => $this->chatUsers,
        ]);
    }
}