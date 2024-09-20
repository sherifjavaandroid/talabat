@section('title', __('Vendor Driver Settings'))
<div>
    <x-baseview title="{{ __('Vendor Driver Settings') }}">
        <div class="w-full md:w-6/12 lg:w-4/12 border rounded shadow bg-white px-10 py-4 pb-8">
            <x-form noClass="true" action="saveDriverSettings">

                <div class="block mt-4 text-sm">
                    <x-input title="{{ __('Driver order search radius') }}(KM)" name="driverSearchRadius" type="number" />
                </div>

                <div class="block mt-4 text-sm">
                    <x-input title="{{ __('Driver Max Acceptable Order') }}" name="maxDriverOrderAtOnce" type="number" />
                </div>
                <div class="block mt-4 text-sm">
                    <x-input title="{{ __('Number of driver to be notified of new order') }}"
                        name="maxDriverOrderNotificationAtOnce" type="number" />
                </div>

                {{-- save button --}}
                <div class="flex justify-end mt-4">
                    <x-buttons.primary class="ml-4">
                        {{ __('Save') }}
                    </x-buttons.primary>

                </div>
            </x-form>
        </div>
    </x-baseview>
</div>
