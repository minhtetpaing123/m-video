<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $user;
    public $userId;

    // ✅ Listen for profile updates
    protected $listeners = [
        'profileUpdated' => 'refreshProfile',
        'followUpdated' => '$refresh',
    ];

    public function mount($user)
    {
        $this->user = User::withCount('posts')->findOrFail($user);
        $this->userId = $this->user->id;
    }

    public function refreshProfile()
    {
        $this->user->refresh();
        $this->userId = $this->user->id;
    }

    public function render()
    {
        $videos = Post::where('user_id', $this->userId)
            ->where(function($q) {
                $q->whereNotNull('video_cdn_url')
                  ->orWhereNotNull('link')
                  ->orWhereNotNull('image');
            })
            ->latest()
            ->paginate(12);

        return view('livewire.profile.show', [
            'videos' => $videos,
            'videoCount' => $this->user->posts()->count(),
        ])->layout('layouts.app');
    }
}