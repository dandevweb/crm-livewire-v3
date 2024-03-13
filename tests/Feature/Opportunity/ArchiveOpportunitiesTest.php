<?php


use Livewire\Livewire;
use App\Models\Customer;
use App\Livewire\Customers\{Archive, Index};

use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\assertSoftDeleted;

it('should be able to archive a customer', function () {

    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->call('archive');

    assertSoftDeleted('customers', ['id' => $customer->id]);

});

test('when confirming we should load the customer and set modal to true', function () {

    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->call('confirmAction', $customer->id)
        ->assertSet('customer.id', $customer->id)
        ->assertSet('modal', true);
});

test('after archiving we should dispatch an event to tell the list to reload', function () {

    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->call('archive')
        ->assertDispatched('customer::reload');
});

test('after archiving we should close the modal', function () {

    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->call('archive')
        ->assertSet('modal', false);
});

it('should list archived items', function () {
    $notArchived = Customer::factory(2)->create();
    $archived    = Customer::factory()->deleted()->create();

    Livewire::test(Index::class)
        ->set('search_trash', false)
        ->assertSee('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(2)
                ->and(
                    collect($items->items())
                        ->filter(fn (Customer $customer) => $customer->id === $archived->id)
                )->toBeEmpty();

            return true;
        })
        ->set('search_trash', true)
        ->assertSee('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(1)
                ->and(
                    collect($items->items())
                        ->filter(fn (Customer $customer) => $customer->id === $archived->id)
                )->not->toBeEmpty();

            return true;
        });
});

test('making sure archive method is wired', function () {
    Livewire::test(Archive::class)
        ->assertMethodWired('archive');
});

test('check if component is in the page', function () {
    Livewire::test(Index::class)
        ->assertContainsLivewireComponent('customers.archive');
});
