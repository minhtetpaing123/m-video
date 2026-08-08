<?php

namespace App\Livewire\Friend;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FriendRequest extends Component
{
    /**
     * Follow Request ကို လက်ခံခြင်း (Accept)
     */
    public function acceptRequest($followerId)
    {
        $user = Auth::user();

        if ($user) {
            $user->followers()->updateExistingPivot($followerId, [
                'status' => 'accepted'
            ]);
        }
    }

    /**
     * Follow Request ကို ငြင်းပယ်/ဖျက်ထုတ်ခြင်း (Decline / Delete)
     */
    public function removeFriend($followerId)
    {
        $user = Auth::user();

        if ($user) {
            $user->followers()->detach($followerId);
        }
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // မိမိကို Follow Request ပို့ထားသော User များကိုဆွဲထုတ်ခြင်း (status = 'pending')
        $requests = $user 
            ? $user->followers()->wherePivot('status', 'pending')->get() 
            : collect();

        return view('livewire.friend.friend-request', [
            'requests' => $requests,
        ]);
    }
}
