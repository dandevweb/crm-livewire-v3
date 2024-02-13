<?php

namespace App\Livewire\Dev;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Collection;

class Login extends Component
{
    public ?int $selectedUser = null;

    public function render()
    {
        return view('livewire.dev.login');
    }

    #[Computed]
    public function users(): Collection
    {
        return User::all();
    }

    public function login(): void
    {
        auth()->loginUsingId($this->selectedUser);

        $this->redirect(route('dashboard'));
    }
}
