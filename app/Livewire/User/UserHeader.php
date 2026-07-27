<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class UserHeader extends Component
{
    public $user;
    public $searchQuery = '';
    public $isSearchOpen = false;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function toggleSearch()
    {
        $this->isSearchOpen = !$this->isSearchOpen;
    }

    public function closeSearch()
    {
        $this->isSearchOpen = false;
        $this->searchQuery = '';
    }

    public function search()
    {
        if (trim($this->searchQuery) === '') {
            return;
        }

        return redirect()->route('search', ['q' => $this->searchQuery]);
    }

    public function getUserAvatar()
    {
        if ($this->user && $this->user->avatar) {
            return $this->user->avatar;
        }

        // Facebook Avatar
        if ($this->user) {
            return 'https://graph.facebook.com/' . $this->user->id . '/picture?type=square&width=40&height=40';
        }

        // Default Avatar (Base64 SVG)
        return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiByeD0iMjAiIGZpbGw9InVybCgjbGluZWFyLWdyYWRpZW50KSIvPgo8ZGVmcz4KPGxpbmVhckdyYWRpZW50IGlkPSJsaW5lYXItZ3JhZGllbnQiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPgo8c3RvcCBvZmZzZXQ9IjAlIiBzdHlsZT0ic3RvcC1jb2xvcjojMTg3N0YyOyIvPgo8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0eWxlPSJzdG9wLWNvbG9yOiM0MkI3MkE7Ii8+CjwvbGluZWFyR3JhZGllbnQ+CjwvZGVmcz4KPC9zdmc+Cg==';
    }

    public function render()
    {
        return view('livewire.user.user-header', [
            'avatar' => $this->getUserAvatar(),
        ]);
    }
}