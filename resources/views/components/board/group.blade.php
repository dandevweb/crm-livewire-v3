@props(['status', 'items'])

<div class="rounded-md bg-base-200 p-2" wire:key='group-{{ $status }}'>
    <x-header :title="$status" subtitle="Total {{ $items->count() }} opportunities" size="pb-0 mb-2"
        separator progress-indicator />
    <div class="space-y-2 p-2" wire:sortable-group.item-group="{{ $status }}"
        wire:sortable-group.options="{ animation: 100 }">
        @forelse ($items as $item)
            <x-card class="hover:oppacity-60 cursor-grab" wire:sortable-group.handle
                wire:sortable-group.item="{{ $item->id }}"
                wire:key="opportunity-{{ $item->id }}">{{ $item->title }}</x-card>
        @empty
            <div wire:key='opportunity-null'
                class="rounded-2 flex h-10 w-full items-center justify-center border border-dashed border-gray-400 opacity-20 shadow">
                Empty list
            </div>
        @endforelse
    </div>
</div>
