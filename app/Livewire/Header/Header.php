<?php

namespace App\Livewire\Header;

use Livewire\Component;

class Header extends Component
{
    // User ရဲ့ status ပြောင်းလဲမှု (ဥပမာ- Logout ဖြစ်သွားတာမျိုး) ကို လှမ်းသိနိုင်ဖို့ listeners ထည့်ထားပေးနိုင်ပါတယ်
    protected $listeners = ['authUpdated' => '$refresh'];

    public function render()
    {
        return view('livewire.header.header');
    }
}
