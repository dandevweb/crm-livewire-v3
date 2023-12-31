<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\{DB, Hash, Password};
use Livewire\Attributes\{Computed, Layout, Rule};

class Reset extends Component
{
    public ?string $token = null;

    #[Rule(['required', 'email', 'confirmed'])]
    public ?string $email              = null;
    public ?string $email_confirmation = null;

    #[Rule(['required', 'confirmed'])]
    public ?string $password              = null;
    public ?string $password_confirmation = null;


    public function mount(?string $token = null, ?string $email = null)
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if ($this->tokenNotValid()) {
            session()->flash('status', 'Token Invalid');
            $this->redirectRoute('login');
        }
    }


    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.auth.password.reset');
    }

    public function updatePassword(): void
    {
        $this->validate();
        $status = Password::reset(
            [
                'token'                 => $this->token,
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function (User $user, $password) {
                $user->forceFill(
                    [
                        'password'       => $password,
                        'remember_token' => Str::random(60),
                    ]
                )->save();

                event(new PasswordReset($user));

            }
        );

        session()->flash('status', __($status));
        if ($status !== Password::PASSWORD_RESET) {
            return;
        }

        $this->redirect(route('login'));
    }

    #[Computed]
    public function obfuscatedEmail(): string
    {
        return obfuscateEmail($this->email);
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
