<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class StopImpersonate extends Component
{
    public function render(): View
    {
        return view('livewire.admin.users.stop-impersonate');
    }

    public function stop(): void
    {
        session()->forget('impersonate');

        $this->redirect(route('admin.users'));
    }
}
