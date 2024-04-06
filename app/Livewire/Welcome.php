<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\View\View;

class Welcome extends Component
{
    public function render(): View
    {
        return view('livewire.welcome');
    }
}
