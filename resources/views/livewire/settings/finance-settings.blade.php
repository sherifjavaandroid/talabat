@section('title', __('Finance Settings'))
<div>

    <x-baseview title="{{ __('Finance Settings') }}">

        <x-form action="saveAppSettings">

            <div class="">

                {{-- Finance --}}
                <p class="text-2xl">{{ __('Finance') }}</p>
                <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('General Drivers Commission') }}(%)" name="driversCommission" />
                    </div>

                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('General Vendors Commission') }}(%)" name="vendorsCommission" />
                    </div>

                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('General Vendors Tax') }}(%)" name="generalTax" />
                    </div>
                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('General Vendors Min Order Amount') }}" name="minOrderAmount" />
                    </div>
                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('General Vendors Max Order Amount') }}" name="maxOrderAmount" />
                    </div>
                </div>
                <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>

                    <div class="block mt-4 text-sm">
                        <p>{{ __('Driver Self Pay') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" description="" name="driverSelfPay" :defer="true" />
                        <p class="mt-2 text-xs italic">
                            {{ __('Enable this if you want system to only calculate system commission from delivery fee and vendor order amount with the assumption that driver has paid for order') }}
                        </p>
                        <p class="text-xs italic text-red-500">{{ __('Only for Cash On Delivery') }}</p>
                    </div>
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Vendor can set delivery fee') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="vendorSetDeliveryFee" :defer="true" />
                    </div>
                    <div class="block mt-4 text-sm">
                        <x-select title="{{ __('Prevent Order Cancellation') }}" name="preventOrderCancellation"
                            :multiple="true" :options="$orderStatues ?? []" />
                    </div>
                </div>
                {{-- payment timeout  --}}
                <p class="pt-4 border-t text-2xl">{{ __('Payment Timeout') }}</p>
                <div class='mb-10 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>
                    {{-- order online payment timeout --}}
                    <x-input title="{{ __('Order Online Payment Timeout') }} (mins)" name="orderOnlinePaymentTimeout"
                        hint="{{ __('Set zero(0) to disable the auto timeout') }}" />
                    {{-- wallet topup payment timeout --}}
                    <x-input title="{{ __('Wallet Topup Payment Timeout') }} (mins)" name="walletTopupPaymentTimeout"
                        hint="{{ __('Set zero(0) to disable the auto timeout') }}" />
                    {{-- vendor subscription payment timeout --}}
                    {{-- <x-input title="{{ __('Vendor Subscription Payment Timeout') }} (mins)"
                        name="vendorSubscriptionPaymentTimeout" /> --}}
                </div>

                {{-- refer  --}}
                <p class="pt-4 border-t text-2xl">{{ __('Refer') }}</p>
                <div class='grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3'>

                    {{-- refer --}}
                    <x-checkbox title="{{ __('Refer System') }}"
                        description="{{ __('Enable refer system. When enabled, user will be rewarded for referring other users') }}"
                        name="enableReferSystem" :defer="true" />

                    <x-checkbox title="{{ __('Reward on regirstration') }}"
                        description="{{ __('When enabled, user will be rewarded after registration, else reward will be after first order') }}"
                        name="enableOnRegistrationReferReward" :defer="true" />

                    <div x-data="{ open: @entangle('enableReferSystem') }">
                        <x-input title="{{ __('Refer Amount') }}" name="referRewardAmount" />
                    </div>


                </div>




                {{-- Driver releated settings --}}
                <p class="pt-4 mt-10 text-2xl border-t">{{ __('Earning') }}</p>
                <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 '>

                    <div class="block p-2 mt-4 text-sm border rounded">
                        <p>{{ __('Vendor Earning') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="vendorEarningEnabled" :defer="true" />

                        <p class="py-2 text-xs font-light"><span
                                class="pr-2 text-sm font-medium text-red-600">Note:</span>{{ __('Vendor Earning Enable(irrespective of the payment method used)') }}
                        </p>
                    </div>
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Driver Wallet System') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="enableDriverWallet" :defer="true" />
                        <p class="py-2 text-xs font-light"><span
                                class="pr-2 text-sm font-medium text-red-600">Note:</span>{{ __('This needs to be abled for the rest driver wallet related settings to work') }}
                        </p>
                    </div>


                    <div class="block p-2 mt-4 text-sm border rounded">
                        <p>{{ __('Driver Wallet Balance Require') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="driverWalletRequired" :defer="true" />
                        <p class="py-2 text-xs font-light"><span
                                class="pr-2 text-sm font-medium text-red-600">Note:</span>{{ __('Driver must have enough in wallet balance irrespective of the payment method') }}
                        </p>
                    </div>

                    <div class="block p-2 mt-4 text-sm border rounded">
                        <p>{{ __('Driver Wallet Balance Require For Order Total') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="driverWalletRequiredForTotal"
                            :defer="true" />
                        <p class="py-2 text-xs font-light"><span
                                class="pr-2 text-sm font-medium text-red-600">Note:</span>{{ __('Driver must have enough in wallet balance to cover order total or only admin commission from delivery fee') }}
                        </p>
                    </div>


                    <div class="block mt-4 text-sm">
                        <p>{{ __('Enforce Cash Delivery Payment') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="collectDeliveryCash" :defer="true" />
                        <p class="py-2 text-xs font-light"><span
                                class="pr-2 text-sm font-medium text-red-600">Note:</span>{{ __('Irrespective of the order payment method, order delivery fee will have to be paid in cash to delivery personal') }}
                        </p>
                    </div>


                </div>


                <p class="pt-4 mt-10 text-2xl border-t">{{ __('Delivery') }}</p>
                <p class="text-xs italic">{{ __('Delivery info below will be use for vendor withut delivery info') }}
                </p>
                <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
                    <x-checkbox title="{{ __('Charge per KM') }}" name="charge_per_km"
                        description="{{ __('Delivery fee will be per KM') }}" />
                    <x-input title="{{ __('Base Delivery Fee') }}" name="base_delivery_fee" />
                    <x-input title="{{ __('Delivery Fee') }}" name="delivery_fee" />

                    <x-input title="{{ __('Delivery Range(KM)') }}" name="delivery_range" />

                </div>


                {{-- Wallet --}}
                <p class="pt-4 mt-10 text-2xl border-t">{{ __('Wallet') }}</p>
                <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Wallet') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" description="{{ __('Allow wallet') }}"
                            name="allowWallet" :defer="true" />
                    </div>

                    <div class="block mt-4 text-sm">
                        <p>{{ __('Wallet Transfer') }}</p>
                        <x-checkbox title="{{ __('Enable') }}"
                            description="{{ __('Allow wallet transfer between users') }}" name="allowWalletTransfer"
                            :defer="true" />
                    </div>

                    <div class="block mt-4 text-sm">
                        <p>{{ __('Require Full Customer Info') }}</p>
                        <x-checkbox title="{{ __('Enable') }}"
                            description="{{ __('Customer must enter full email or phone number of the user they want to transfer to') }}"
                            name="fullInfoRequired" :defer="true" />
                    </div>
                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('Minimum Wallet Topup Amount') }}" name="minimumTopupAmount" />
                    </div>
                    <div class="block mt-4 text-sm">
                        <x-input title="{{ __('Wallet Topup Percentage') }} (%)" name="walletTopupPercentage" />
                        <p class="text-xs font-light text-gray-600">
                            {{ __('You can set the percenatge of the wallet topup amount that should be credited to user during wallet topup') }}
                        </p>
                        <p class="text-xs italic font-light text-red-500">
                            {{ __('For eaxmple: You set 90%, if user try to topup wallet with U$100, after payment their wallet will be credited U$90') }}
                        </p>
                    </div>

                </div>

                {{-- Refund --}}
                <p class="pt-4 mt-10 text-2xl border-t">{{ __('Refunds') }}</p>
                <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Auto Payment Refund') }}</p>
                        <x-checkbox title="{{ __('Enable') }}"
                            description="{{ __('Order amount will automatically be refund to user wallet if payment was successful but order failed') }}"
                            name="autoRefund" :defer="true" />
                    </div>

                </div>

                {{-- loyalty points --}}
                @can('view-loyalty-settings')
                    <p class="pt-4 mt-10 text-2xl border-t">{{ __('Loyalty Point') }}</p>
                    <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
                        <x-input title="{{ __('Amount To 1 Point') }}"
                            hint="{{ __('This is the amount that will be rewared with 1 point. e.g Set 0.001 if you want every') }} {{ setting('currency', '$') }} {{ __('0.001 spent to be rewarded with 1 point') }}"
                            name="amount_to_point" />
                        <x-input title="{{ __('1 Point To Amount') }}"
                            hint="{{ __('This is the amount that 1 point can be converted to. e.g Set 0.0001 if you want every 1 point to be convertable to 0.001 and credited to user wallet') }}"
                            name="point_to_amount" />
                        <div class="block mt-4 text-sm">
                            <p></p>
                            <x-checkbox title="{{ __('Enable') }}"
                                description="{{ __('Enable Loyalty Point System') }}" name="enableLoyalty"
                                :defer="true" />
                        </div>
                    </div>
                @endcan


                <x-buttons.primary title="{{ __('Save Changes') }}" />
                <div>
        </x-form>

    </x-baseview>

</div>
