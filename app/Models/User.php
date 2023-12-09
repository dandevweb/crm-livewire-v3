<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(string $key): void
    {
        $this->permissions()->firstOrCreate(compact('key'));

        Cache::forget($this->getPermissionCacheKey());
        Cache::rememberForever(
            $this->getPermissionCacheKey(),
            fn () => $this->permissions
        );
    }

    public function hasPermissionTo(string $key): bool
    {
        /** @var Collection $permissions */
        $permissions = Cache::get($this->getPermissionCacheKey(), $this->permissions);

        return $permissions->where('key', '=', $key)->isEmpty();
    }

    private function getPermissionCacheKey(): string
    {
        return "user::{$this->id}::permissions";
    }
}
