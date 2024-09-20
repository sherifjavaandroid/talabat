@section('title', __('Cities'))
<div>

    <x-baseview title="{{ __('Cities') }}" showNew="true">
        <livewire:tables.city-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create City') }}</p>
            <x-select title="{{ __('Country') }}" :options='$countries' name="country_id" :defer="false" />
            <x-select title="{{ __('State') }}" :options='$states' name="state_id" :defer="false" />
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit City') }}</p>
            <x-select title="{{ __('Country') }}" :options='$countries' name="country_id" :defer="false" />
            <x-select title="{{ __('State') }}" :options='$states' name="state_id" :defer="false" />
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />


        </x-modal>
    </div>

</div>
