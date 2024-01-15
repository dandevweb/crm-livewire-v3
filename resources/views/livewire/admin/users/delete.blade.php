<div>
    <button wire:click="$set('modal', true)">

    </button>

    @if ($modal)
        <p>modal</p>
    @endif
</div>
