<div class="grid h-full grid-cols-3 gap-4 p-2" wire:sortable-group="updateOpportunities">
    <x-board.group status="open" :items="$this->opens" />
    <x-board.group status="won" :items="$this->wons" />
    <x-board.group status="lost" :items="$this->losts" />
</div>
