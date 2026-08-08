<?php

namespace App\Livewire\Friend;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BlockUser extends Component
{
    public User $targetUser;

    // Block ပြုလုပ်ရန် Action
    public function block(): void
    {
        if (!Auth::check() || Auth::id() === $this->targetUser->id) return;

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        
        $authUser->blockUser(
            targetUserId: $this->targetUser->id,
            type: 'full'
        );

        // Alert Message နှင့် Feed Refresh Event Dispatch ပို့ခြင်း
        $this->dispatch('toast', message: 'User has been blocked.');
        $this->dispatch('user-blocked');
        $this->dispatch('refresh-feed');
    }

    // Unblock ပြုလုပ်ရန် Action
    public function unblock(): void
    {
        if (!Auth::check()) return;

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        
        $authUser->unblockUser($this->targetUser->id);

        $this->dispatch('toast', message: 'User unblocked successfully.');
        $this->dispatch('user-blocked');
        $this->dispatch('refresh-feed');
    }

    public function render()
    {
        return view('livewire.friend.block-user');
    }
}
