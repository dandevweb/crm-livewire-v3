<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Users\{Impersonate, StopImpersonate};

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $user = User::factory()->create();

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));

    assertSame(session()->get('impersonate'), $user->id);
});

it('should mak sure that we are logged with the impersonated user', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect(route('dashboard'));


    get(route('dashboard'))
    ->assertSee(__("You are impersonating :name, click here to stop the impersonation.", ['name' => $user->name]));

    expect(auth()->id())->toBe($user->id);
});

it('should be able to stop impersonation', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect(route('dashboard'));

    Livewire::test(StopImpersonate::class)
        ->call('stop')
        ->assertRedirect(route('admin.users'));

    expect(session()->has('impersonate'))->toBeFalse();

    get(route('dashboard'))
    ->assertDontSee(__("You are impersonating :name, click here to stop the impersonation.", ['name' => $user->name]));

    expect(auth()->id())->toBe($admin->id);
});