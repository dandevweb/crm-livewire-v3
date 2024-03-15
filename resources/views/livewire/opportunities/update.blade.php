<x-drawer wire:model="modal" title="Updating Opportunity" class="w-1/3 p-4" separator right>
    <x-form wire:submit='save' id="update-opportunity-form">
        <div class="space-y-2">
            <x-input label="Title" wire:model="form.title" />
            <x-select :options="[
                ['id' => 'open', 'name' => 'Open'],
                ['id' => 'won', 'name' => 'Won'],
                ['id' => 'lost', 'name' => 'Lost'],
            ]" label="Status" wire:model="form.status" />
            <x-input label="Amount" wire:model="form.amount" prefix="R$" locale="pt-BR" money />
        </div>
    </x-form>

    <x-slot:actions>
        <x-button label="Cancel" x-on:click="$wire.modal = false" />
        <x-button label="Save" type="submit" form='update-opportunity-form' />
    </x-slot:actions>
</x-drawer>
