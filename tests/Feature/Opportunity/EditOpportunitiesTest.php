<?php

use Livewire\Livewire;
use App\Livewire\Opportunities;
use App\Models\{Opportunity, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};

beforeEach(function () {
    actingAs(User::factory()->create());
    $this->opportunity = Opportunity::factory()->create();
});

it('should be able to updated a opportunity', function () {
    Livewire::test(Opportunities\Update::class)
        ->call('load', $this->opportunity->id)
        ->set('form.title', 'John Doe')
        ->assertPropertyWired('form.title')
        ->set('form.status', 'won')
        ->assertPropertyWired('form.status')
        ->set('form.amount', '1234567890')
        ->assertPropertyWired('form.amount')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors();

    assertDatabaseHas('opportunities', [
        'id'     => $this->opportunity->id,
        'title'  => 'John Doe',
        'status' => 'won',
        'amount' => '1234567890',
    ]);
});

describe('validations', function () {
    test('title should required', function ($rule, $value) {
        Livewire::test(Opportunities\Update::class)
            ->call('load', $this->opportunity->id)
            ->set('form.title', $value)
            ->call('save')
            ->assertHasErrors(['form.title' => $rule]);
    })->with([
        'required' => ['required', ''],
        'min'      => ['min', 'Jo'],
        'max'      => ['max', str_repeat('a', 256)],
    ]);

    test('status should required', function ($rule, $value) {
        Livewire::test(Opportunities\Update::class)
        ->call('load', $this->opportunity->id)
            ->set('form.status', $value)
            ->call('save')
            ->assertHasErrors(['form.status' => $rule]);
    })->with([
        'required' => ['required', ''],
        'in'       => ['in', 'Jo'],
    ]);


    test('amount should required', function ($rule, $value) {
        Livewire::test(Opportunities\Update::class)
        ->call('load', $this->opportunity->id)
            ->set('form.amount', $value)
            ->call('save')
            ->assertHasErrors(['form.amount' => $rule]);
    })->with([
        'required' => ['required', ''],
    ]);

});

test('check if component is in the page', function () {
    Livewire::test(Opportunities\Index::class)
        ->assertContainsLivewireComponent('opportunities.update');
});
