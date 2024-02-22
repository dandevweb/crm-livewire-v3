<?php

use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Providers\RouteServiceProvider;
use Livewire\Livewire;
use App\Livewire\Auth\{EmailValidation, Register};
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;

use App\Listeners\Auth\CreateValidationCode;

use App\Notifications\Auth\ValidationCodeNotification;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    Notification::fake();
});

describe('after registration', function () {

    it('should create a new validation code and save in the users table', function () {
        $user = User::factory()->create(['email_verified_at' => null, 'validation_code' => null]);

        $event    = new Registered($user);
        $listener = new CreateValidationCode();
        $listener->handle($event);

        $user->refresh();

        expect($user->validation_code)->not->toBeNull()
            ->and($user)->validation_code->toBeNumeric();

        assertTrue(strlen($user->validation_code) === 6);

    });

    it('should send that new code to the user via email', function () {
        $user = User::factory()->create(['email_verified_at' => null, 'validation_code' => null]);

        $event    = new Registered($user);
        $listener = new CreateValidationCode();
        $listener->handle($event);

        Notification::assertSentTo(
            $user,
            ValidationCodeNotification::class
        );

    });


    test('making sure that the listener to send the code is linked to the Registered event', function () {
        Event::fake();

        Event::assertListening(Registered::class, CreateValidationCode::class);

    });
});

describe('validation page', function () {
    it('should redirect to the validation page after registration', function () {
        Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@joe.com')
        ->set('email_confirmation', 'joe@joe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('auth.email-validation'));
    });

    it('should check if code is valid', function () {
        $user = User::factory()->withValidationCode()->create();

        actingAs($user);

        Livewire::test(EmailValidation::class)
            ->set('code', '000000')
            ->call('handle')
            ->assertHasErrors([
                'code'
            ]);
    });

    it('should be able to send a new code to the user', function () {
        $user = User::factory()->withValidationCode()->create();

        actingAs($user);

        $oldCode = $user->validation_code;

        Livewire::test(EmailValidation::class)
            ->call('sendNewCode');

        expect($user->validation_code)->not->toBe($oldCode);

        Notification::assertSentTo(
            $user,
            ValidationCodeNotification::class
        );
    });

    it('should update email_verified_at and delete the code if the code is valid', function () {
        $user = User::factory()->withValidationCode()->create();

        actingAs($user);

        Livewire::test(EmailValidation::class)
            ->set('code', $user->validation_code)
            ->call('handle')
            ->assertHasNoErrors()
            ->assertRedirect(RouteServiceProvider::HOME);

        expect($user)->email_verified_at->not->toBeNull()
            ->validation_code->toBeNull();

        Notification::assertSentTo(
            $user,
            WelcomeNotification::class
        );
    });

});

describe('middleware', function () {
    it('should redirect to the email-verification if email_verified_at id null and the user is logged in', function () {
        $user = User::factory()->withValidationCode()->create();

        actingAs($user);

        get(route('dashboard'))
            ->assertRedirect(route('auth.email-validation'));

    });

});
