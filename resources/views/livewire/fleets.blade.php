@section('title', __('Fleets') )
<div>

    <x-baseview title="{{ __('Fleets') }}" :showNew="true">
        <livewire:tables.fleet-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('New Fleet') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
            <x-input title="{{ __('Phone') }}" name="phone" placeholder="" />
            <x-input title="{{ __('Address') }}" name="address" placeholder="" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Fleet') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
            <x-input title="{{ __('Phone') }}" name="phone" placeholder="" />
            <x-input title="{{ __('Address') }}" name="address" placeholder="" />

        </x-modal>
    </div>

    {{-- assign form --}}
    <div x-data="{ open: @entangle('showAssign') }">
        <x-modal confirmText="{{ __('Assign') }}" action="assignManagers" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Assign Manager To Fleet') }}</p>
            <x-select2 title="{{ __('Fleet Managers') }}" :options="$fleetManagers" name="managersIds" id="managersSelect2" :multiple="true" width="100" :ignore="true" />

        </x-modal>
    </div>

</div>
