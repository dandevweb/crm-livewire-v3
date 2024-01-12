<div>
    <x-header title="Users" separator />

    <div class="mb-4 flex gap-4">
        <div class="w-1/3">
            <x-input label="Search" icon="o-magnifying-glass" wire:model.live="search"
                placeholder="Seare by email or name" class="input-sm h-12" />
        </div>

        <div class="w-1/3">
            <x-choices label="Filter by permission" option-label="key"
                wire:model.live="search_permissions" :options="$this->permissions" />
        </div>

        <div class="mt-8 w-1/3">
            <x-checkbox label="Only Deleted Users" wire:model.live="search_trash"
                class="checkbox-primary" right tight />
        </div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('header_id', $header)
            <x-table.th :$header name="id" />
        @endscope

        @scope('header_name', $header)
            <x-table.th :$header name="name" />
        @endscope

        @scope('header_email', $header)
            <x-table.th :$header name="email" />
        @endscope

        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-primary" />
            @endforeach
        @endscope

        @scope('actions', $user)
            @unless ($user->trashed())
                <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
            @else
                <x-button icon="o-arrow-path" wire:click="restore({{ $user->id }})" spinner
                    class="btn-success btn-ghost btn-sm" />
            @endunless
        @endscope
    </x-table>
</div>
