<?php


use Livewire\Livewire;
use App\Models\Opportunity;
use App\Livewire\Opportunities\{Archive, Index};

use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\assertSoftDeleted;

it('should be able to archive a opportunity', function () {

    $opportunity = Opportunity::factory()->create();

    Livewire::test(Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive');

    assertSoftDeleted('opportunities', ['id' => $opportunity->id]);

});

test('when confirming we should load the opportunity and set modal to true', function () {

    $opportunity = Opportunity::factory()->create();

    Livewire::test(Archive::class)
        ->call('confirmAction', $opportunity->id)
        ->assertSet('opportunity.id', $opportunity->id)
        ->assertSet('modal', true);
});

test('after archiving we should dispatch an event to tell the list to reload', function () {

    $opportunity = Opportunity::factory()->create();

    Livewire::test(Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive')
        ->assertDispatched('opportunity::reload');
});

test('after archiving we should close the modal', function () {

    $opportunity = Opportunity::factory()->create();

    Livewire::test(Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive')
        ->assertSet('modal', false);
});

it('should list archived items', function () {
    $notArchived = Opportunity::factory(2)->create();
    $archived    = Opportunity::factory()->deleted()->create();

    Livewire::test(Index::class)
        ->set('search_trash', false)
        ->assertSee('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(2)
                ->and(
                    collect($items->items())
                        ->filter(fn (Opportunity $opportunity) => $opportunity->id === $archived->id)
                )->toBeEmpty();

            return true;
        })
        ->set('search_trash', true)
        ->assertSee('items', function (LengthAwarePaginator $items) use ($archived) {
            expect($items->items())->toHaveCount(1)
                ->and(
                    collect($items->items())
                        ->filter(fn (Opportunity $opportunity) => $opportunity->id === $archived->id)
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
        ->assertContainsLivewireComponent('opportunities.archive');
});
