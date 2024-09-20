@section('title', __('States'))
<div>

    <x-baseview title="{{ __('States') }}" showNew="true">
        <livewire:tables.state-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create State') }}</p>
            <x-select title="{{ __('Country') }}" :options='$countries' name="country_id" :defer="false" />
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit State') }}</p>
            <x-select title="{{ __('Country') }}" :options='$countries' name="country_id" :defer="false" />
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />


        </x-modal>
    </div>

</div>
