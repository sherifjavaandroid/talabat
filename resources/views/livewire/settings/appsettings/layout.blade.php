<x-form noClass="true" action="saveLayoutSettings">
    <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
        <div class="block mt-4 text-sm">
            <p>{{ __('Calculate Distance via Google Map') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableGoogleDistance" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Single-Vendor Mode') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableSingleVendor" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Chat Option') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableChat" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Order Tracking') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableOrderTracking" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Allow Prescription') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableUploadPrescription" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Proof of delivery by delivery boy') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableProofOfDelivery" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Proof type') }}</p>
            <x-select :options="['none', 'code', 'signature', 'photo']" name="orderVerificationType" />
        </div>


        <div class="block mt-4 text-sm">
            <p>{{ __('Allow vendors create drivers') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="allowVendorCreateDrivers" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Show only image on vendor types') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="showVendorTypeImageOnly" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Allow partners(Driver, Vendor) account registration') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="partnersCanRegister" :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Fetch Data By Location') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableFatchByLocation"
                description="{{ __('This can be use to enforce only data within customer location is loaded') }}"
                :defer="true" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Allow multiple-vendor ordering from customer') }}</p>
            <x-checkbox title="{{ __('Enable') }}" name="enableMultipleVendorOrder" :defer="true" />
        </div>
    </div>
    <hr class="my-4" />
    <div class='grid grid-cols-1 gap-4 mb-10 md:grid-cols-2 lg:grid-cols-3'>
        <div class="block mt-4 text-sm">
            <p>{{ __('Vendors Home Page List Count') }}</p>
            <x-input title="" name="vendorsHomePageListCount" type="number" />
        </div>
        <div class="block mt-4 text-sm">
            <p>{{ __('Products Per Page') }}</p>
            <x-input title="" name="productsPerPage" type="number" />
        </div>


    </div>
    {{-- save button --}}
    <div class="flex justify-end mt-4">
        <x-buttons.primary class="ml-4">
            {{ __('Save') }}
        </x-buttons.primary>
    </div>
</x-form>
