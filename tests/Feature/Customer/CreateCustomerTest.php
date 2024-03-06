<?php

use Livewire\Livewire;
use App\Livewire\Customers;
use App\Models\{Customer, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to create a customer', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@joe.com')
        ->set('phone', '1234567890')
        ->call('save')
        ->assertHasNoErrors();

    assertDatabaseHas('customers', [
        'name'  => 'John Doe',
        'email' => 'joe@joe.com',
        'phone' => '1234567890',
        'type'  => 'customer',
    ]);
});


test('name should required', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', '')
        ->set('email', 'joe@joe.com')
        ->set('phone', '1234567890')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('name should min 3 characters', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', 'Jo')
        ->set('email', 'joe@joe.com')
        ->set('phone', '1234567890')
        ->call('save')
        ->assertHasErrors(['name' => 'min']);
});

test('name should max 255 characters', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', str_repeat('a', 256))
        ->set('email', 'joe@joe.com')
        ->set('phone', '1234567890')
        ->call('save')
        ->assertHasErrors(['name' => 'max']);
});

test('email should be required if dont have a phone', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', 'John Doe')
        ->set('email', '')
        ->set('phone', '')
        ->call('save')
        ->assertHasErrors(['email' => 'required_without']);
});

test('email should be valid', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe')
        ->set('phone', '1234567890')
        ->call('save')
        ->assertHasErrors(['email' => 'email']);
});

test('email should be unique', function () {
    $email = 'joe@joe.com';

    Customer::factory()->create(['email' => $email]);

    Livewire::test(Customers\Create::class)
        ->set('name', 'John Doe')
        ->set('email', $email)
        ->set('phone', '1234567890')
        ->call('save')
        ->assertHasErrors(['email' => 'unique']);
});

test('phone should be required if dont have a email', function () {
    Livewire::test(Customers\Create::class)
        ->set('name', 'John Doe')
        ->set('email', '')
        ->set('phone', '')
        ->call('save')
        ->assertHasErrors(['phone' => 'required_without']);
});

test('phone should be unique', function () {
    $phone = '1234567890';

    Customer::factory()->create(['phone' => $phone]);
    Livewire::test(Customers\Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@joe.com')
        ->set('phone', $phone)
        ->call('save')
        ->assertHasErrors(['phone' => 'unique']);

});
