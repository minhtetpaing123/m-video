<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;

class UserHeader extends Component
{
    public string $search = '';

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function render()
    {
        $users = collect();
        $searchResults = collect();

        if (strlen(trim($this->search)) > 1) {
            $searchTerm = '%' . trim($this->search) . '%';

            $users = User::query()
                ->where('name', 'like', $searchTerm)
                ->limit(4)
                ->get();

            $searchResults = Post::query()
                ->where('title', 'like', $searchTerm)
                ->orWhere('content', 'like', $searchTerm)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('livewire.dashboard.user-header', [
            'users' => $users,
            'searchResults' => $searchResults,
        ]);
    }
}
