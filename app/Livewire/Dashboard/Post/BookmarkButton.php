<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class BookmarkButton extends Component
{
    public Post $post;
    public bool $isSaved = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->isSaved = Auth::check() ? $post->isSavedBy(Auth::user()) : false;
    }

    public function toggleBookmark(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $user = Auth::user();

        if ($this->isSaved) {
            $user->savedPosts()->detach($this->post->id);
            $this->isSaved = false;
        } else {
            $user->savedPosts()->attach($this->post->id);
            $this->isSaved = true;
        }

        $this->dispatch('bookmark-updated');
    }

    public function render()
    {
        return view('livewire.dashboard.post.bookmark-button');
    }
}
