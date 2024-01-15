<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Delete extends Component
{
    public User $user;

    public function render(): View
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy(): void
    {
        $this->user->delete();
        $this->dispatch('user::deleted');
    }
}
