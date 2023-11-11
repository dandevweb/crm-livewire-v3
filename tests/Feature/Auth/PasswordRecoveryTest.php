<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('needs to have a route to password recovery', function () {
    get(route('password.recovery'))
        ->assertOk();
});