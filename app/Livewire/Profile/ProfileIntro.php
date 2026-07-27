<?php
// File Path: app/Livewire/Profile/ProfileIntro.php
// Purpose: User ရဲ့ အချက်အလက်များ (Uploaded Videos Count, Followers Count, Joined Date) ကို သီးသန့်ထုတ်ပြပေးသော Intro Component ဖြစ်ပါသည်။

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class ProfileIntro extends Component
{
    public User $user;
    public int $videoCount = 0;

    public function mount(User $user, int $videoCount = 0)
    {
        $this->user = $user;
        $this->videoCount = $videoCount;
    }

    public function render()
    {
        return view('livewire.profile.profile-intro');
    }
}
