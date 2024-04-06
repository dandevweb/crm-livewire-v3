<div class="grid h-full grid-cols-3 gap-4 p-2">

    @foreach ($this->opportunities->groupBy('status') as $status => $items)
        <div class="rounded-md bg-base-200 p-2">
            <x-header :title="$status" subtitle="Total {{ $items->count() }} opportunities"
                size="pb-0 mb-2" separator progress-indicator />
            <div class="space-y-2 p-2">
                @foreach ($items as $item)
                    <x-card>{{ $item->title }}</x-card>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
