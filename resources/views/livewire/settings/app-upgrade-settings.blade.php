@section('title', __('Mobile App Upgrade Settings'))
<div>

    <x-baseview title="{{ __('Mobile App Upgrade Settings') }}">

        <x-form action="save">

            <div class="">
                <p class="text-2xl">{{ __("Customer App") }}</p>
                <div class='grid grid-cols-2 gap-4 md:grid-cols-3'>
                    {{--  <x-input title="{{ __('Android Build Number') }}" name="customer.android" />
                    <x-input title="{{ __('iOS Build Number') }}" name="customer.ios" />  --}}
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Force App Upgrade') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="customer.force" :defer="true" />
                    </div>
                </div>

                <p class="mt-4 text-2xl">{{ __("Driver App") }}</p>
                <div class='grid grid-cols-2 gap-4 md:grid-cols-3'>
                    {{--  <x-input title="{{ __('Android Build Number') }}" name="driver.android" />
                    <x-input title="{{ __('iOS Build Number') }}" name="driver.ios" />  --}}
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Force App Upgrade') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="driver.force" :defer="true" />
                    </div>
                </div>

                <p class="mt-4 text-2xl">{{ __("Vendor App") }}</p>
                <div class='grid grid-cols-2 gap-4 md:grid-cols-3'>
                    {{--  <x-input title="{{ __('Android Build Number') }}" name="vendor.android" />
                    <x-input title="{{ __('iOS Build Number') }}" name="vendor.ios" />  --}}
                    <div class="block mt-4 text-sm">
                        <p>{{ __('Force App Upgrade') }}</p>
                        <x-checkbox title="{{ __('Enable') }}" name="vendor.force" :defer="true" />
                    </div>
                </div>
                
                <x-buttons.primary title="{{ __('Save Changes') }}" />
                <div>
        </x-form>

    </x-baseview>

</div>
