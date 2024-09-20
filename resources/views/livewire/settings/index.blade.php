@section('title', __('Settings'))
<div>

    <x-baseview title="{{ __('Settings') }}">

        <x-tab.tabview class="shadow pb-10">

            <x-slot name="header">
                <x-tab.header tab="1" title="{{ __('Backend Settings') }}" />
                <x-tab.header tab="2" title="{{ __('Push notification setting(Firebase)') }}" />
                <x-tab.header tab="3" title="{{ __('General Settings') }}" />
                <x-tab.header tab="4" title="{{ __('Custom Order Notification Messages') }}" />
                <x-tab.header tab="5" title="{{ __('File Upload Limits') }}" />
            </x-slot>

            <x-slot name="body">
                <x-tab.body tab="1">
                    <livewire:settings.backend-settings />
                </x-tab.body>
                <x-tab.body tab="2">
                    <livewire:settings.notification />
                </x-tab.body>
                <x-tab.body tab="3">
                    <livewire:settings.web-app-settings />
                </x-tab.body>
                <x-tab.body tab="4">
                    <livewire:settings.custom-notification-message />
                </x-tab.body>
                <x-tab.body tab="5">
                    <livewire:settings.file-limit />
                </x-tab.body>
            </x-slot>

        </x-tab.tabview>
    </x-baseview>

    @include('layouts.partials.wysisyg')
    <x-loading />
</div>
