<?php
// File Path: app/Livewire/Profile/ProfileFeed.php
// Purpose: Tab အလိုက် (Posts, Videos, Photos, About) Switch လုပ်၍ Feed သို့မဟုတ် About Info ကို ပြသပေးသော Component ဖြစ်ပါသည်။

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\User;

class ProfileFeed extends Component
{
    use WithPagination;

    public User $user;
    public string $currentTab = 'posts';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    #[On('profile-tab-changed')]
    public function handleTabChange($tab)
    {
        $this->currentTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        // About Tab ဖြစ်နေလျှင် Post Query လုပ်ရန် မလိုပါ
        if ($this->currentTab === 'about') {
            return view('livewire.profile.profile-feed', [
                'posts' => null
            ]);
        }

        // Filter Logic
        $query = $this->user->posts()->latest();

        if ($this->currentTab === 'videos') {
            $query->whereNotNull('video_path');
        } elseif ($this->currentTab === 'photos') {
            $query->whereNotNull('image');
        }

        $posts = $query->paginate(5);

        return view('livewire.profile.profile-feed', [
            'posts' => $posts
        ]);
    }
}
