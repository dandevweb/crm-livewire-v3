<?php

use App\Enum\Can;
use App\Models\{Permission, User};
use Illuminate\Support\Facades\{Cache, DB};

use Database\Seeders\{PermissionSeeder, UserSeeder};

use function Pest\Laravel\{assertDatabaseHas, seed, actingAs, get};

it('should be able to give an user a permission to do something', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    expect($user)
        ->hasPermissionTo(Can::BE_AN_ADMIN)
        ->toBeTrue('Checking if the user has the permission to be an admin');

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where(['key' => Can::BE_AN_ADMIN->value])->first()->id,
    ]);
});

test('permission must to have a seeder', function () {
    seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN,
    ]);
});

test('seed with an admin user', function () {
    seed([PermissionSeeder::class, UserSeeder::class]);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->id,
        'permission_id' => Permission::where('key', Can::BE_AN_ADMIN->value)->first()?->id,
    ]);
});

it('should block the access to an admin page if the user does not have the permission to be an admin', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user);

    get(route('admin.dashboard'))
        ->assertForbidden();
});

test("let's make sure that we are using cache to store user permission", function () {
    /** @var User $user */
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    $cacheKey = 'user::' . $user->id . '::permissions';

    expect(Cache::has($cacheKey))->toBeTrue('Checking if cache key exists')
        ->and(Cache::get($cacheKey))->toBe(
            $user->permissions,
            'Checking if permissions are the same as the user'
        );
});

test("let's make sure that we are using the cache the retrieve/check when the user has the given permission", function () {
    /** @var User $user */
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    DB::listen(fn ($query) => throw new Exception('We got a hit'));
    $user->hasPermissionTo(Can::BE_AN_ADMIN);

    expect(true)->toBeTrue();

});
