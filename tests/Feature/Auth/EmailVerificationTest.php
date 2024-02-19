<?php

use Livewire\Livewire;
use App\Livewire\Auth\Register;

use function Pest\Laravel\assertDatabaseCount;

it('should send a verfication code to the new users email', function () {

    Livewire::test(Register::class)
    ->set('name', 'John Doe')
    ->set('email', 'joe@joe.com')
    ->set('email_confirmation', 'joe@joe.com')
    ->set('password', 'password')
    ->call('submit');

    assertDatabaseCount('users', 1);

});
