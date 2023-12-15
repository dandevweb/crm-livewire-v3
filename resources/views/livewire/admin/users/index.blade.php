<div>
    <x-header title="Users" separator />

    <div class="mb-4 flex gap-4">
        <div class="w-1/3">
            <x-input icon="o-magnifying-glass" wire:model.live="search"
                placeholder="Seare by email or name" class="input-sm" />
        </div>

        <x-select>

        </x-select>
    </div>

    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-primary" />
            @endforeach
        @endscope

        @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
        @endscope
    </x-table>
</div>
