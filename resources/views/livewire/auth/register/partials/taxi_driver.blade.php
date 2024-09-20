@if ( $driverType == "taxi")
<hr class="my-4" />
<p class="font-light">{{ __('Vehicle Details') }}</p>
<div class="grid grid-cols-2 gap-4">
    {{-- car make --}}
    <div>
        <livewire:component.autocomplete-input title="{{ __('Car Make') }}" column="name" model="CarMake" emitFunction="autocompleteCategorySelected" />
        <x-input-error message="{{ $errors->first('car_make_id') }}" />
    </div>

    {{-- car model --}}
    <div>
        <livewire:component.autocomplete-input title="{{ __('Car Model') }}" column="name" model="CarModel" updateQueryClauseName="carModelQueryClasueUpdate" :queryClause="$carModelSearchClause" emitFunction="autocompleteAddressSelected" />
        <x-input-error message="{{ $errors->first('car_model_id') }}" />
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <x-input title="{{ __('Registration Number') }}" name="reg_no" />
    <x-input title="{{ __('Color') }}" name="color" />
    {{-- vehicle type --}}
    <x-select title="{{ __('Vehicle Type') }}" :options='$vehicleTypes ?? []' name="vehicle_type_id" :defer="true" />
</div>
@endif
