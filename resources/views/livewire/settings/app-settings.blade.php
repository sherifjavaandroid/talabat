@section('title', __('Mobile App Settings'))
<div>

    <x-baseview title="{{ __('Mobile App Settings') }}">

        <x-tab.tabview class="shadow pb-10">

            <x-slot name="header">
                <x-tab.header tab="1" title="{{ __('General') }}" />
                <x-tab.header tab="2" title="{{ __('Authentication') }}" />
                <x-tab.header tab="3" title="{{ __('App Layout') }}" />
                <x-tab.header tab="4" title="{{ __('Driver') }}" />
                <x-tab.header tab="5" title="{{ __('Theme') }}" />
                <x-tab.header tab="6" title="{{ __('Order') }}" />
                <x-tab.header tab="7" title="{{ __('Page Setting') }}" />
            </x-slot>

            <x-slot name="body">
                <x-tab.body tab="1">
                    @include('livewire.settings.appsettings.general')
                </x-tab.body>
                <x-tab.body tab="2">
                    @include('livewire.settings.appsettings.auth')
                </x-tab.body>
                <x-tab.body tab="3">
                    @include('livewire.settings.appsettings.layout')
                </x-tab.body>
                <x-tab.body tab="4">
                    @include('livewire.settings.appsettings.driver')
                </x-tab.body>
                <x-tab.body tab="5">
                    @include('livewire.settings.appsettings.theme')
                </x-tab.body>
                <x-tab.body tab="6">
                    @include('livewire.settings.appsettings.order')
                </x-tab.body>
                <x-tab.body tab="7">
                    <livewire:settings.page />
                </x-tab.body>
            </x-slot>

        </x-tab.tabview>
    </x-baseview>

</div>
