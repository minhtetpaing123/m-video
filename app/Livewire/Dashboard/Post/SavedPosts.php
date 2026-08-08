<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SavedPosts extends Component
{
    public string $search = '';
    public string $sort = 'latest'; // 'latest' or 'oldest'
    public array $selectedPosts = [];
    public bool $selectAll = false;
    public bool $isSelectMode = false; // Checkbox ပေါ်/မပေါ် ထိန်းချုပ်ရန် Variable

    protected $listeners = ['bookmark-updated' => '$refresh', 'user-blocked' => '$refresh'];

    public function toggleSelectMode(): void
    {
        $this->isSelectMode = !$this->isSelectMode;
        if (!$this->isSelectMode) {
            $this->selectedPosts = [];
            $this->selectAll = false;
        }
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->isSelectMode = true;
            $this->selectedPosts = $this->getSavedPostsQuery()->pluck('posts.id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedPosts = [];
        }
    }

    public function deleteSelected(): void
    {
        if (Auth::check() && !empty($this->selectedPosts)) {
            Auth::user()->savedPosts()->detach($this->selectedPosts);
            $this->selectedPosts = [];
            $this->selectAll = false;
            $this->isSelectMode = false;
            $this->dispatch('bookmark-updated');
        }
    }

    public function clearAll(): void
    {
        if (Auth::check()) {
            Auth::user()->savedPosts()->detach();
            $this->selectedPosts = [];
            $this->selectAll = false;
            $this->isSelectMode = false;
            $this->dispatch('bookmark-updated');
        }
    }

    private function getSavedPostsQuery()
    {
        if (!Auth::check()) {
            return collect();
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // မိမိ Block ထားသော User ID များနှင့် မိမိအား Block ထားသော User ID များကို Filter လုပ်ခြင်း
        $blockedUserIds = method_exists($user, 'blockedUsers') ? $user->blockedUsers()->pluck('blocked_user_id')->toArray() : [];
        $blockedByUsers = method_exists($user, 'blockedByUsers') ? $user->blockedByUsers()->pluck('user_id')->toArray() : [];
        $allBlockedIds = array_unique(array_merge($blockedUserIds, $blockedByUsers));

        $query = $user->savedPosts();

        // Block ထားသော User များ၏ Post များကို ဖယ်ထုတ်ခြင်း
        if (!empty($allBlockedIds)) {
            $query->whereNotIn('posts.user_id', $allBlockedIds);
        }

        if (!empty(trim($this->search))) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhere('category', 'like', $searchTerm);
            });
        }

        return $query->orderBy('saved_posts.created_at', $this->sort === 'oldest' ? 'asc' : 'desc');
    }

    public function render()
    {
        $savedPosts = Auth::check() ? $this->getSavedPostsQuery()->get() : collect();

        return view('livewire.dashboard.post.saved-posts', [
            'savedPosts' => $savedPosts
        ])->layout('layouts.app');
    }
}
