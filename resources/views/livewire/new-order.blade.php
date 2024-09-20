@section('title', __('New Order'))
<div>


    <x-form action="showSummary">
        <x-baseview title="{{ __('Create Order') }}">

            <div class='md:min-h-[70%] grid grid-cols-1 md:grid-cols-4 md:gap-4'>
                <div class="col-span-3">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        {{-- customer --}}
                        <x-label for="customer_id" title="{{ __('Customer') }}">
                            <livewire:select.new-order-user-select name="user_id"
                                placeholder="{{ __('Select Customer') }}" :searchable="true" />
                        </x-label>
                        {{-- vendors --}}
                        <x-label for="vendor_id" title="{{ __('Vendor') }}">
                            <livewire:select.new-order-vendor-select name="vendor_id"
                                placeholder="{{ __('Select Vendor') }}" :searchable="true" />
                        </x-label>
                    </div>

                    {{-- products --}}
                    <x-label for="product_id" title="{{ __('Product') }}">
                        <livewire:select.new-order-product-select name="product_id"
                            placeholder="{{ __('Search products') }}" :searchable="true" :depends-on="['vendor_id']" />
                    </x-label>
                    <table class="my-4 w-full shadow-sm border-collapse border border-gray-400">
                        @php
                            $cellClasses = 'border border-gray-300 p-2 py-4 text-left';
                        @endphp
                        <thead>
                            <tr class="bg-black text-white">
                                <th colspan="3" class="{{ $cellClasses }}">{{ __('Product') }}</th>
                                <th class="{{ $cellClasses }}">{{ __('Options') }}</th>
                                <th class="{{ $cellClasses }}">{{ __('Quantity') }}</th>
                                <th class="{{ $cellClasses }}">{{ __('Price') }}</th>
                                <th class="{{ $cellClasses }} text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr class=" {{ $key % 2 == 1 ? 'bg-gray-100' : 'bg-white' }} ">
                                    <td colspan="3" class="{{ $cellClasses }}">
                                        {{ $product['name'] ?? '' }}
                                    </td>
                                    <td class="{{ $cellClasses }}">
                                        @php
                                            $selected_options = $product['selected_options'] ?? [];
                                            if (is_string($selected_options)) {
                                                $selected_options = json_decode($selected_options, true);
                                            }
                                        @endphp
                                        @foreach ($selected_options ?? [] as $option)
                                            {{ $option['name'] ?? '' }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </td>
                                    <td class="{{ $cellClasses }} w-24">
                                        <x-input type="number" name="products.{{ $key }}.qty"
                                            min="1" />
                                    </td>
                                    <td class="{{ $cellClasses }}">
                                        {{ currencyFormat($product['price'] ?? '0') }}
                                    </td>
                                    <td class="{{ $cellClasses }} flex items-center justify-center">
                                        <x-buttons.plain bgColor="bg-red-500"
                                            wireClick="removeProduct({{ $key }})">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                            </x-button>
                                    </td>
                                </tr>
                            @endforeach

                            @empty($products)
                                <tr>
                                    <td colspan="7" class="text-center p-4">
                                        {{ __('No products added yet') }}
                                    </td>
                                </tr>
                            @endempty
                        </tbody>
                    </table>

                    <hr class="my-4" />
                    {{-- checkbox for if vendor allows delviery --}}
                    <x-checkbox name="isPickup" title="{{ __('Pickup Order') }}" :defer="false" />

                    @if (!$isPickup)
                        {{-- pickup address --}}
                        <x-label for="delivery_address_id" title="{{ __('Delivery Address') }}">
                            <livewire:select.new-order-delivery-address-select name="delivery_address_id"
                                placeholder="{{ __('Select Delivery Address') }}" :searchable="true"
                                :depends-on="['user_id']" />
                        </x-label>
                    @endif

                </div>

                <div class="mt-4 md:mt-0 md:border-l md:pl-4">
                    <x-label for="payment_method_id" title="{{ __('Payment Method') }}">
                        <livewire:select.payment-method-select name="payment_method_id" :value="$payment_method_id"
                            placeholder="--{{ __('Select') }}--" :depends-on="['vendor_id']" />
                    </x-label>

                    {{-- tip input --}}
                    @if (!$isPickup)
                        <x-input type="number" title="{{ __('Driver Tip') }}" name="tip" min="0" />
                    @endif

                    {{-- order note --}}
                    <x-textarea title="{{ __('Note') }}" name="note" />

                    {{-- coupon input --}}
                    <div class="w-full flex items-start justify-start">
                        <div class="w-full">
                            <x-input type="text" title="{{ __('Coupon Code') }}" name="coupon_code" />
                        </div>
                        <div class="ml-2 mt-10">
                            <x-buttons.plain wireClick="applyCoupon" bgColor="bg-primary-500">
                                {{ __('Apply') }}
                            </x-buttons.plain>
                        </div>
                    </div>

                    <hr class="my-4" />
                    {{-- summary btn --}}
                    <x-buttons.primary noMargin="true">
                        {{ __('Show Summary') }}
                    </x-buttons.primary>
                </div>
            </div>

        </x-baseview>
    </x-form>




    {{-- options modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal action="addOptionsToProduct" confirmText="{{ __('Add') }}">
            <p class="text-xl font-semibold">{{ __('Product Options') }}</p>
            <hr class="my-2" />
            @if (!empty($selectedModel))
                {{-- option groups --}}
                @foreach ($optionGroups ?? [] as $optionGroup)
                    {{-- title --}}
                    <p class="font-boldmt-4 text-lg">{{ $optionGroup->name }}</p>
                    {{-- options --}}
                    <div class="my-2 mb-8">
                        @foreach ($optionGroup->options as $key => $option)
                            <div class="mb-2 flex items-center justify-items-center">

                                @if ($optionGroup->multiple ?? false)
                                    <input type="checkbox" name="option_{{ $option->id }}"
                                        id="option_{{ $option->id }}" class="form-checkbox h-5 w-5 text-indigo-600"
                                        wire:key="option_seclection.{{ $key }}"
                                        wire:model.defer="newProductSelectedOptions.{{ $option->id }}"
                                        value="1">
                                @else
                                    <input type="radio" name="option_{{ $option->id }}"
                                        id="optionGroup_{{ $optionGroup->id }}"
                                        class="form-radio h-5 w-5 text-indigo-600"
                                        wire:key="option_seclection.{{ $key }}"
                                        wire:model.defer="newProductSelectedOptions.{{ $option->id }}"
                                        value="1">
                                @endif
                                <p class="font-medium text-sm w-full mx-2 ">{{ $option->name }}</p>
                                <p class="font-bold text-sm w-3/12 text-right">
                                    {{ currencyFormat($option->price ?? 0) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </x-modal>
    </div>


    {{-- order summary --}}

    {{-- ORDER PLACEMENT  --}}
    <div x-data="{ open: @entangle('showSummary') }">
        <x-modal-lg confirmText="{{ __('Place Order') }}" action="saveNewOrder" onCancel="$set('showSummary', false)">
            <p class="text-xl font-semibold">{{ __('Order Summary') }}</p>
            {{-- order summary  --}}
            <div class="grid grid-cols-1 md:grid-cols-2">
                <x-details.item title="{{ __('Customer') }}" text="{{ $newOrder->user->name ?? '' }}" />
                @if (!$isPickup)
                    <x-details.item title="{{ __('Delivery Address') }}"
                        text="{{ $newOrder->delivery_address->address ?? 'Pickup' }}" />
                @endif
                <x-details.item title="{{ __('Note') }}" text="{{ $newOrder->note ?? '' }}" />
                <x-details.item title="{{ __('Payment Method') }}"
                    text="{{ $newOrder->payment_method->name ?? '' }}" />
            </div>
            <hr class="my-4" />
            {{-- vendor details  --}}
            <x-details.item title="{{ __('Vendor') }}" text="{{ $newOrder->vendor->name ?? '' }}" />
            {{-- products  --}}
            <p class="mt-4 mb-2 font-medium text-md">{{ __('Products') }}</p>
            <table class="w-full p-2 border rounded-sm">
                <thead>
                    <tr>
                        <th class="w-1/12 p-2 bg-gray-300 border-b">S/N</th>
                        <th class="w-3/12 p-2 bg-gray-300 border-b">Name</th>
                        <th class="w-4/12 p-2 bg-gray-300 border-b">Options</th>
                        <th class="w-2/12 p-2 bg-gray-300 border-b">QTY</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($products ?? [] as $key => $product)
                        <tr>
                            <td class="items-center w-1/12 px-2 py-1 border-b">
                                <span>{{ $key + 1 }}</span>
                            </td>
                            <td class="items-center w-2/12 px-2 py-1 border-b">
                                {{ $product['name'] ?? '' }}
                            </td>
                            <td class="items-center w-4/12 px-2 py-1 border-b">
                                @php
                                    $selected_options = $product['selected_options'] ?? [];
                                    if (is_string($selected_options)) {
                                        $selected_options = json_decode($selected_options, true);
                                    }
                                @endphp
                                @foreach ($selected_options ?? [] as $option)
                                    {{ $option['name'] ?? '' }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </td>
                            <td class="items-center w-1/12 px-2 py-1 border-b">
                                {{ $product['qty'] ?? '1' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr class="my-4" />
            <div class="">

                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Subtotal') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p text="{{ currencyFormat($newOrder->sub_total ?? '') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Discount') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p text="- {{ currencyFormat($newOrder->discount ?? '') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Tax') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p text="+ {{ currencyFormat($newOrder->tax ?? '') }}" />
                    </div>
                </div>
                @if (!$isPickup)
                    <div class="flex items-center justify-end space-x-20 border-b">
                        <x-label title="{{ __('Delivery Fee') }}" />
                        <div class="w-6/12 md:w-4/12 lg:w-2/12">
                            <x-details.p text="+ {{ currencyFormat($newOrder->delivery_fee ?? '0') }}" />
                        </div>
                    </div>
                @endif
                {{-- driver tip --}}
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Driver Tip') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p text="+ {{ currencyFormat($newOrder->tip ?? '0') }}" />
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-20 border-b">
                    <x-label title="{{ __('Total') }}" />
                    <div class="w-6/12 md:w-4/12 lg:w-2/12">
                        <x-details.p text="{{ currencyFormat($newOrder->total ?? '') }}" />
                    </div>
                </div>



        </x-modal-lg>
    </div>
</div>
