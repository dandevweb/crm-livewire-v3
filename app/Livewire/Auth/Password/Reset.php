<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Support\Facades\{DB, Hash};
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public function mount()
    {
        $this->token = request('token');

        if ($this->tokenNotValid()) {
            session()->flash('status', 'Token Invalid');
            $this->redirectRoute('login');
        }
    }


    public function render()
    {
        return view('livewire.auth.password.reset');
    }

    private function tokenNotValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')
            ->get(['token']);

        foreach ($tokens as $token) {
            if (Hash::check($this->token, $token->token)) {
                return false;
            }
        }

        return true;
    }
}
