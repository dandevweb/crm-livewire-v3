<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use App\Notifications\PasswordRecoveryNotification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class Recovery extends Component
{
    public ?string $message = null;

    #[Rule(['required', 'email'])]
    public ?string $email = null;

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.password.recovery');
    }

    public function startProcessRecovery(): void
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        $user?->notify(new PasswordRecoveryNotification);

        $this->message = "You will receive an email with the password recovery link.";
    }
}
