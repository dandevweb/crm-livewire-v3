<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Dev\Login;

use function Pest\Laravel\{actingAs, get};
use function Pest\Laravel\assertAuthenticatedAs;

it('should be able to list all users of the system', function () {
    User::factory()->count(10)->create();

    $users = User::all();

    Livewire::test(Login::class)
        ->assertSet('users', $users)
        ->assertSee($users->first()->name);
});

it('should be able to login with any user', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('selectedUser', $user->id)
        ->call('login')
        ->assertMethodWired('login')
        ->assertRedirect(route('dashboard'));

    assertAuthenticatedAs($user);
});

it('should not load livewire component on production environment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'production');

    actingAs($user);

    get(route('dashboard'))
        ->assertDontSeeLivewire('dev.login');

    get(route('login'))
        ->assertDontSeeLivewire('dev.login');
});

it('should load livewire component on non production environment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'local');

    actingAs($user);

    get(route('dashboard'))
        ->assertSeeLivewire('dev.login');

    get(route('login'))
        ->assertSeeLivewire('dev.login');
});
