<?php

namespace App\Livewire\Admin;

use App\Enum\Can;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Dashboard extends Component
{
    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
