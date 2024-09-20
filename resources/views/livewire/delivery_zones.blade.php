@section('title', __('Delivery Zones'))
<div>


    <x-baseview title="{{ __('Delivery Zones') }}" ::showNew="inProduction()">
        <livewire:tables.delivery-zone-table />
    </x-baseview>


    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Delivery Zone') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Delivery Fee') }}" name="delivery_fee"
                hint="{{ __('Leave empty if you do not want to set delivery fee') }}" />
            <div wire:ignore id="map" class="my-4 h-72"></div>
            <x-textarea title="{{ __('Coordinates') }}" name="coordinates" placeholder="" disable="true" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
        </x-modal-lg>
    </div>

    {{-- edit form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Update Delivery Zone') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Delivery Fee') }}" name="delivery_fee"
                hint="{{ __('Leave empty if you do not want to set delivery fee') }}" />
            <div wire:ignore id="editMap" class="my-4 h-72"></div>
            <x-textarea title="{{ __('Coordinates') }}" name="coordinates" placeholder="" disable="true" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
        </x-modal-lg>
    </div>


</div>


@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('googleMapKey', '') }}&callback=initMap&libraries=drawing&v=weekly"
        async></script>
    <script src="{{ asset('js/delivery-zone.js') }}"></script>
@endpush
