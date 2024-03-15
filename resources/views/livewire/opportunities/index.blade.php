<div>

    <x-header title="Opportunities" separator />

    <div class="mb-4 flex items-end justify-between">
        <div class="flex w-full gap-4">
            <div class="w-1/3">
                <x-input label="Search by title" icon="o-magnifying-glass" wire:model.live="search" />
            </div>

            <x-select wire:model.live="perPage" :options="[
                ['id' => 5, 'name' => 5],
                ['id' => 15, 'name' => 15],
                ['id' => 25, 'name' => 25],
                ['id' => 50, 'name' => 50],
            ]" label="Records Per Page" />

            <x-checkbox label="Show Archived Opportunities" wire:model.live="search_trash"
                class="checkbox-primary" right tight />
        </div>

        <x-button x-on:click="$dispatch('opportunity::create')" label="New Opportunity"
            icon="o-plus" />
    </div>

    <x-table :headers="$this->headers" :rows="$this->items">
        @scope('header_id', $header)
            <x-table.th :$header name="id" />
        @endscope

        @scope('header_title', $header)
            <x-table.th :$header name="title" />
        @endscope

        @scope('header_status', $header)
            <x-table.th :$header name="status" />
        @endscope

        @scope('header_amount', $header)
            <x-table.th :$header name="amount" />
        @endscope

        @scope('cell_status', $item)
            <x-badge :value="$item->status" @class([
                'badge-outline badge-sm',
                'badge-error' => $item->status === 'lost',
                'badge-success' => $item->status === 'won',
                'badge-info' => $item->status === 'open',
            ]) />
        @endscope

        @scope('cell_amount', $item)
            R$ {{ number_format($item->amount, 2, ',', '.') }}
        @endscope

        @scope('actions', $opportunity)
            <div class="flex items-center">
                <x-button id="update-btn-{{ $opportunity->id }}"
                    wire:key="update-btn-{{ $opportunity->id }}" icon="o-pencil"
                    x-on:click="$dispatch('opportunity::update', { id: {{ $opportunity->id }} })"
                    spinner class="btn-sm" />

                @unless ($opportunity->trashed())
                    <x-button id="archive-btn-{{ $opportunity->id }}"
                        wire:key="archive-btn-{{ $opportunity->id }}" icon="o-trash"
                        x-on:click="$dispatch('opportunity::archive', { id: {{ $opportunity->id }} })"
                        spinner class="btn-sm" />
                </div>
            @else
                <div class="flex items-center">

                    <x-button id="restore-btn-{{ $opportunity->id }}"
                        wire:key="restore-btn-{{ $opportunity->id }}" icon="o-arrow-uturn-left"
                        x-on:click="$dispatch('opportunity::restore', { id: {{ $opportunity->id }} })"
                        spinner class="btn-sm" />
                </div>
            @endunless
        @endscope

    </x-table>

    {{ $this->items->links(data: ['scrollTo' => false]) }}

    <livewire:opportunities.create />
    <livewire:opportunities.update />
    <livewire:opportunities.archive />
    <livewire:opportunities.restore />

</div>
