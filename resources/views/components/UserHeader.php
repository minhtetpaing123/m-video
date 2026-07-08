<?php
// app/View/Components/UserHeader.php
namespace App\View\Components;

use Illuminate\View\Component;

class UserHeader extends Component
{
    public function render()
    {
        return view('components.user-header');
    }
}