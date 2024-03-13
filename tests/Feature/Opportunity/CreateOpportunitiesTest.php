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
        ->set('form.name', 'John Doe')
        ->assertPropertyWired('form.name')
        ->set('form.email', 'joe@joe.com')
        ->assertPropertyWired('form.email')
        ->set('form.phone', '1234567890')
        ->assertPropertyWired('form.phone')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors();

    assertDatabaseHas('customers', [
        'name'  => 'John Doe',
        'email' => 'joe@joe.com',
        'phone' => '1234567890',
        'type'  => 'customer',
    ]);
});

describe('validations', function () {
    test('name should required', function ($rule, $value) {
        Livewire::test(Customers\Create::class)
            ->set('form.name', $value)
            ->call('save')
            ->assertHasErrors(['form.name' => $rule]);
    })->with([
        'required' => ['required', ''],
        'min'      => ['min', 'Jo'],
        'max'      => ['max', str_repeat('a', 256)],
    ]);

    test('email should be required if don`t have a phone', function () {
        Livewire::test(Customers\Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', '')
            ->set('form.phone', '')
            ->call('save')
            ->assertHasErrors(['form.email' => 'required_without']);
    });

    test('email should be valid', function () {
        Livewire::test(Customers\Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', 'joe')
            ->set('form.phone', '1234567890')
            ->call('save')
            ->assertHasErrors(['form.email' => 'email']);
    });

    test('email should be unique', function () {
        $email = 'joe@joe.com';

        Customer::factory()->create(['email' => $email]);

        Livewire::test(Customers\Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', $email)
            ->set('form.phone', '1234567890')
            ->call('save')
            ->assertHasErrors(['form.email' => 'unique']);
    });

    test('phone should be required if don`t have a email', function () {
        Livewire::test(Customers\Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', '')
            ->set('form.phone', '')
            ->call('save')
            ->assertHasErrors(['form.phone' => 'required_without']);
    });

    test('phone should be unique', function () {
        $phone = '1234567890';

        Customer::factory()->create(['phone' => $phone]);

        Livewire::test(Customers\Create::class)
            ->set('form.name', 'John Doe')
            ->set('form.email', 'joe@joe.com')
            ->set('form.phone', $phone)
            ->call('save')
            ->assertHasErrors(['form.phone' => 'unique']);

    });

});

test('check if component is in the page', function () {
    Livewire::test(Customers\Index::class)
        ->assertContainsLivewireComponent('customers.create');
});
