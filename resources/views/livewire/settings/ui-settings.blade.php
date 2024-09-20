@section('title', __('UI Settings'))
<div>

    <x-baseview title="{{ __('UI Settings') }}">

        <x-tab.tabview class="shadow pb-10">

            <x-slot name="header">
                <x-tab.header tab="1" title="{{ __('Home Screen') }}" />
                <x-tab.header tab="2" title="{{ __('Category') }}" />
                <x-tab.header tab="3" title="{{ __('Currency') }}" />
                <x-tab.header tab="4" title="{{ __('Misc.') }}" />
            </x-slot>

            <x-slot name="body">
                <x-tab.body tab="1">
                    @include('livewire.settings.ui.home')
                </x-tab.body>
                <x-tab.body tab="2">
                    @include('livewire.settings.ui.category')
                </x-tab.body>
                <x-tab.body tab="3">
                    @include('livewire.settings.ui.currency')
                </x-tab.body>
                <x-tab.body tab="4">
                    @include('livewire.settings.ui.misc')
                </x-tab.body>
            </x-slot>

        </x-tab.tabview>
    </x-baseview>

</div>
