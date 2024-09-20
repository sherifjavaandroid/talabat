@if (!empty($selectedModel))

    @if (in_array($selectedModel->order_type, ['package', 'parcel', 'service', 'booking', 'taxi']))
        <p class="text-xl font-semibold">{{ __('Order Details') }}</p>
    @endif
    @switch($selectedModel->order_type)
        @case('package')
            @include('livewire.order.package_order_details')
        @break

        @case('parcel')
            @include('livewire.order.package_order_details')
        @break

        @case('service')
            @include('livewire.order.service_order_details')
        @break

        @case('taxi')
            @include('livewire.order.taxi_order_details')
        @break

        @default
            @extends('layouts.guest')
            {{-- printout --}}
            <div class="flex">
                <div class="mx-auto min-w-max" style="min-width: 100mm;">
                    {{-- logo --}}
                    <img src="{{ url(setting('websiteLogo', asset('images/logo.png'))) }}" alt=""
                        class="w-12 h-12 mx-auto my-1 rounded" />
                    {{-- title --}}
                    <h1 class="text-2xl font-semibold text-center mb-2">{{ setting('websiteName', env('APP_NAME')) }}</h1>
                    {{-- order details --}}
                    <div class="border-dotted border-2 border-spacing-x-2 py-2 font-bold mb-1 text-center">
                        <div>
                            {{ __('RECEIPT') }}
                        </div>
                        <p class="my-2"></p>
                        @if (setting('pos.showVendorDetails', true))
                            <div class="px-4">
                                {{-- vendor  --}}
                                <p class="flex w-full">
                                    <span class="text-sm font-light">{{ __('Vendor') }}:</span>
                                    <span class="mx-auto"></span>
                                    <span class="text-base font-medium">{{ $selectedModel->vendor->name ?? '' }}</span>
                                </p>
                                {{-- vendor address  --}}
                                <p class="flex w-full">
                                    <span class="text-sm font-light">{{ __('Address') }}:</span>
                                    <span class="mx-auto"></span>
                                    <span class="text-base font-medium">{{ $selectedModel->vendor->address ?? '' }}</span>
                                </p>
                                {{-- vendor phone  --}}
                                <p class="flex w-full">
                                    <span class="text-sm font-light">{{ __('Phone') }}:</span>
                                    <span class="mx-auto"></span>
                                    <span class="text-base font-medium">{{ $selectedModel->vendor->phone ?? '' }}</span>
                                </p>
                            </div>
                        @endif
                    </div>

                    <table class="w-full table-auto">
                        <thead class="border-dotted border-2 border-spacing-x-2 px-12 py-2 font-bold">
                            <td class="py-1 px-2">{{ __('Qty') }}</td>
                            <td class="py-1 px-2">{{ __('Item') }}</td>
                            <td class="py-1 px-2">{{ __('Price') }}</td>
                        </thead>
                        <tbody>
                            @foreach ($selectedModel->products as $orderProduct)
                                <tr class="border-b text-sm text-gray-500">
                                    <td class='px-2 py-1'>{{ $orderProduct->quantity }}x</td>
                                    <td class='px-2 py-1'>
                                        <p class="break-words font-semibold line-clamp-2">
                                            {{ $orderProduct->product->name }}
                                        </p>
                                        <p class="text-xs italic">{{ $orderProduct->options ?? '' }}</p>
                                    </td>
                                    <td class='px-2 py-1'>{{ currencyFormat($orderProduct->price ?? '') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- order summary --}}
                    <div class="border-dotted border-2 border-spacing-x-2 my-1 py-2 space-y-1 font-medium">
                        {{-- subtotal --}}
                        <div class="flex justify-between px-2">
                            <div class="text-left">{{ __('Subtotal') }}</div>
                            <div class="text-right">{{ currencyFormat($selectedModel->sub_total ?? '') }}</div>
                        </div>
                        {{-- discount --}}
                        <div class="flex justify-between px-2">
                            <div class="text-left">{{ __('Discount') }}</div>
                            <div class="text-right">- {{ currencyFormat($selectedModel->discount ?? '') }}</div>
                        </div>
                        {{-- tax --}}
                        <div class="flex justify-between px-2">
                            <div class="text-left">{{ __('Tax') }}({{ $selectedModel->tax_rate ?? '' }}%)</div>
                            <div class="text-right">+ {{ currencyFormat($selectedModel->tax ?? '') }}</div>
                        </div>
                        {{-- fees --}}
                        {{-- fees --}}
                        @foreach (json_decode($selectedModel->fees ?? [], true) as $fee)
                            <div class="flex text-lg font-medium">
                                <div class="grow ltr:text-left rtl:text-right">
                                    {{ __($fee['name'] ?? 'Fee') }}
                                </div>
                                <span class="mx-auto"></span>
                                <div class="grow-0">+ {{ currencyFormat($fee['amount'] ?? 0) }}</div>
                            </div>
                        @endforeach

                        {{-- total --}}
                        <hr class="mx-2" />
                        <div class="flex justify-between px-2">
                            <div class="font-bold text-lg text-left">{{ __('Total') }}</div>
                            <div class="font-semibold text-lg text-right"> {{ currencyFormat($selectedModel->total ?? '') }}
                            </div>
                        </div>
                    </div>

                    {{-- barcode  --}}
                    <div class="items-center justify-center text-center">
                        <p class="hidden" id="orderCode">{{ $selectedModel->code }}</p>
                        <div class="flex items-center justify-center">
                            <svg id="barcode"></svg>
                        </div>
                    </div>

                </div>


            </div>

            @push('scripts')
                <script src="{{ asset('js/print-barcode.js') }}"></script>
                <script src="{{ asset('js/receipt-print.js') }}"></script>
            @endpush
        @break
    @endswitch
@endif
