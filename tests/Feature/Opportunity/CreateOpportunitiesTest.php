<?php

use Livewire\Livewire;
use App\Livewire\Opportunities;
use App\Models\{Customer, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('should be able to create a opportunity', function () {
    $customer = Customer::factory()->create();

    Livewire::test(Opportunities\Create::class)
        ->set('form.customer_id', $customer->id)
        ->set('form.title', 'John Doe')
        ->assertPropertyWired('form.title')
        ->set('form.status', 'won')
        ->assertPropertyWired('form.status')
        ->set('form.amount', '123.45')
        ->assertPropertyWired('form.amount')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors();

    assertDatabaseHas('opportunities', [
        'customer_id' => $customer->id,
        'title'       => 'John Doe',
        'status'      => 'won',
        'amount'      => '12345',
    ]);
});

describe('validations', function () {
    test('customer', function ($rule, $value) {
        Livewire::test(Opportunities\Create::class)
            ->set('form.customer_id', $value)
            ->call('save')
            ->assertHasErrors(['form.customer_id' => $rule]);
    })->with([
        'required' => ['required', ''],
        'exists'   => ['exists', 999],
    ]);

    test('title should required', function ($rule, $value) {
        Livewire::test(Opportunities\Create::class)
            ->set('form.title', $value)
            ->call('save')
            ->assertHasErrors(['form.title' => $rule]);
    })->with([
        'required' => ['required', ''],
        'min'      => ['min', 'Jo'],
        'max'      => ['max', str_repeat('a', 256)],
    ]);


    test('status should required', function ($rule, $value) {
        Livewire::test(Opportunities\Create::class)
            ->set('form.status', $value)
            ->call('save')
            ->assertHasErrors(['form.status' => $rule]);
    })->with([
        'required' => ['required', ''],
        'in'       => ['in', 'Jo'],
    ]);


    test('amount should required', function ($rule, $value) {
        Livewire::test(Opportunities\Create::class)
            ->set('form.amount', $value)
            ->call('save')
            ->assertHasErrors(['form.amount' => $rule]);
    })->with([
        'required' => ['required', ''],
    ]);

});

test('check if component is in the page', function () {
    Livewire::test(Opportunities\Index::class)
        ->assertContainsLivewireComponent('opportunities.create');
});
