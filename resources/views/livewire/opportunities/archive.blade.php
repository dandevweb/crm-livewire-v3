<x-modal wire:model="modal" title="Archive Confirmation"
    subtitle="You are archiving the opportunity {{ $opportunity?->name }}">

    <x-slot:actions>
        <x-button label="Hum... no" x-on:click="$wire.modal=false" />
        <x-button label="Yes, I am" class="btn-primary" wire:click="archive" />
    </x-slot:actions>
</x-modal>
