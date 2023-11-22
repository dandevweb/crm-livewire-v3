<div>
    <x-card title="Login" shadow class="mx-auto w-[450px]">
        @if ($message = session()->get('status'))
            <x-alert icon="o-exclamation-triangle" class="alert-error mb-4">
                {{ $message }}
            </x-alert>
        @endif

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
            <div class="w-full text-right text-sm">
                <a href="{{ route('password.recovery') }}" class="link-primary link">Forgot
                    your
                    password?</a>
            </div>
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
