<div class=" w-full md:w-10/12 lg:w-6/12">
    <x-form noClass="true" action="saveMiscSettings">

        <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
            <x-checkbox title="{{ __('Vendor Phone') }}"
                description="{{ __('Show vendor phone contact in customer app') }}" name="showVendorPhone" />
            <x-checkbox title="{{ __('Vendor Address') }}"
                description="{{ __('Show vendor address contact in customer app') }}" name="showVendorAddress" />
        </div>

        <hr class="my-4" />
        <x-details.item title="{{ __('Call') }}">
            <x-checkbox title="{{ __('Customer - Vendor') }}"
                description="{{ __('Allow customer to call vendor and vice versa') }}" name="canCustomerVendorCall" />
            <x-checkbox title="{{ __('Customer - Driver') }}"
                description="{{ __('Allow customer to call driver and vice versa') }}" name="canCustomerDriverCall" />
            <x-checkbox title="{{ __('Driver - Vendor') }}"
                description="{{ __('Allow driver to call vendor and vice versa') }}" name="canDriverVendorCall" />
        </x-details.item>
        <hr class="my-4" />
        <x-details.item title="{{ __('Chat') }}">
            <p class="mt-4 text-sm font-semibold">{{ __('Vendor Chat') }}</p>
            <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
                <x-checkbox title="{{ __('Enable') }}"
                    description="{{ __('Allow chat between vendor and customer/driver') }}" name="canVendorChat" />
                <x-checkbox title="{{ __('Enable') }}"
                    description="{{ __('Allow image sharing in chat between vendor and customer/driver') }}"
                    name="canVendorChatSupportMedia" />

            </div>
            <p class="mt-4 text-sm font-semibold">{{ __('Customer Chat') }}</p>
            <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
                <x-checkbox title="{{ __('Enable') }}"
                    description="{{ __('Allow chat between customer and vendor/driver') }}" name="canCustomerChat" />
                <x-checkbox title="{{ __('Enable') }}"
                    description="{{ __('Allow image sharing in chat between customer and vendor/driver') }}"
                    name="canCustomerChatSupportMedia" />
            </div>
            <p class="mt-4 text-sm font-semibold">{{ __('Driver Chat') }}</p>
            <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
                <x-checkbox title="{{ __('Enable') }}"
                    description="{{ __('Allow chat between driver and customer/vendor') }}" name="canDriverChat" />
                <x-checkbox title="{{ __('Enable') }}"
                    description="{{ __('Allow image sharing in chat between driver and customer/vendor') }}"
                    name="canDriverChatSupportMedia" />
            </div>
        </x-details.item>

        <x-buttons.primary title="{{ __('Save') }}" />
    </x-form>
</div>
