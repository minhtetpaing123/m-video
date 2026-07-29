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
    public $showFollowModal = false;
    public $followUserId = null;
    public $followType = 'followers';

    // Listen for profile updates
    protected $listeners = [
        'profileUpdated' => 'refreshProfile',
        'followUpdated' => '$refresh',
        'openFollowList' => 'openFollowList',
        'closeFollowList' => 'closeFollowList',
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

    /**
     * Open Follow List Modal
     */
    public function openFollowList($userId, $type = 'followers')
    {
        $this->followUserId = $userId;
        $this->followType = $type;
        $this->showFollowModal = true;
    }

    /**
     * Close Follow List Modal
     */
    public function closeFollowList()
    {
        $this->showFollowModal = false;
        $this->followUserId = null;
        $this->followType = 'followers';
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
        ])->layout('livewire.layout.app');
    }
}