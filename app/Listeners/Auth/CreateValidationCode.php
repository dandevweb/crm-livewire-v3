<?php

namespace App\Listeners\Auth;

use App\Events\SendNewCode;
use Illuminate\Auth\Events\Registered;
use App\Notifications\Auth\ValidationCodeNotification;

class CreateValidationCode
{
    public function handle(Registered|SendNewCode $event): void
    {
        /** @var \App\Models\User $user */
        $user                  = $event->user;
        $user->validation_code = random_int(100000, 999999);
        $user->save();

        $user->notify(new ValidationCodeNotification());
    }
}
