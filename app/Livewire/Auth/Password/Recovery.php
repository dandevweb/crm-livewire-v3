<?php

namespace App\Livewire\Auth\Password;

use Livewire\Component;
use Livewire\Attributes\{Layout, Rule};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;

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

        Password::sendResetLink($this->only('email'));

        $this->message = "You will receive an email with the password recovery link.";
    }
}
