<?php

namespace Database\Factories;

use App\Enum\Can;
use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\Factory\HasDeleted;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use HasDeleted;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withPermission(Can $key): static
    {
        return $this->afterCreating(
            fn ($user) => $user->givePermissionTo($key)
        );
    }

    public function admin(): static
    {
        return $this->afterCreating(fn (User $user) => $user->givePermissionTo(Can::BE_AN_ADMIN));
    }

    public function withValidationCode(): static
    {
        return $this->state(fn () => [
            'validation_code'   => random_int(100000, 999999),
            'email_verified_at' => null,
        ]);
    }

}
