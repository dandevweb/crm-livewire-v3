<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin;

use App\Notifications\UserRestoreAccessNotification;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('should be able to restore a user', function () {
    $user           = User::factory()->create();
    $forRestoration = User::factory()->deleted()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Restore::class)
        ->set('user', $forRestoration)
        ->set('confirmation_confirmation', 'YODA')
        ->call('restore')
        ->assertDispatched('user::restored');


    assertNotSoftDeleted('users', ['id' => $forRestoration->id]);
    $forRestoration->refresh();

    expect($forRestoration)->restored_at->not->toBeNull()
        ->restoredBy->id->toBe($user->id);
});

it('should have a confirmation before the restoration', function () {
    $user           = User::factory()->create();
    $forRestoration = User::factory()->deleted()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Restore::class)
        ->set('user', $forRestoration)
        ->call('restore')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::restored');

    assertSoftDeleted('users', ['id' => $forRestoration->id]);
});

it('should send a notification to the user telling him that he has no long access to the application', function () {
    Notification::fake();
    $user           = User::factory()->create();
    $forRestoration = User::factory()->deleted()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Restore::class)
        ->set('user', $forRestoration)
        ->set('confirmation_confirmation', 'YODA')
        ->call('restore');

    Notification::assertSentTo($forRestoration, UserRestoreAccessNotification::class);
});
