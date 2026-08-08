<?php

namespace App\Livewire\Friend;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('livewire.layout.app')]
#[Title('Blocked Users')]
class BlockedList extends Component
{
    use WithPagination;

    // Unblock ပြုလုပ်ရန် Action
    public function unblock($userId): void
    {
        if (!Auth::check()) return;

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $authUser->unblockUser($userId);

        $this->dispatch('toast', message: 'User unblocked successfully.');
    }

    public function render()
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // မိမိ Block ထားသော User များကို Paginate ဖြင့် ဆွဲထုတ်ခြင်း
        $blockedUsers = $authUser->blockedUsers()->paginate(15);

        return view('livewire.friend.blocked-list', [
            'blockedUsers' => $blockedUsers
        ]);
    }
}
