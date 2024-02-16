<div class="flex items-center space-x-2">
    <x-select class="select-sm" icon="o-user" :options="$this->users" wire:model="selectedUser"
        placeholder='Select an user' />

    <x-button wire:click="login" class="btn-sm">Login</x-button>
</div>
