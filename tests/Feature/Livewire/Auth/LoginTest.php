<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Auth\Login;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@joe.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'joe@joe.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});
