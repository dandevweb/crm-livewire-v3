<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Enum\Can;
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
                $can->value,
                fn (User $user) => $user->hasPermissionTo($can)
            );
        }
    }
}
