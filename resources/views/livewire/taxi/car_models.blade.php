@section('title', __('Car Models') )
<div>

    <x-baseview title="{{ __('Car Models') }}">
        <livewire:tables.taxi.car-model-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('New') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Car Make') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            {{-- car make --}}
            <livewire:component.autocomplete-input 
                title="{{ __('Car Make') }}" 
                column="name"
                model="CarMake" 
                emitFunction="autocompleteVendorSelected"
                onclearCalled="clearAutocompleteFieldsEvent"
            />
        </x-modal>
    </div>
    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('New Car Make') }}</p>
            <x-input title="{{ __('Name') }}" name="name" />
            {{-- vendors --}}
            <livewire:component.autocomplete-input 
                title="{{ __('Car Make') }}"
                column="name"
                model="CarMake"
                initialEmit="preselectedVendorEmit"
                emitFunction="autocompleteVendorSelected"
                onclearCalled="clearAutocompleteFieldsEvent"
            />

        </x-modal>
    </div>


</div>
