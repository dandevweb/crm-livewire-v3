<?php

use App\Models\User;

use function Pest\Laravel\{get, actingAs};

it('should be able to access the route admin/users', function () {
    actingAs(User::factory()->admin()->create());

    get(route('admin.users'))
        ->assertOk();
});
