<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class Header extends Component
{
    public $user;
    public $videoCount;
    public $followersCount = 0;
    public $isFollowing = false;

    protected $listeners = [
        'profileUpdated' => 'refreshProfile',
        'followUpdated' => 'refreshFollow',
    ];

    public function mount($user, $videoCount = 0)
    {
        $this->user = $user;
        $this->videoCount = $videoCount;
        
        // ✅ Simple followers count (without followers() method)
        $this->followersCount = 0; // ယာယီအနေနဲ့ 0 ထားမယ်
        $this->isFollowing = false;
    }

    public function refreshProfile()
    {
        $this->user->refresh();
        $this->videoCount = $this->user->posts()->count();
    }

    public function refreshFollow()
    {
        // Placeholder for follow logic
    }

    public function toggleFollow()
    {
        // Placeholder for follow logic
        session()->flash('message', 'Follow feature coming soon!');
    }

    public function render()
    {
        return view('livewire.profile.header');
    }
}