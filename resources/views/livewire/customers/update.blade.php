<x-drawer wire:model="modal" title="Updating Customer" class="w-1/3 p-4" separator right>
    <x-form wire:submit='save' id="update-customer-form">
        <div class="space-y-2">
            <x-input label="Name" wire:model="form.name" />
            <x-input label="Email" wire:model="form.email" />
            <x-input label="Phone" wire:model="form.phone" />
        </div>
    </x-form>

    <x-slot:actions>
        <x-button label="Cancel" x-on:click="$wire.modal = false" />
        <x-button label="Save" type="submit" form='update-customer-form' />
    </x-slot:actions>
</x-drawer>