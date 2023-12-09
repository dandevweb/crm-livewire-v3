<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Enums\Can;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        foreach (Can::cases() as $can) {
            Gate::define(
                str($can->value)->snake('-')->toString(),
                fn (User $user) => $user->hasPermissionTo($can)
            );
        }
    }
}
