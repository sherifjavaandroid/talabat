@section('title', __('Car Makes') )
<div>

    <x-baseview title="{{ __('Car Makes') }}">
        <livewire:tables.taxi.car-make-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('New') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Car Make') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
        </x-modal>
    </div>
    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('New Car Make') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />

        </x-modal>
    </div>


</div>
