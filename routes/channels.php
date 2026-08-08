<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// ✅ User Status Channel (Private)
Broadcast::channel('user-status', function ($user) {
    if ($user) {
        return ['id' => $user->id];
    }
    return false;
});

// ✅ Voice Call Signaling Channel (Private)
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// ✅ Chat Channel (Private)
Broadcast::channel('chat.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// ✅ Presence Channel 'online'
Broadcast::channel('online', function ($user) {
    if ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar_url ?? null,
        ];
    }
    return false;
});

// ✅ Presence Channel 'chat' (For Echo.join('chat'))
Broadcast::channel('chat', function ($user) {
    if ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar_url ?? null,
        ];
    }
    return false;
});

// 🟢 Notification Channel (Private - Facebook Like Real-time Sound Noti)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
