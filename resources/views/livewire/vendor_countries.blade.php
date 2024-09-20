@section('title', __('Countries'))
<div>

    <x-baseview title="{{ __('Countries') }}" showNew="true">
        <livewire:tables.vendor-country-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('New Operation Country') }}</p>
            <x-select title="{{ __('Country') }}" :options='$countries' name="country_id" :defer="false"
                onchange="countryChanged" />
            <x-checkbox title="{{ __('Active') }}" name="isActive" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Operation Country') }}</p>
            <x-select title="{{ __('Country') }}" :options='$countries' name="country_id" :defer="false" />
            <x-checkbox title="{{ __('Active') }}" name="isActive" />

        </x-modal>
    </div>

</div>
