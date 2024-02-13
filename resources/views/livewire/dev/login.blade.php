<div class="flex items-center justify-end space-x-2 bg-red-500 p-2">
    <x-select class="select-sm" icon="o-user" :options="$this->users" wire:model="selectedUser"
        placeholder='Select an user' />

    <x-button wire:click="login" class="btn-sm">Login</x-button>
</div>
