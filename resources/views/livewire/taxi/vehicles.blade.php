@section('title', __('Vehicles'))
<div>

    <x-baseview title="{{ __('Vehicles') }}" :showNew="true">
        <livewire:tables.taxi.vehicle-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('New') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Vehicle') }}</p>
            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Registration Number') }}" name="reg_no" />
                <x-input title="{{ __('Color') }}" name="color" />
                {{-- vehicle type --}}
                <x-select title="{{ __('Vehicle Type') }}" :options='$vehicleTypes' name="vehicle_type_id" :defer="true" />
                {{-- driver --}}
                <div>
                    <livewire:component.autocomplete-input title="{{ __('Driver') }}"
                        placeholder="{{ __('Search for driver') }}" column="name" model="User" customQuery="driver"
                        emitFunction="autocompleteDriverSelected" />
                    <x-input-error message="{{ $errors->first('driver_id') }}" />
                </div>

                {{-- car make --}}
                <div>
                    <livewire:component.autocomplete-input title="{{ __('Car Make') }}" column="name" model="CarMake"
                        emitFunction="autocompleteCategorySelected" />
                    <x-input-error message="{{ $errors->first('car_model_id') }}" />
                </div>

                {{-- car model --}}
                <div>
                    <livewire:component.autocomplete-input title="{{ __('Car Model') }}" column="name"
                        model="CarModel" updateQueryClauseName="carModelQueryClasueUpdate" :queryClause="$carModelSearchClause"
                        emitFunction="autocompleteAddressSelected" />
                    <x-input-error message="{{ $errors->first('car_model_id') }}" />
                </div>
            </div>
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
            <hr class="my-4" />
            <p class="font-medium text">{{ __('Documents') }}</p>
            <livewire:component.multiple-media-upload title="{{ __('Image') }}" name="photos"
                max="{{ setting('page.settings.driverDocumentCount', 3) }}" types="PNG or JPEG" fileTypes="image/*"
                emitFunction="photoSelected" />

        </x-modal-lg>
    </div>
    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Update Vehicle') }}</p>
            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Registration Number') }}" name="reg_no" />
                <x-input title="{{ __('Color') }}" name="color" />
                {{-- vehicle type --}}
                <x-select title="{{ __('Vehicle Type') }}" :options='$vehicleTypes' name="vehicle_type_id"
                    :defer="true" />
                {{-- driver --}}
                <div>
                    <livewire:component.autocomplete-input title="{{ __('Driver') }}"
                        placeholder="{{ __('Search for driver') }}" column="name" model="User" customQuery="driver"
                        initialEmit="preselectedDeliveryBoyEmit" emitFunction="autocompleteProductSelected" />
                    <x-input-error message="{{ $errors->first('driver_id') }}" />
                </div>

                {{-- car make --}}
                <div>
                    <livewire:component.autocomplete-input title="{{ __('Car Make') }}" column="name" model="CarMake"
                        initialEmit="preselectedCarMakeEmit" emitFunction="autocompleteCategorySelected" />
                    <x-input-error message="{{ $errors->first('car_model_id') }}" />
                </div>

                {{-- car model --}}
                <div>
                    <livewire:component.autocomplete-input title="{{ __('Car Model') }}" column="name"
                        model="CarModel" updateQueryClauseName="carModelQueryClasueUpdate" :queryClause="$carModelSearchClause"
                        initialEmit="preselectedCarModelEmit" emitFunction="autocompleteAddressSelected" />
                    <x-input-error message="{{ $errors->first('car_model_id') }}" />
                </div>
            </div>
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
            <hr class="my-4" />
            <p class="font-medium text">{{ __('Documents') }}</p>
            <livewire:component.multiple-media-upload title="{{ __('Image') }}" name="photos" types="PNG or JPEG"
                fileTypes="image/*" emitFunction="photoSelected"
                max="{{ setting('page.settings.driverDocumentCount', 3) }}" previewsEmit="vehiclePreviewsListener" />

        </x-modal-lg>
    </div>

    {{-- details form --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>

            <p class="text-xl font-semibold">{{ __('Vehicle Details') }}</p>
            <div class="grid grid-cols-2 gap-4">
                <x-details.item title="{{ __('Registration Number') }}" text="{{ $selectedModel->reg_no ?? '' }}" />
                <x-details.item title="{{ __('Color') }}" text="{{ $selectedModel->color ?? '' }}" />
                <x-details.item title="{{ __('Vehicle Type') }}"
                    text="{{ $selectedModel->vehicle_type->name ?? '' }}" />
                <x-details.item title="{{ __('Driver') }}" text="{{ $selectedModel->driver->name ?? '' }}" />
                <x-details.item title="{{ __('Car Make') }}"
                    text="{{ $selectedModel->car_model->car_make->name ?? '' }}" />
                <x-details.item title="{{ __('Car Model') }}" text="{{ $selectedModel->car_model->name ?? '' }}" />
                <div>
                    <x-label title="{{ __('Status') }}" />
                    <x-table.active :model="$selectedModel" />
                </div>

                <div>
                    <x-label title="{{ __('Verified') }}" />
                    <x-table.active active="{{ $selectedModel->verified ?? false }}" />
                </div>
            </div>
            <hr class="my-4" />
            <x-details.item title="{{ __('Documents') }}" text="">
                <div class="flex flex-wrap space-x-3 space-y-3">
                    @foreach ($selectedModel->photos ?? [] as $photo)
                        <a href="{{ $photo }}" target="_blank"><img src="{{ $photo }}"
                                class="w-24 h-24 mx-2 rounded-sm" /></a>
                    @endforeach
                </div>
            </x-details.item>

            @if ($selectedModel != null && (!$selectedModel->verified ?? false))
                <x-buttons.primary title="{{ __('Verify Vehicle') }}" wireClick="verifyVehicle" />
            @endif


        </x-modal>
    </div>
</div>
