<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Auth\Register;
use Illuminate\Auth\Events\Registered;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@joe.com')
        ->set('email_confirmation', 'joe@joe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'joe@joe.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user())->id
        ->toBe(User::first()->id);
});

test('validation rules', function ($f) {
    if ($f->rule === 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Register::class)
        ->set($f->field, $f->value);

    if (property_exists($f, 'aValue')) {
        $livewire->set($f->aField, $f->aValue);
    }

    $livewire->call('submit')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required' => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],

    'name::max:255' => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],

    'email::required' => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],

    'email::email' => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],

    'email::max:255' => (object)['field' => 'email', 'value' => str_repeat('*' . '@joe.com', 256), 'rule' => 'max'],

    'email::confirmed' => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'confirmed'],

    'email::unique' => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'unique', 'aField' => 'email_confirmation', 'aValue' => 'joe@doe.com'],

    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
]);

it('should dispatch Registered event', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@joe.com')
        ->set('email_confirmation', 'joe@joe.com')
        ->set('password', 'password')
        ->call('submit');

    Event::assertDispatched(Registered::class);
});
