<?php

namespace App\Livewire\Friend;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Friend extends Component
{
    public string $search = '';
    public string $tab = 'posts'; // Default tab

    // Follow ပြုလုပ်ရန်
    public function sendRequest(int $userId): void
    {
        if (!Auth::check() || Auth::id() === $userId) return;

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // တိုက်ရိုက် Follow ပြုလုပ်ခြင်း
        $authUser->followings()->syncWithoutDetaching([
            $userId => ['status' => 'accepted']
        ]);

        $this->dispatch('toast', message: 'Followed successfully!');
    }

    // Unfollow သို့မဟုတ် Remove ပြုလုပ်ရန်
    public function removeFriend(int $userId): void
    {
        if (!Auth::check()) return;

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        $authUser->followings()->detach($userId);
        $authUser->followers()->detach($userId);

        $this->dispatch('toast', message: 'Removed successfully!');
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. မိမိအား Follow လုပ်ထားသော User များ (Followers)
        $friends = $user ? $user->followers()
            ->when(!empty(trim($this->search)), fn($q) => $q->where('name', 'like', '%' . trim($this->search) . '%'))
            ->get() : collect();

        // 2. User Suggestions (မိမိ Follow မလုပ်ရသေးသော User များ)
        $followingIds = $user ? $user->followings()->pluck('users.id')->toArray() : [];

        $suggestions = $user ? User::where('id', '!=', $user->id)
            ->whereNotIn('id', $followingIds)
            ->when(!empty(trim($this->search)), fn($q) => $q->where('name', 'like', '%' . trim($this->search) . '%'))
            ->limit(10)
            ->get() : collect();

        return view('livewire.friend.friend', [
            'friends' => $friends,
            'suggestions' => $suggestions,
        ])->layout('layouts.app');
    }
}
