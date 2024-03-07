<?php

use Livewire\Livewire;
use App\Models\Customer;
use App\Livewire\Customers\Archive;

use function Pest\Laravel\assertSoftDeleted;

it('should be able to archive a customer', function () {

    $customer = Customer::factory()->create();

    Livewire::test(Archive::class)
        ->set('customer', $customer)
        ->call('archive');

    assertSoftDeleted('customers', ['id' => $customer->id]);

});
