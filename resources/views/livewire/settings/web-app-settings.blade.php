<div>

    <x-form action="saveAppSettings" :noClass="true">

        <div class="grid grid-cols-1 gap-5">
            <div class="">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    {{-- schedule max days --}}
                    <div>
                        <x-input title="{{ __('Max Schedule Order Days') }}" name="maxScheduledDay" type="number" />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('The max number of days customers are allowed to schedule an order') }}</p>
                    </div>
                    {{-- schedule time range --}}
                    <div>
                        <x-input title="{{ __('Schedule Order Time Interval') }}(mins)" name="vendor_slot_interval"
                            type="number" />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('Schedule time interval selection') }}
                        </p>
                    </div>
                    {{-- schedule order time --}}
                    <div>
                        <x-input title="{{ __('Schedule Order Time') }}" name="minScheduledTime" type="number" />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __("Hours before pickup time for system to move order from 'scheduled' to 'pending'") }}
                        </p>
                    </div>
                    {{-- schedule order time --}}
                    <div>
                        <x-input title="{{ __('Max Schedule Time') }}" name="maxScheduledTime" type="number" />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('Hours from close time that customer still allow to book for') }}</p>
                    </div>
                    {{-- autocancel pending order --}}
                    <div>
                        <x-input title="{{ __('Cancel Pending Order Time') }} (Mins)" name="autoCancelPendingOrderTime"
                            type="number" />
                        <p class="mt-1 text-xs text-gray-500">{{ __('Auto cancel pending orders') }}</p>
                    </div>
                    {{-- autocancel pending order --}}
                    <div>
                        <x-input title="{{ __('Default Rating') }}" name="defaultVendorRating" type="number" />
                        <p class="mt-1 text-xs text-gray-500">{{ __('Default Rating') }}</p>
                    </div>

                    {{-- auto set vendor open/close time back --}}
                    <div>
                        <x-input title="{{ __('Auto Set Vendor Open/Close Timning') }} ({{ __('Hour(s)') }})"
                            name="vendorResetOpenCloseTime" type="number" />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('Vendor sometimes manually set to open/close outside of the open/close time, this will set the vendor open/lose back using the default open/close time') }}
                        </p>
                    </div>



                </div>
                <hr class="my-2" />
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    {{-- enable/disable product update request --}}
                    <div>
                        <x-checkbox title="{{ __('Product Details Verification') }}"
                            name="productDetailsUpdateRequest">
                            <p class="mt-1 text-xs text-gray-500">
                                {{ __('When enabled product created/updated by vendors will need to be approved by admin/role with right permission') }}
                            </p>
                        </x-checkbox>
                    </div>
                </div>
                <x-buttons.primary title="{{ __('Save Changes') }}" />
            </div>
        </div>
    </x-form>

</div>
