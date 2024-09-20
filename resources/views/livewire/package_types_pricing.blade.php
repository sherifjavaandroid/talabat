@section('title', __('Pricing'))
<div>

    <x-baseview title="{{ __('Pricing') }}" showNew="true">
        <livewire:tables.package-type-price-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Package Type Pricing') }}</p>
            <x-select title="{{ __('Package Type') }}" :options='$packageTypes' name="package_type_id" :defer="true" />
            <x-input title="{{ __('Max Booking Days (7)') }}" name="max_booking_days"
                placeholder="{{ __('Max number of days user can book ahead') }}" />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-input title="{{ __('Base Delivery Price') }}" name="base_price" placeholder="" />
                <x-input title="{{ __('Multiple Stop Fee') }}" name="multiple_stop_fee" placeholder="" />
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <x-input title="{{ __('Price for Package') }}" name="size_price" placeholder="" />
                    <x-checkbox title="{{ __('Charge Per KG') }}" name="price_per_kg" :defer="true" />
                </div>
                <div>
                    <x-input title="{{ __('Distance Price') }}" name="distance_price" placeholder="" />
                    <x-checkbox title="{{ __('Charge Per KM') }}" name="price_per_km" :defer="true" />
                </div>

                <x-checkbox title="{{ __('Auto move to ready') }}" name="auto_assignment" :defer="true" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="true" />
            </div>


        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Package Type') }}</p>
            <x-select title="{{ __('Package Type') }}" :options='$packageTypes' name="package_type_id" />
            <x-input title="{{ __('Max Booking Days (7)') }}" name="max_booking_days"
                placeholder="{{ __('Max number of days user can book ahead') }}" />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-input title="{{ __('Base Delivery Price') }}" name="base_price" placeholder="" />
                <x-input title="{{ __('Multiple Stop Fee') }}" name="multiple_stop_fee" placeholder="" />
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <x-input title="{{ __('Price for Package') }}" name="size_price" placeholder="" />
                    <x-checkbox title="{{ __('Charge Per KG') }}" name="price_per_kg" :defer="true" />
                </div>
                <div>
                    <x-input title="{{ __('Distance Price') }}" name="distance_price" placeholder="" />
                    <x-checkbox title="{{ __('Charge Per KM') }}" name="price_per_km" :defer="true" />
                </div>
                <x-checkbox title="{{ __('Auto move to ready') }}" name="auto_assignment" :defer="true" />
                <x-checkbox title="{{ __('Extra fields required (e.g kg, width)') }}" name="field_required"
                    :defer="true" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="true" />
            </div>


        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal confirmText="{{ __('Update') }}" action="">

            <p class="text-xl font-semibold">{{ __('Package Type Pricing') }}</p>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <x-label title="{{ __('Package Type') }}" />
                    {{ $selectedModel->package_type->name ?? '' }}
                </div>
                <div>
                    <x-label title="{{ __('Max Booking Days (7)') }}" />
                    {{ $selectedModel->max_booking_days ?? '' }}
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <x-label title="{{ __('Base Delivery Price') }}" />
                    {{ setting('currency') }}
                    {{ $selectedModel->base_price ?? '' }}
                </div>
                <div>
                    <x-label title="{{ __('Multiple Stop Fee') }}" />
                    {{ setting('currency') }}
                    {{ $selectedModel->multiple_stop_fee ?? '' }}
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <x-label title="{{ __('Price for Package') }}" />
                    {{ $selectedModel->size_price ?? '' }}
                </div>
                <div>
                    <x-label title="{{ __('Charge Per KG') }}" />
                    {{ $selectedModel->price_per_kg ?? '' ? 'Yes' : 'No' }}
                </div>
                <div>
                    <x-label title="{{ __('Distance Price') }}" />
                    {{ setting('currency') }}
                    {{ $selectedModel->distance_price ?? '' }}
                </div>
                <div>
                    <x-label title="{{ __('Charge Per KM') }}" />
                    {{ $selectedModel->price_per_km ?? '' ? 'Yes' : 'No' }}
                </div>
                <div>
                    <x-label title="{{ __('Auto move to ready') }}" />
                    {{ $selectedModel->auto_assignment ?? '' ? 'Yes' : 'No' }}
                </div>
                <div>
                    <x-label title="{{ __('Extra fields required (e.g kg, width)') }}" />
                    {{ $selectedModel->field_required ?? '' ? 'Yes' : 'No' }}
                </div>
                <div>
                    <x-label title="{{ __('Active') }}" />
                    {{ $selectedModel->is_active ?? '' ? 'Yes' : 'No' }}
                </div>
            </div>

        </x-modal>
    </div>
</div>
