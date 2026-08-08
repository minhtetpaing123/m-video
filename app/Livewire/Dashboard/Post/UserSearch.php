<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserSearch extends Component
{
    public string $search = '';
    public bool $isSearchOpen = false;

    public function openSearch(): void
    {
        $this->isSearchOpen = true;
        $this->js("document.body.classList.add('search-active');");
    }

    public function closeSearch(): void
    {
        $this->isSearchOpen = false;
        $this->search = '';
        $this->js("document.body.classList.remove('search-active');");
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function selectPost($postId): void
    {
        $this->dispatch('pin-post-to-top', postId: $postId);
        $this->closeSearch();
    }

    public function toggleFollow($userId = null): void
    {
        if (!$userId || !Auth::check() || $userId == Auth::id()) {
            return;
        }

        $currentUserId = Auth::id();

        $existing = DB::table('follows')
            ->where('user_id', $userId)
            ->where('follower_id', $currentUserId)
            ->first();

        if ($existing) {
            DB::table('follows')
                ->where('user_id', $userId)
                ->where('follower_id', $currentUserId)
                ->delete();
        } else {
            DB::table('follows')->insert([
                'user_id'     => $userId,
                'follower_id' => $currentUserId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    public function render()
    {
        $users = collect();
        $searchResults = collect();

        if (strlen(trim($this->search)) > 1) {
            $searchTerm = '%' . trim($this->search) . '%';
            $currentUserId = Auth::id();

            $users = User::query()
                ->select('users.*')
                ->where('name', 'like', $searchTerm)
                ->where('id', '!=', $currentUserId)
                ->when($currentUserId, function ($query) use ($currentUserId) {
                    $query->selectSub(function ($q) use ($currentUserId) {
                        $q->selectRaw('COUNT(*)')
                          ->from('follows')
                          ->whereColumn('follows.user_id', 'users.id')
                          ->where('follows.follower_id', $currentUserId);
                    }, 'is_following');
                })
                ->limit(4)
                ->get();

            $searchResults = Post::query()
                ->where(function ($query) use ($searchTerm) {
                    $query->where('title', 'like', $searchTerm)
                          ->orWhere('content', 'like', $searchTerm);
                })
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('livewire.dashboard.post.user-search', [
            'users' => $users,
            'searchResults' => $searchResults
        ]);
    }
}
