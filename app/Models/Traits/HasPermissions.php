<?php

namespace App\Models\Traits;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
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

        return $permissions
            ->where('key', '=', $key)
            ->isNotEmpty();
    }

    private function getPermissionCacheKey(): string
    {
        return "user::{$this->id}::permissions";
    }
}
