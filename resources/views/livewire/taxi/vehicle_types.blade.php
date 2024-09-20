@section('title', __('Vehicle Types') )
<div>

    <x-baseview title="{{ __('Vehicle Types') }}" :showNew="true">
        <livewire:tables.taxi.vehicle-type-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('New') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Vehicle Type') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Base Fare') }}" name="base_fare" />
            <x-input title="{{ __('Distance Fare') }}(/km)" name="distance_fare" />
            <x-input title="{{ __('Fare Per Minutes') }}" name="time_fare" />
            <x-input title="{{ __('Minimum Fare') }}" name="min_fare" />
            <x-media-upload title="{{ __('Logo') }}" name="photo"
                preview="{{ $selectedModel->logo ?? '' }}" :photo="$photo"
                :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" /> 
        </x-modal>
    </div>
    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Update Vehicle Type') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Base Fare') }}" name="base_fare" />
            <x-input title="{{ __('Distance Fare') }}(/km)" name="distance_fare" />
            <x-input title="{{ __('Fare Per Minutes') }}" name="time_fare" />
            <x-input title="{{ __('Minimum Fare') }}" name="min_fare" />
            <x-media-upload title="{{ __('Logo') }}" name="photo"
                preview="{{ $selectedModel->photo ?? '' }}" :photo="$photo"
                :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" /> 

        </x-modal>
    </div>


</div>
