<?php

use App\Enum\Can;

use Livewire\Livewire;

use App\Models\{Permission, User};

use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{get, actingAs};

it('should be able to access the route customers', function () {
    actingAs(User::factory()->create());

    get(route('customers'))
        ->assertOk();
})->only();

test("let's create a livewire component to list all customers in the page", function () {
    actingAs(User::factory()->admin()->create());
    $customers = User::factory()->count(10)->create();

    $lw = Livewire::test(Customers\Index::class);

    $lw->assertSet('customers', function ($customers) {
        expect($customers)
        ->toHaveCount(11);

        return true;
    });

    foreach ($customers as $user) {
        $lw->assertSee($user->name);
    }
});

test('check the table format', function () {
    actingAs(User::factory()->admin()->create());

    Livewire::test(Customers\Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by name and email', function () {
    $admin = User::factory()->admin()->create(['name' => 'joe Doe', 'email' => 'admin@gmail.com']);
    $mario = User::factory()->create(['name' => 'Mario', 'email' => 'little_guy@gmail.com']);

    actingAs($admin);

    Livewire::test(Customers\Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })
        ->set('search', 'mar')
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(1)->first()->name->toBe('Mario');

            return true;
        })
        ->set('search', 'guy')
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(1)->first()->name->toBe('Mario');

            return true;
        });

});

it('should be able to filter by permissions', function () {
    $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);

    $mario       = User::factory()->withPermission(Can::TESTING)->create(['name' => 'Mario', 'email'       => 'little_guy@gmail.com']);
    $permission  = Permission::where('key', Can::BE_AN_ADMIN)->first();
    $permission2 = Permission::where('key', Can::TESTING)->first();


    actingAs($admin);

    Livewire::test(Customers\Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        })
        ->set('search_permissions', [$permission->id, $permission2->id])
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2)->first()->name->toBe('Joe Doe');

            return true;
        });

});

it('should be able to list deleted customers', function () {
    $admin            = User::factory()->admin()->create(['name' => 'Joe Doe', 'email'            => 'admin@gmail.com']);
    $deletedCustomers = User::factory()->count(2)->create(['deleted_at' => now()]);

    actingAs($admin);

    Livewire::test(Customers\Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('customers', function ($customers) {
            expect($customers)->toHaveCount(2);

            return true;
        });

});

it('should be able to sort by name', function () {
    $admin = User::factory()->admin()->create(['name' => 'joe Doe', 'email' => 'admin@gmail.com']);
    $mario = User::factory()
        ->withPermission(Can::TESTING)
        ->create(['name' => 'Mario', 'email' => 'little_guy@gmail.com']);

    actingAs($admin);

    Livewire::test(Customers\Index::class)
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'name')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('joe Doe')
                ->and($customers)->last()->name->toBe('Mario');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'name')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('Mario')
                ->and($customers)->last()->name->toBe('joe Doe');

            return true;
        });

});

it('should be able to paginate the result', function () {
    $admin = User::factory()->admin()->create(['name' => 'joe Doe', 'email' => 'admin@gmail.com']);
    User::factory(30)
       ->withPermission(Can::TESTING)
       ->create();

    actingAs($admin);

    Livewire::test(Customers\Index::class)
        ->set('perPage', 20)
        ->assertSet('customers', function (LengthAwarePaginator $customers) {
            expect($customers)
                ->toHaveCount(20);

            return true;
        });

});
