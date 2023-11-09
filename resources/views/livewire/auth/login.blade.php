<div>
    <x-card title="Login" shadow class="mx-auto w-[450px]">
        @if ($errors->hasAny(['invalidCredentials', 'rateLimiter']))
            <x-alert icon="o-home" class="alert-warning mb-4">
                @error('invalidCredentials')
                    <div>
                        {{ $message }}
                    </div>
                @enderror

                @error('rateLimiter')
                    <div>
                        {{ $message }}
                    </div>
                @enderror
            </x-alert>
        @endif

        <x-form wire:submit="tryToLogin">
            <x-input label="Email" wire:model="email" />
            <x-input label="Password" wire:model="password" type="password" />
            <x-slot:actions>
                <div class="flex w-full items-center justify-between">
                    <a wire:navigate href="{{ route('auth.register') }}" class="link-primary link">I
                        want to
                        create an
                        account</a>
                    <div>
                        <x-button label="Login" class="btn-primary" type="submit"
                            spinner="submit" />
                    </div>
                </div>
            </x-slot:actions>
        </x-form>

    </x-card>


</div>
