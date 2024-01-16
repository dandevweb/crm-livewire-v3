<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Rule};
use App\Notifications\{UserRestoreAccessNotification, UserRestoredNotification};

class Restore extends Component
{
    use Toast;

    public ?User $user = null;

    public bool $modal = false;

    #[Rule(['required', 'confirmed'])]
    public string $confirmation = 'YODA';

    public ?string $confirmation_confirmation = null;

    public function render(): View
    {
        return view('livewire.admin.users.restore');
    }

    #[On('user::restoring')]
    public function openConfirmationFor(int $userId): void
    {
        $this->user  = User::select('id', 'name')->withTrashed()->find($userId);
        $this->modal = true;
    }

    public function restore(): void
    {
        $this->validate();

        if($this->user->is(auth()->user())) {

            $this->addError('confirmation', "You can't restore yourself brow.");

            return;
        }

        $this->user->restore();

        $this->user->notify(new UserRestoreAccessNotification());
        $this->success('User restored successfully.');
        $this->dispatch('user::restored');
        $this->reset('modal');
    }
}
