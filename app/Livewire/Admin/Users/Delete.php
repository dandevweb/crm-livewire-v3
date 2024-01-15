<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\View\View;
use App\Notifications\UserDeletedNotification;

class Delete extends Component
{
    public User $user;
    public bool $modal = false;

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
        $this->user->notify(new UserDeletedNotification());

        $this->dispatch('user::deleted');
    }
}
