@use('App\Enum\Can')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased">
    <x-toast />
    @if (session('impersonate'))
        <livewire:admin.users.stop-impersonate />
    @endif

    @if (!app()->environment('production'))
        <x-devbar />
    @endif
    <x-main full-width>

        {{-- The `$slot` goes here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-sky-800 pt-3 text-white">

            {{-- Hidden when collapsed --}}
            <div class="hidden-when-collapsed ml-5 text-4xl font-black text-yellow-500">mary</div>

            {{-- Display when collapsed --}}
            <div class="display-when-collapsed ml-5 text-4xl font-black text-orange-500">m</div>

            {{-- Custom `active menu item background color` --}}
            <x-menu activate-by-route active-bg-color="bg-base-300/10">

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-list-item :item="$user" sub-value="username" no-separator no-hover
                        class="!-mx-2 mb-5 mt-2 border-y border-y-sky-900">
                        <x-slot:actions>
                            <div class="tooltip tooltip-left" data-tip="logoff">
                                <livewire:auth.logout wire:key='logout' />
                            </div>
                        </x-slot:actions>
                    </x-list-item>
                @endif

                <x-menu-item title="Home" icon="o-home" :link="route('dashboard')" />
                <x-menu-item title="Customers" icon="o-building-storefront" :link="route('customers')" />
                <x-menu-item title="Opportunities" icon="o-currency-dollar" :link="route('opportunities')" />

                @can(Can::BE_AN_ADMIN->value)
                    <x-menu-sub title="Admin" icon="o-lock-closed">
                        <x-menu-item title="Dashboard" icon="o-chart-bar-square" :link="route('admin.dashboard')" />
                        <x-menu-item title="Users" icon="o-users" :link="route('admin.users')" />
                    </x-menu-sub>
                @endcan
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>

            {{ $slot }}
        </x-slot:content>
    </x-main>
</body>

</html>
