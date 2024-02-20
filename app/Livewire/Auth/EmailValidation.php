<?php

namespace App\Livewire\Auth;

use Closure;
use Livewire\Component;
use App\Events\SendNewCode;
use Illuminate\Contracts\View\View;

class EmailValidation extends Component
{
    public ?string $code = null;

    public function render(): View
    {
        return view('livewire.auth.email-validation');
    }

    public function handle(): void
    {
        $this->validate([
            'code' => function (string $attribute, mixed $value, Closure $fail) {
                if ($value !== auth()->user()->validation_code) {
                    $fail('Invalid code');
                }
            },
        ]);
    }

    public function sendNewCode(): void
    {
        SendNewCode::dispatch(auth()->user());
    }
}
