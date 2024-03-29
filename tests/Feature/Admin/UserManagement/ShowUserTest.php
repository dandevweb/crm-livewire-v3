<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Users\{Index, Show};

use function Pest\Laravel\actingAs;

it('should be able to show all the details of the user in the component', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('load', $user->id)
        ->assertSet('user.id', $user->id)
        ->assertSet('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y'))
        ->assertSee($user->updated_at->format('d/m/Y'))
        ->assertSee($user->deleted_at->format('d/m/Y'))
        ->assertSee($user->deletedBy?->name);
});

it('should open the modal when the event is dispatched', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Index::class)
        ->call('showUser', $user->id)
        ->assertDispatched('user::show', id: $user->id);
});

test('making sure that the method loadUser has the attribute On', function () {

    $reflection = new ReflectionClass(new Show());

    $attributes = $reflection->getMethod('load')->getAttributes();

    expect($attributes)->toHaveCount(1);

    $attribute = $attributes[0];

    expect($attribute->getName())->toBe('Livewire\Attributes\On')
        ->and($attribute)->getArguments()->toHaveCount(1);

    $argument = $attribute->getArguments()[0];

    expect($argument)->toBe('user::show');
});

test('check if component is in the page', function () {
    actingAs(User::factory()->admin()->create());

    Livewire::test(Index::class)
        ->assertContainsLivewireComponent('admin.users.show');
});
