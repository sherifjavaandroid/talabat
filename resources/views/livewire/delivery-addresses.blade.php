@section('title',  __('Delivery Address') )
<div>

    <x-baseview title="{{ __('Delivery Address') }}" :showNew="true">
        <livewire:tables.delivery-address-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('New Delivery Address') }}</p>
            <x-autocomplete-input title="{{ __('User') }}" name="user" :dataList="$users ?? []" emitFunction="autocompleteUserSelected" />
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Description') }}" name="description" placeholder="" />
            <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address" address="{{ $address ?? '' }}" />
            <div class="grid grid-cols-2 space-x-4">
                <x-input title="{{ __('Latitude') }}" name="latitude" :disable="true" />
                <x-input title="{{ __('Longitude') }}" name="longitude" :disable="true" />
            </div>
        </x-modal>
    </div>

     {{--  update form  --}}
     <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Update Delivery Address') }}</p>            
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Description') }}" name="description" placeholder="" />
            <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address" address="{{ $address ?? 'Hello' }}" />
            <div class="grid grid-cols-2 space-x-4">
                <x-input title="{{ __('Latitude') }}" name="latitude" :disable="true" />
                <x-input title="{{ __('Longitude') }}" name="longitude" :disable="true" />
            </div>

        </x-modal>
    </div>

</div>

