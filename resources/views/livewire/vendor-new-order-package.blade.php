@section('title', __('New Package Order'))
<div wire:init="fetchPackageTypes">


    <x-baseview title="{{ __('New Package Order') }}">
        <div class="shadow pb-4 px-2 bg-white rounded">
            <x-stepper.main steps="6" :currentStep="$currentStep">
                <x-slot name="header">
                    <x-stepper.header step="1" title="{!! __('Customer & Package Type') !!}" />
                    <x-stepper.header step="2" title="{{ __('Delivery Info') }}" />
                    <x-stepper.header step="3" title="{{ __('Contact Info') }}" />
                    <x-stepper.header step="4" title="{{ __('Package Paramaters') }}" />
                    <x-stepper.header step="5" title="{{ __('Summary') }}" />
                    <x-stepper.header step="6" title="{{ __('Payment') }}" />
                </x-slot>

                <x-slot name="body">
                    {{-- step 1 --}}
                    <x-stepper.body step="1" nextClick="validateStep1" :showPrev="false">
                        {{-- list of selectable package types --}}
                        <div class="my-2 mb-4">
                            {{-- customer --}}
                            <x-label for="customer_id" title="{{ __('Customer') }}">
                                <livewire:select.new-order-user-select name="user_id"
                                    placeholder="{{ __('Select Customer') }}" :searchable="true" />
                                <x-input-error message="{{ $errors->first('user_id') }}" />
                            </x-label>
                            <p class='mt-3 mb-1'>
                                {{ __('Package Type') }}
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($packageTypes as $mPackageType)
                                    @php
                                        $selectedClass = '';
                                        if ($selectedPackageTypeId == $mPackageType->id) {
                                            $selectedClass = 'border-primary-600 border-2';
                                        }
                                    @endphp

                                    <div class="{{ $selectedClass }} rounded border px-4 py-2 mb-2 flex items-center cursor-pointer"
                                        wire:click="onPackageTypeSelected('{{ $mPackageType->id }}')">
                                        <div class="mr-2">
                                            <img src="{{ $mPackageType->photo }}" alt="{{ $mPackageType->name }}"
                                                class="w-20 h-20 rounded" />
                                        </div>
                                        <div class="w-full">
                                            <p>{{ $mPackageType->name }}</p>
                                            <p>{{ $mPackageType->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-stepper.body>

                    {{-- step 2 --}}
                    <x-stepper.body step="2" nextClick="validateStep2">

                        <div class="my-4">
                            <div class="relative">
                                <!-- Timeline line -->
                                <div class="border-l-2 border-primary-500 absolute h-full top-0 left-4"></div>
                                @foreach ($orderStops ?? [] as $key => $orderStop)
                                    <!-- Timeline items -->
                                    <div class="flex items-start mb-8" wire:key="order-stop-{{ $key }}">
                                        <div
                                            class="bg-primary-500 rounded-full h-8 w-8 flex items-center justify-center mr-4">
                                            <span class="text-white text-sm font-bold">
                                                {{ $key + 1 }}
                                            </span>
                                        </div>
                                        <div class="flex-grow">
                                            <h3 class="text-lg font-bold">
                                                {{ $orderStop['label'] ?? __('Address') }}
                                            </h3>
                                            {{-- address autocomplete --}}
                                            <div class="{{ $orderStop['showMapPicker'] ?? false ? '' : 'hidden' }}">
                                                <livewire:component.autocomplete-address
                                                    wire:key="order-stop-{{ $key }}" name="address"
                                                    elementId="order-stop-{{ $key }}"
                                                    address="{{ $address ?? '' }}" extraData="{{ $key }}" />
                                            </div>
                                            {{-- select from delivery address --}}
                                            <div class="{{ $orderStop['showMapPicker'] ?? false ? 'hidden' : '' }}">
                                                <x-select name="orderStops.{{ $key }}.id" :options="$deliveryAddresses"
                                                    :noPreSelect="true" :defer="false" />
                                            </div>
                                            {{-- add error  --}}
                                            <x-input-error
                                                message="{{ $errors->first('orderStops.' . $key . '.address') }}" />
                                        </div>
                                        @if (!$orderStop['showMapPicker'])
                                            {{-- icon for map picker --}}
                                            <div class="ml-2">
                                                <h3 class="text-lg font-bold"> &nbsp; </h3>
                                                <x-buttons.plain wireClick="openMapPicker({{ $key }})"
                                                    bgColor="bg-primary-600">
                                                    <x-heroicon-o-map class="w-5 h-5" />
                                                </x-buttons.plain>
                                            </div>
                                        @else
                                            {{-- icon for select address --}}
                                            <div class="ml-2">
                                                <h3 class="text-lg font-bold"> &nbsp; </h3>
                                                <x-buttons.plain wireClick="openAddressPicker({{ $key }})"
                                                    bgColor="bg-primary-600">
                                                    <x-heroicon-o-search class="w-5 h-5" />
                                                </x-buttons.plain>
                                            </div>
                                        @endif
                                        {{-- icon for removing stop --}}
                                        <div class="ml-2 {{ $key == 0 ? 'hidden' : '' }}">
                                            <h3 class="text-lg font-bold"> &nbsp; </h3>
                                            <x-buttons.plain wireClick="removeOrderStop({{ $key }})"
                                                bgColor="bg-red-600">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </x-buttons.plain>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- add new stop if multiple stops is enabled --}}
                            @if ((bool) setting('enableParcelMultipleStops'))
                                <div class="mt-2 flex justify-start">
                                    <x-buttons.plain wireClick="addOrderStop" bgColor="bg-primary-600">
                                        <x-heroicon-o-plus class="w-5 h-5" />
                                        <span class="mx-1">{{ __('Add New Stop') }}</span>
                                    </x-buttons.plain>
                                </div>
                            @endif
                        </div>
                    </x-stepper.body>

                    {{-- step 3 --}}
                    <x-stepper.body step="3" nextClick="validateStep3">

                        <div class="my-4">
                            @foreach ($orderStops ?? [] as $key => $orderStop)
                                <div class="border-primary-600 border-2 rounded p-4 mb-8"
                                    wire:key="order-stop-{{ $orderStop['code'] }}" x-data="{ isOpen: true }">
                                    <div class="flex justify-between items-center">
                                        <div class="w-full">
                                            <p class='text-xl font-bold'>
                                                {{ __('Contact Info') }}
                                            </p>
                                            <p>
                                                <span
                                                    class="font-medium">{{ $orderStop['label'] ?? __('Address') }}:</span>
                                                {{ $orderStop['address'] }}
                                            </p>
                                        </div>
                                        {{-- toggle button --}}
                                        <button x-on:click="isOpen = !isOpen">
                                            <x-heroicon-o-chevron-down x-show="!isOpen" class="w-5 h-5 text-gray-500" />
                                            <x-heroicon-o-chevron-up x-show="isOpen" class="w-5 h-5 text-gray-500" />
                                        </button>
                                    </div>
                                    {{-- info --}}
                                    <div x-show="isOpen">
                                        <x-input type="text" name="orderStops.{{ $key }}.contact.name"
                                            title="{{ __('Name') }}" />
                                        <x-input type="tel" name="orderStops.{{ $key }}.contact.phone"
                                            title="{{ __('Phone') }}" />
                                        <x-textarea name="orderStops.{{ $key }}.contact.note"
                                            title="{{ __('Note') }}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-stepper.body>


                    {{-- step 4 --}}
                    <x-stepper.body step="4" nextClick="validateStep4">

                        <div class="my-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input type="numeric" name="package_weight" title="{{ __('Package weight') }}(kg)" />
                            <x-input type="numeric" name="package_length" title="{{ __('Package length') }}(cm)" />
                            <x-input type="numeric" name="package_width" title="{{ __('Package width') }}(cm)" />
                            <x-input type="numeric" name="package_height" title="{{ __('Package height') }}(cm)" />
                        </div>
                    </x-stepper.body>

                    {{-- step 5 --}}
                    <x-stepper.body step="5" nextClick="initiatePaymentPage">
                        @if ($currentStep == 5)
                            <div class="my-4">
                                {{-- package type --}}
                                <p class="font-semibold">{{ __('Package Type') }}</p>
                                <div class="rounded border px-4 py-2 mb-2 flex items-center">
                                    <div class="mr-2">
                                        <img src="{{ $packageType->photo }}" alt="{{ $packageType->name }}"
                                            class="w-12 h-12 rounded" />
                                    </div>
                                    <div class="w-full">
                                        <p class="font-semibold">{{ $packageType->name }}</p>
                                        <p class="text-sm">{{ $packageType->description }}</p>
                                    </div>
                                </div>


                                {{-- vendor --}}
                                <p class="mt-4 font-semibold">{{ __('Courier Vendor') }}</p>
                                <div class="rounded border px-4 py-2 mb-2">
                                    <div class="flex items-center">
                                        <div class="mr-2">
                                            <img src="{{ $selectedModel->logo }}" alt="{{ $selectedModel->name }}"
                                                class="w-12 h-12 rounded" />
                                        </div>
                                        <div class="w-full">
                                            <p class="font-semibold">{{ $selectedModel->name }}</p>
                                            <p class="text-sm">{{ $selectedModel->description }}</p>
                                        </div>
                                    </div>
                                    {{-- if schedule_enable is true, show schedule time and date --}}
                                    @if ($schedule_enable)
                                        <div class="mt-2 border-t grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <x-details.item title="{{ __('Schedule Date') }}">
                                                <p>{{ $schedule_date }}</p>
                                            </x-details.item>
                                            <x-details.item title="{{ __('Schedule Time') }}">
                                                <p>{{ $schedule_time }}</p>
                                            </x-details.item>
                                        </div>
                                    @endif
                                </div>

                                {{-- order stops --}}
                                <p class="mt-4 font-semibold">{{ __('Delivery Details') }}</p>
                                <div class="w-full">
                                    @foreach ($orderStopsPreview ?? [] as $key => $orderStop)
                                        <div class="rounded border px-4 py-2 mb-2">
                                            <div class="px-4 py-2 flex items-center">
                                                <div class="mr-2">
                                                    <x-heroicon-o-location-marker class="w-6 h-6 text-gray-500" />
                                                </div>
                                                <div class="w-full">
                                                    <p class="font-semibold">
                                                        {{ $orderStop['label'] ?? __('Address') }}
                                                    </p>
                                                    <p class="text-sm">{{ $orderStop['address'] }}</p>
                                                </div>

                                            </div>
                                            {{-- contact info --}}
                                            <div class="border-t grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                                                <x-details.item title="{{ __('Name') }}">
                                                    <p>{{ $orderStop['name'] }}</p>
                                                </x-details.item>
                                                <x-details.item title="{{ __('Phone') }}">
                                                    <p>{{ $orderStop['phone'] }}</p>
                                                </x-details.item>

                                            </div>
                                            <x-details.item title="{{ __('Note') }}">
                                                <p>{{ $orderStop['note'] }}</p>
                                            </x-details.item>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- package details --}}
                                <p class="mt-4 font-semibold">{{ __('Package Parameters') }}</p>
                                <div class="w-full">
                                    <div class="rounded border px-4 py-2 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                                            <x-details.item title="{{ __('Weight') }}">
                                                <p>{{ $package_weight }} kg</p>
                                            </x-details.item>
                                            <x-details.item title="{{ __('Length') }}">
                                                <p>{{ $package_length }} cm</p>
                                            </x-details.item>
                                            <x-details.item title="{{ __('Width') }}">
                                                <p>{{ $package_width }} cm</p>
                                            </x-details.item>
                                            <x-details.item title="{{ __('Height') }}">
                                                <p>{{ $package_height }} cm</p>
                                            </x-details.item>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </x-stepper.body>


                    {{-- step 6 --}}
                    <x-stepper.body step="6" nextClick="placeNewOrder" nextText="{{ __('Place Order') }}">


                        {{-- who is paying --}}
                        <div class="border-t border-b py-4">
                            <p class="font-semibold">{{ __('Order Payer') }}</p>
                            <p class="text-sm">{{ __('Who is paying for this order?') }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-radio name="payer" title="{{ __('Sender') }}" value="sender"
                                    :defer="false" />
                                <x-radio name="payer" title="{{ __('Receiver') }}" value="receiver"
                                    :defer="false" />
                            </div>
                        </div>
                        {{-- payment methods --}}
                        <div class="border-b py-4">
                            <p class="font-semibold">{{ __('Payment Methods') }}</p>
                            <p class="text-sm">{{ __('How do you want to pay for this order?') }}</p>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($paymentMethods ?? [] as $paymentMethod)
                                    @php
                                        $selectedStyle = '';
                                        if ($paymentMethod->id == $payment_method_id) {
                                            $selectedStyle = 'border-2 border-primary-500';
                                        }
                                    @endphp
                                    <div class="{{ $selectedStyle }} border rounded flex items-center cursor-pointer"
                                        wire:click="onPaymentMethodSelected('{{ $paymentMethod->id }}')">
                                        <img src="{{ $paymentMethod->photo }}" alt="{{ $paymentMethod->name }}"
                                            class="w-16 h-16 object-cover" />
                                        <div class="p-2">
                                            <p class="font-semibold">{{ $paymentMethod->name }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- payment --}}
                        <div class="mx-auto justify-end border-b py-4 w-full md:w-4/12">
                            <p class="font-bold text-lg my-2">{{ __('Payment') }}</p>
                            <div>
                                <x-details.amount title="{{ __('Distance') }}"
                                    amount="{{ number_format($distance, 2) }}km" />
                                <x-details.amount title="{{ __('Delivery Charges') }}"
                                    amount="{{ currencyFormat($delivery_fee) }}" />
                                <x-details.amount title="{{ __('Package Size Charges') }}"
                                    amount="{{ currencyFormat($package_parameter_fee) }}" />
                                <hr class="my-1" />
                                <x-details.amount title="{{ __('Subtotal') }}"
                                    amount="{{ currencyFormat($sub_total) }}" />
                                <hr class="my-1" />
                                {{-- coupon input --}}
                                <div class="flex mb-1 items-start">
                                    <div class="w-full">
                                        <x-input name="coupon_code" placeholder="{{ __('Coupon Code') }}" />
                                    </div>
                                    <x-buttons.plain wireClick="applyCoupon()" bgColor="mt-1 bg-primary-500 ml-2">
                                        {{ __('Apply') }}
                                    </x-buttons.plain>
                                </div>
                                <x-details.amount title="{{ __('Discount') }}"
                                    amount="{{ currencyFormat($discount) }}" />

                                <hr class="my-1" />
                                <x-details.amount title="{{ __('Tax') }}({{ $selectedModel->tax ?? 0 }}%)"
                                    amount="{{ currencyFormat($tax) }}" />
                                <hr />
                                <x-details.amount title="{{ __('Total') }}"
                                    amount="{{ currencyFormat($total) }}" />
                            </div>
                        </div>

                    </x-stepper.body>
                </x-slot>

            </x-stepper.main>
        </div>
    </x-baseview>


</div>
