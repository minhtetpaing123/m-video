<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Toast extends Component
{
    public $message;
    public $type;

    public function __construct($message = null, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.toast');
    }
}