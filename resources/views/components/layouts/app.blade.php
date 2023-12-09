@use('App\Enums\Can')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased">
    <x-main full-width>
        <x-slot:sidebar drawer="main-drawer" collapsible class="pt-3 text-white bg-sky-800">

            {{-- Hidden when collapsed --}}
            <div class="ml-5 text-4xl font-black text-yellow-500 hidden-when-collapsed">mary</div>

            {{-- Display when collapsed --}}
            <div class="ml-5 text-4xl font-black text-orange-500 display-when-collapsed">m</div>

            {{-- Custom `active menu item background color` --}}
            <x-menu activate-by-route active-bg-color="bg-base-300/10">

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-list-item :item="$user" sub-value="username" no-separator no-hover
                        class="!-mx-2 mb-5 mt-2 border-y border-y-sky-900">
                        <x-slot:actions>

                            <livewire:auth.logout />
                        </x-slot:actions>
                    </x-list-item>
                @endif

                <x-menu-item title="Home" icon="o-home" link="/" />
                <x-menu-item title="Yeah" icon="o-sparkles" link="####" />

                <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                    <x-menu-item title="Wifi" icon="o-wifi" />
                    <x-menu-item title="Archives" icon="o-archive-box" />
                </x-menu-sub>

                @can(Can::BE_AN_ADMIN->value)
                    <x-menu-sub title="Admin" icon="o-lock-closed">
                        <x-menu-item title="Dashboard" icon="o-chart-bar-square" :link="route('admin.dashboard')" />
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
