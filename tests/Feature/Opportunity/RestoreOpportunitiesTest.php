<?php

use Livewire\Livewire;
use App\Models\Opportunity;
use App\Livewire\Opportunities\Index;

use App\Livewire\Opportunities\{Restore};

use function Pest\Laravel\assertNotSoftDeleted;

it('should be able to restore a opportunity', function () {

    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('opportunity', $opportunity)
        ->call('restore');

    assertNotSoftDeleted('opportunities', ['id' => $opportunity->id]);

});

test('when confirming we should load the opportunity and set modal to true', function () {

    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->call('confirmAction', $opportunity->id)
        ->assertSet('opportunity.id', $opportunity->id)
        ->assertSet('modal', true)
        ->assertPropertyEntangled('modal');
});

test('after restoring we should dispatch an event to tell the list to reload', function () {

    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('opportunity', $opportunity)
        ->call('restore')
        ->assertDispatched('opportunity::reload');
});

test('after restoring we should close the modal', function () {

    $opportunity = Opportunity::factory()->deleted()->create();

    Livewire::test(Restore::class)
        ->set('opportunity', $opportunity)
        ->call('restore')
        ->assertSet('modal', false);
});

test('making sure restore method is wired', function () {
    Livewire::test(Restore::class)
        ->assertMethodWired('restore');
});

test('check if component is in the page', function () {
    Livewire::test(Index::class)
        ->assertContainsLivewireComponent('opportunities.restore');
});
