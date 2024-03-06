<x-drawer wire:model="modal" title="Create Customer" class="w-1/3 p-4" separator right>
    <x-form wire:submit='save' id="create-customer-form">
        <div class="space-y-2">
            <x-input label="Name" wire:model="name" />
            <x-input label="Email" wire:model="email" />
            <x-input label="Phone" wire:model="phone" />
        </div>
    </x-form>

    <x-slot:actions>
        <x-button label="Cancel" x-on:click="$wire.modal = false" />
        <x-button label="Save" type="submit" form='create-customer-form' />
    </x-slot:actions>
</x-drawer>
