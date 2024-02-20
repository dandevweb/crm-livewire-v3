<?php

namespace App\Livewire\Auth;

use Closure;
use Livewire\Component;
use App\Events\SendNewCode;
use Illuminate\Contracts\View\View;
use App\Providers\RouteServiceProvider;
use App\Notifications\WelcomeNotification;

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
                if ($value != auth()->user()->validation_code) {
                    $fail('Invalid code');
                }
            },
        ]);

        $user = auth()->user();

        $user->validation_code   = null;
        $user->email_verified_at = now();
        $user->save();

        $user->notify(new WelcomeNotification());

        $this->redirect(RouteServiceProvider::HOME);
    }

    public function sendNewCode(): void
    {
        SendNewCode::dispatch(auth()->user());
    }
}
