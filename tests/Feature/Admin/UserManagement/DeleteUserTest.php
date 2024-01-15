<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin;

use function Pest\Laravel\{actingAs, assertSoftDeleted, assertNotSoftDeleted};

it('should be able to delete a user', function () {
    $user        = User::factory()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy')
        ->assertDispatched('user::deleted');


    assertSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('should have a confirmation before the deletion', function () {
    $user        = User::factory()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $forDeletion->id]);

});
