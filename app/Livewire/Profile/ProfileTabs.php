<?php
// File Path: app/Livewire/Profile/ProfileTabs.php
// Purpose: Profile စာမျက်နှာရှိ Posts, Videos, About, Photos Tab မ်ားကို Switch လုပျရနျ စီမံပေးသော Component ဖွစျပါသညျ။

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\Url;

class ProfileTabs extends Component
{
    #[Url]
    public $activeTab = 'posts';

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('profile-tab-changed', tab: $tab);
    }

    public function render()
    {
        return view('livewire.profile.profile-tabs');
    }
}
