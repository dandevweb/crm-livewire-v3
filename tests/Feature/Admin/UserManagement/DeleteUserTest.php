<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin;

use App\Notifications\UserDeletedNotification;

use function Pest\Laravel\{actingAs, assertSoftDeleted, assertNotSoftDeleted};

it('should be able to delete a user', function () {
    $user        = User::factory()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy')
        ->assertDispatched('user::deleted');


    assertSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('should have a confirmation before the deletion', function () {
    $user        = User::factory()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->call('destroy')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('should send a notification to the user telling him that he has no long access to the application', function () {
    Notification::fake();
    $user        = User::factory()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy');

    Notification::assertSentTo($forDeletion, UserDeletedNotification::class);
});
