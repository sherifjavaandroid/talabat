@section('title', __('Taxi Settings'))
<div>

    <x-baseview title="{{ __('Taxi Settings') }}">

        <x-form action="saveSettings">

            {{-- genereal settings --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 w-full">
                <div class="">

                    <x-input title="{{ __('Taxi Tax') }}(%)" name="tax" />
                    <hr class="my-4" />
                    <x-input title="{{ __('Taxi Auto Cancel Time') }}({{ __('Mintues') }})"
                        name="cancelPendingTaxiOrderTime" />
                    <x-input title="{{ __('Max order schedule days') }}" name="taxiMaxScheduleDays" />
                    <x-input title="{{ __('Average driving speed') }}(KM/H)" name="drivingSpeed" />
                    <x-select title="{{ __('Request Booking Code') }}" name="requestBookingCode" :options="$bookingCodeOptions" />
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <x-checkbox title="{{ __('Multiple Currency') }}" name="multipleCurrency" :defer="false" />
                    <x-checkbox title="{{ __('Allow schedule taxi order') }}" name="canScheduleTaxiOrder"
                        :defer="false" />
                    <div class="border-t">
                        <x-checkbox title="{{ __('Allow taxi order fare recalculation') }}" name="recalculateFare"
                            :defer="false" />
                        <p class="my-2 text-xs italic text-gray-500">
                            <span class="text-sm text-red-500">{{ __('Note') }}:</span>
                            {{ __("When activated, the wallet system will also activate automatically. The system will deduct any overdraft trip balance from the user's wallet balance.") }}
                        </p>
                    </div>
                    <div class="border-t"></div>

                    <div class="border-t">
                        <x-checkbox title="{{ __('Show Pickup Info') }}" name="showTaxiPickupInfo" :defer="false" />
                        <p class="my-2 text-xs italic text-gray-500">
                            <span class="text-sm text-red-500">{{ __('Note') }}:</span>
                            {{ __('When activated, the driver will be able to see the pickup address of the order before accepting the order.') }}
                        </p>
                    </div>
                    <div class="border-t">
                        <x-checkbox title="{{ __('Show Destination Info') }}" name="showTaxiDropoffInfo"
                            :defer="false" />
                        <p class="my-2 text-xs italic text-gray-500">
                            <span class="text-sm text-red-500">{{ __('Note') }}:</span>
                            {{ __('When activated, the driver will be able to see the dropoff address of the order before accepting the order.') }}
                        </p>
                    </div>


                </div>
            </div>
            {{-- matching settings --}}
            <p class="pt-4 mt-10 text-2xl border-t">{{ __('Driver Matching') }}</p>
            <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 '>

                {{-- <x-checkbox title="{{ __('Use Firebase cloud function') }}" name="taxiUseFirebaseServer" description="{{ __('Your taxi order will be matched with driver using firebase cloud function') }}" :defer="false" /> --}}
                <x-input title="{{ __('Delay Taxi matching') }}({{ __('Seconds') }})" name="delayTaxiMatching" />
                <x-input title="{{ __('Delay same order subsequent Search') }}({{ __('Seconds') }})"
                    name="delayResearchTaxiMatching" />
            </div>

            {{-- custom message --}}
            <p class="pt-4 mt-10 text-2xl border-t">{{ __('Custom Notification Message') }}</p>
            <p class="text-sm font-light text-red-300">
                {{ __('These are messages sent to customer base on order status') }}</p>
            <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 '>

                <x-input title="{{ __('Pending') }}" name="pending" />
                <x-input title="{{ __('Preparing') }}" name="preparing" />
                <x-input title="{{ __('Ready') }}" name="ready" />
                <x-input title="{{ __('Enroute') }}" name="enroute" />
                <x-input title="{{ __('Completed') }}" name="completed" />
                <x-input title="{{ __('Cancelled') }}" name="cancelled" />
                <x-input title="{{ __('Failed') }}" name="failed" />
            </div>
            <hr />
            <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 '>
                <div>
                    <x-input title="{{ __('Cash Overdraft Completed') }}" name="cash_overdraft_completed" />
                    <p class="my-2 text-xs italic text-red-300">
                        {{ __('Notification message to be sent to customer when trip amount is more than initially calculated') }}
                    </p>
                    <div class="my-2 text-xs italic text-gray-600">
                        {{ __('Use the codes below and it will be replace will values before sending notification') }}
                        <ul>
                            <li>:amt - total</li>
                            <li>:bal - outstanding</li>
                            <li>:pai - already paid</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <x-input title="{{ __('Online payment overdraft completed') }}" name="overdraft_completed" />
                    <p class="my-2 text-xs italic text-red-300">
                        {{ __('Notification message to be sent to customer when trip amount is more than initially calculated for Online payments') }}
                    </p>
                    <div class="my-2 text-xs italic text-gray-600">
                        {{ __('Use the codes below and it will be replace will values before sending notification') }}
                        <ul>
                            <li>:amt - total</li>
                            <li>:bal - outstanding</li>
                            <li>:pai - already paid</li>
                        </ul>
                    </div>
                </div>
            </div>

            <x-buttons.primary title="{{ __('Save Changes') }}" />

        </x-form>

    </x-baseview>

</div>
