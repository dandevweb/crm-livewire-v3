<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Auth\Password\Recovery;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;

test('need to receive a valid token with a combination with the email', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('startProcessRecovery');

    Notification::assertSentTo(
        $user,
        ResetPassword::class,
        function (ResetPassword $notification) {
            get(route('password.reset') . '?token=' . $notification->token)
            ->assertSuccessful();

            get(route('password.reset') . '?token=any-token')
            ->assertRedirect(route('login'));

            return true;
        }
    );
});
