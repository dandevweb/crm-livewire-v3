<?php

use App\Enum\Can;

use Livewire\Livewire;
use App\Livewire\Admin;

use App\Models\{Permission, User};

use Illuminate\Pagination\LengthAwarePaginator;

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

    $lw->assertSet('items', function ($items) {
        expect($items)
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
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'permissions', 'label' => 'Permissions', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by name and email', function () {
    $admin = User::factory()->admin()->create(['name' => 'joe Doe', 'email' => 'admin@gmail.com']);
    $mario = User::factory()->create(['name' => 'Mario', 'email' => 'little_guy@gmail.com']);

    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('search', 'mar')
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(1)->first()->name->toBe('Mario');

            return true;
        })
        ->set('search', 'guy')
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(1)->first()->name->toBe('Mario');

            return true;
        });

});

it('should be able to filter by permissions', function () {
    $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);

    $mario       = User::factory()->withPermission(Can::TESTING)->create(['name' => 'Mario', 'email'       => 'little_guy@gmail.com']);
    $permission  = Permission::where('key', Can::BE_AN_ADMIN)->first();
    $permission2 = Permission::where('key', Can::TESTING)->first();


    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        })
        ->set('search_permissions', [$permission->id, $permission2->id])
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2)->first()->name->toBe('Joe Doe');

            return true;
        });

});

it('should be able to list deleted users', function () {
    $admin        = User::factory()->admin()->create(['name' => 'Joe Doe', 'email'        => 'admin@gmail.com']);
    $deletedUsers = User::factory()->count(2)->create(['deleted_at' => now()]);

    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('items', function ($items) {
            expect($items)->toHaveCount(2);

            return true;
        });

});

it('should be able to sort by name', function () {
    $admin = User::factory()->admin()->create(['name' => 'joe Doe', 'email' => 'admin@gmail.com']);
    $mario = User::factory()
        ->withPermission(Can::TESTING)
        ->create(['name' => 'Mario', 'email' => 'little_guy@gmail.com']);

    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'name')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('joe Doe')
                ->and($items)->last()->name->toBe('Mario');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'name')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('Mario')
                ->and($items)->last()->name->toBe('joe Doe');

            return true;
        });

});

it('should be able to paginate the result', function () {
    $admin = User::factory()->admin()->create(['name' => 'joe Doe', 'email' => 'admin@gmail.com']);
    User::factory(30)
       ->withPermission(Can::TESTING)
       ->create();

    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->set('perPage', 20)
        ->assertSet('items', function (LengthAwarePaginator $items) {
            expect($items)
                ->toHaveCount(20);

            return true;
        });

});
