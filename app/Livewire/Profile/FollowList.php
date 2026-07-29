<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class FollowList extends Component
{
    use WithPagination;

    public User $profileUser;
    public string $type = 'followers';
    public ?string $search = null;
    public $perPage = 20;

    public $confirmingUnfollow = false;
    public $unfollowUserId = null;
    public $unfollowUserName = null;

    // ✅ Reverb Event Listener
    protected $listeners = [
        'refreshFollowList' => '$refresh',
        'echo:user-status,status-changed' => 'handleStatusChanged',
    ];

    protected $queryString = [
        'type' => ['except' => 'followers'],
        'search' => ['except' => ''],
    ];

    public function mount(User $user, string $type = 'followers')
    {
        $this->profileUser = $user;
        $this->type = $type;
    }

    /**
     * ✅ Status ပြောင်းတဲ့အခါ Refresh
     */
    public function handleStatusChanged($payload)
    {
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->dispatch('closeFollowList');
    }

    public function switchTab(string $type)
    {
        $this->type = $type;
        $this->resetPage();
        $this->search = null;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        $query = $this->type === 'followers' 
            ? $this->profileUser->followers() 
            : $this->profileUser->following();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate($this->perPage);
    }

    public function confirmUnfollow($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->unfollowUserId = $userId;
            $this->unfollowUserName = $user->name;
            $this->confirmingUnfollow = true;
        }
    }

    public function cancelUnfollow()
    {
        $this->confirmingUnfollow = false;
        $this->unfollowUserId = null;
        $this->unfollowUserName = null;
    }

    public function executeUnfollow()
    {
        if (!$this->unfollowUserId) {
            return;
        }

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $targetUser = User::find($this->unfollowUserId);
        
        if (!$targetUser || auth()->id() === $targetUser->id) {
            $this->cancelUnfollow();
            return;
        }

        if (auth()->user()->isFollowing($targetUser)) {
            auth()->user()->following()->detach($targetUser->id);
            session()->flash('message', 'Unfollowed ' . $targetUser->name . ' successfully!');
        }

        $this->cancelUnfollow();
        $this->resetPage();
    }

    public function toggleFollow(int $userId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $targetUser = User::findOrFail($userId);
        
        if (auth()->id() === $targetUser->id) {
            return;
        }

        if (auth()->user()->isFollowing($targetUser)) {
            $this->confirmUnfollow($userId);
            return;
        }

        auth()->user()->following()->attach($targetUser->id);
        session()->flash('message', 'Followed ' . $targetUser->name . ' successfully!');
        $this->resetPage();
    }

    /**
     * ✅ Get follow button state for a user
     */
    public function getFollowButtonState($user)
    {
        if (!auth()->check() || auth()->id() === $user->id) {
            return null;
        }

        $isFollowing = auth()->user()->isFollowing($user);
        $isFollowedBy = $user->isFollowedBy(auth()->user());

        // Follow Back: သူက ကိုယ်ကို Follow လုပ်ထားပြီး ကိုယ်က ပြန်မလုပ်ရသေး
        if ($isFollowedBy && !$isFollowing) {
            return [
                'text' => '↩ Follow Back',
                'class' => 'bg-blue-600 hover:bg-blue-500 text-white',
                'action' => 'follow'
            ];
        }

        // Following: ကိုယ်က Follow လုပ်ထားပြီးသား
        if ($isFollowing) {
            return [
                'text' => '✓ Following',
                'class' => 'bg-gray-700 hover:bg-gray-600 text-gray-200 border border-gray-600',
                'action' => 'unfollow'
            ];
        }

        // Follow: ဘာမှမလုပ်ရသေး
        return [
            'text' => 'Follow',
            'class' => 'bg-blue-600 hover:bg-blue-500 text-white',
            'action' => 'follow'
        ];
    }

    public function render()
    {
        return view('livewire.profile.follow-list', [
            'users' => $this->users,
        ]);
    }
}