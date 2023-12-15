<?php

use App\Models\User;

use Livewire\Livewire;
use App\Livewire\Admin;

use function Pest\Laravel\{get, actingAs};

it('should be able to access the route admin/users', function () {
    actingAs(User::factory()->admin()->create());

    get(route('admin.users'))
        ->assertOk();
});

test('making sure that the route is protected by the permission BR_AN_ADMIN', function () {
    actingAs(User::factory()->create());

    get(route('admin.users'))
        ->assertForbidden();
});

test("let's create a livewire component to list all users in the page", function () {
    actingAs(User::factory()->admin()->create());
    $users = User::factory()->count(10)->create();

    $lw = Livewire::test(Admin\Users\Index::class);

    $lw->assertSet('users', function ($users) {
        expect($users)
        ->toHaveCount(11);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }
});

test('check the table format', function () {
    actingAs(User::factory()->admin()->create());

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ]);
});
