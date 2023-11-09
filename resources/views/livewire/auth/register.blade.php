<x-card shadow class="mx-auto w-[450px]">
    <x-form wire:submit="submit">
        <x-input label="Name" wire:model="name" />
        <x-input label="Email" wire:model="email" />
        <x-input label="Confirm your email" wire:model="email_confirmation" />
        <x-input label="Password" wire:model="password" type="password" />
        <x-slot:actions>
            <div class="flex w-full items-center justify-between">
                <a wire:navigate href="{{ route('login') }}" class="link-primary link">
                    I already have an account</a>
                <div>
                    <x-button label="Reset" type="reset" />
                    <x-button label="Login" class="btn-primary" type="submit" spinner="submit" />
                </div>
            </div>
        </x-slot:actions>
    </x-form>

</x-card>
