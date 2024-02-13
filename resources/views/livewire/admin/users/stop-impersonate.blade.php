<div class="bg-yellow-300 px-4 py-1 text-sm text-yellow-900 hover:cursor-pointer hover:underline"
    wire:click='stop'>
    {{ __('You are impersonating :name, click here to stop the impersonation.', ['name' => user()->name]) }}
</div>
