<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Delete extends Component
{
    public User $user;

    #[Validate(['required', 'confirmed'])]
    public string $confirmation = 'DART VADER';

    public ?string $confirmation_confirmation = null;

    public function render(): View
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy(): void
    {
        $this->validate();
        $this->user->delete();

        $this->dispatch('user::deleted');
    }
}
