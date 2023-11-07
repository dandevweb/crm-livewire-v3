<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public ?string $email;
    public ?string $password;

    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function tryToLogin(): void
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Invalid credentials.');

            return;
        }

        $this->redirect(route('dashboard'));
    }
}
