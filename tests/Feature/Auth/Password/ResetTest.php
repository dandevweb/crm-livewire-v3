<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Auth\Password\{Recovery, Reset};
use Illuminate\Support\Facades\{Hash, Notification};
use Illuminate\Auth\Notifications\ResetPassword;

use function Pest\Laravel\get;

test('need to receive a valid token with a combination with the email and open the page', function () {
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

test('if is possible to reset the password with the given token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('startProcessRecovery');

    Notification::assertSentTo(
        $user,
        ResetPassword::class,
        function (ResetPassword $notification) use ($user) {

            Livewire::test(
                Reset::class,
                ['token' => $notification->token, 'email' => $user->email]
            )
                ->set('email_confirmation', $user->email)
                ->set('password', 'new-password')
                ->set('password_confirmation', 'new-password')
                ->call('updatePassword')
                ->assertHasNoErrors()
                ->assertRedirect(route('dashboard'));

            $user->refresh();

            expect(Hash::check('new-password', $user->password))->toBeTrue();

            return true;
        }
    );
});
