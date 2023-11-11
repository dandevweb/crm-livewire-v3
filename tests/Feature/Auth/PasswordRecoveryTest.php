<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\get;
use App\Livewire\Auth\Password\Recovery;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordRecoveryNotification;

test('needs to have a route to password recovery', function () {
    get(route('auth.password.recovery'))
        ->assertSeeLivewire('auth.password.recovery')
        ->assertOk();
});

it('should be able to request for a password recovery sending notification to the user', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->assertDontSee("You will receive an email with the password recovery link.")
        ->set('email', $user->email)
        ->call('startProcessRecovery')
        ->assertSee("You will receive an email with the password recovery link.");

    Notification::assertSentTo($user,  PasswordRecoveryNotification::class);
});

test('email property', function ($value, $rule) {
    Livewire::test(Recovery::class)
        ->set('email', $value)
        ->call('startProcessRecovery')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email' => ['value' => 'not-a-valid-email', 'rule' => 'email'],
]);
