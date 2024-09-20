@section('title', __('New Taxi Order'))
<div>

    <div class="w-full grid grid-cols-1 md:grid-cols-5 md:gap-4">
        <div class="col-span-3 rounded bg-white p-4 shadow pb-12">
            <x-baseview title="{{ __('New Taxi Order') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- customer --}}
                    <x-label title="{{ __('User') }}">
                        <livewire:select.new-order-user-select name="user_id" placeholder="{{ __('Select User') }}"
                            :searchable="true" />
                    </x-label>

                    {{-- payment method --}}
                    <x-label title="{{ __('Payment Method') }}">
                        <livewire:select.payment-method-select name="payment_method_id"
                            placeholder="-- {{ __('Select') }} --" />
                    </x-label>

                    {{-- pickup address --}}
                    <x-label title="{{ __('Pickup Address') }}">
                        <livewire:select.address-search-select name="pickup_address" :searchable="true"
                            placeholder="{{ __('Enter address, place etc.') }}" />
                    </x-label>

                    {{-- dropoff address --}}
                    <x-label title="{{ __('Dropoff Address') }}">
                        <livewire:select.address-search-select name="dropoff_address" :searchable="true"
                            placeholder="{{ __('Enter address, place etc.') }}" />
                    </x-label>

                    {{-- vehicle types --}}
                    <x-label title="{{ __('Vehicle Type') }}">
                        <livewire:select.vehicle-type-select name="vehcile_type_id" />
                    </x-label>
                </div>

            </x-baseview>
        </div>
        <div class="col-span-2 rounded bg-white p-4 shadow">
            <p class="font-medium text-2xl">{{ __('Order Summary') }}</p>
            <hr class="my-4" />
            <x-details.item title="{{ __('User') }}" text="{{ $user != null ? $user->name : '' }}" />
            <x-details.item title="{{ __('Pickup Address') }}"
                text="{{ $pickup_address['formatted_address'] ?? '' }}" />
            <x-details.item title="{{ __('Dropoff Address') }}"
                text="{{ $dropoff_address['formatted_address'] ?? '' }}" />
            <x-details.item title="{{ __('Vehicle Type') }}" text="{{ $vehicleType->name ?? '' }}" />
            <hr class="my-4" />
            {{-- amounts --}}
            <div class="font-bold text-lg">
                <div class="flex justify-between">
                    <p class="text-lg font-normal">{{ __('Subtotal') }}</p>
                    <p class="">{{ currencyFormat($amount ?? 0.0) }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-lg font-normal">{{ __('Discount') }}</p>
                    <p class="">- {{ currencyFormat($discount ?? 0.0) }}</p>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between">
                    <p class="text-lg font-normal">{{ __('Total') }}</p>
                    <p class="">{{ currencyFormat($total_amount ?? 0.0) }}</p>
                </div>
                <hr class="my-4" />
                {{-- create order button --}}
                <div class="flex justify-end">
                    <x-buttons.primary wireClick="createOrder">
                        {{ __('Create Order') }}
                    </x-buttons.primary>
                </div>
            </div>
        </div>
    </div>





</div>
