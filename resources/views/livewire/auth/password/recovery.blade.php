<x-card title="Password recovery" shadow class="mx-auto w-[450px]">
    @if ($message)
        <x-alert icon="o-home" class="alert-success mb-4">
            <span>You will receive an email with the password recovery link.</span>
        </x-alert>
    @endif
    <x-form wire:submit="startProcessRecovery">
        <x-input label="Email" wire:model="email" />
        <x-slot:actions>
            <div class="flex w-full items-center justify-between">
                <a wire:navigate href="{{ route('login') }}" class="link-primary link">I
                    Never mind, get back to login page.
                </a>
                <div>
                    <x-button label="Submit" class="btn-primary" type="submit" spinner="submit" />
                </div>
            </div>
        </x-slot:actions>
    </x-form>

</x-card>
