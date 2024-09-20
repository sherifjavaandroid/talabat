<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="{{ setting('localeCode', 'en') }}"
    dir="{{ isRTL() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ setting('favicon') }}" />
    <title>@yield('title', '') - {{ setting('websiteName', env('APP_NAME')) }}</title>
    @include('layouts.partials.styles')
    @yield('styles')
    @stack('styles')
</head>

<body>
    <div class="flex h-screen bg-gray-50" :class="{ 'overflow-hidden': isSideMenuOpen }">

        <!-- Desktop sidebar -->
        @include('layouts.partials.nav.desktop')

        <!-- Mobile sidebar -->
        @include('layouts.partials.nav.mobile')

        <div class="flex flex-col flex-1 w-full">

            {{-- header --}}
            @livewire('header.profile')



            <main class="h-full overflow-y-auto">
                <div class="container grid px-6 py-5 mx-auto">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>

            {{-- subscription --}}
            @livewire('header.subscription')
        </div>
    </div>

    @include('layouts.partials.scripts')
    @stack('scripts')
</body>

</html>
