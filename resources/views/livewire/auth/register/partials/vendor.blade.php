<form wire:submit.prevent="signUp">
    @csrf

    {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> --}}
    <div class="">
        <div>
            <h1 class="mb-4 font-semibold text-gray-700 text-md">
                {{ __('Business Information') }} </h1>
            <x-input title="{{ __('Business Name') }}" name="vendor_name" placeholder="" />
            {{-- vendor type --}}
            <x-select title="{{ __('Vendor Type') }}" :options='$vendorTypes ?? []' name="vendor_type_id" :defer="false" />
            <div class="p-2 mt-4 bg-gray-100 border border-gray-300 rounded">
                <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address" />
                <x-input-error message="{{ $errors->first('address') }}" />

                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Latitude') }}" name="latitude" />
                    <x-input title="{{ __('Longitude') }}" name="longitude" />
                </div>

            </div>
            <div class="grid grid-cols-1 gap-0 md:gap-4 md:grid-cols-2">
                <x-input title="{{ __('Email') }}" name="vendor_email" placeholder="info@mail.com" />
                <x-phoneselector model="vendor_phone" />
            </div>

            {{-- documents  --}}
            <hr class="my-4" />
            <p class="font-light">{{ __('Documents') }}</p>
            <div>
                {!! setting('page.settings.vendorDocumentInstructions', '') !!}
            </div>
            <livewire:component.multiple-media-upload types="PNG or JPEG" fileTypes="image/*"
                emitFunction="vendorDocumentsUploaded" max="{{ setting('page.settings.vendorDocumentCount', 3) }}" />
            <x-input-error message="{{ $errors->first('vendorDocuments') }}" />
        </div>
        {{-- divider  --}}
        <hr class="my-4 " />
        {{-- personal info --}}
        <div>
            <h1 class="mb-4 font-semibold text-gray-700 text-md">
                {{ __('Personal Information') }} </h1>
            <x-input title="{{ __('Name') }}" name="name" placeholder="John" />
            <div class="grid grid-cols-2 space-x-4">
                <x-input title="{{ __('Email') }}" name="email" placeholder="info@mail.com" />
                <x-phoneselector />
            </div>
            <x-input title="{{ __('Login Password') }}" name="password" type="password"
                placeholder="**********************" />

        </div>
    </div>

    <hr class="my-4" />
    <div class="justify-center flex my-2 mt-8">
        <div class="flex items-center">
            <x-checkbox name="agreedVendor" :defer="false" :noMargin="true"> <span>{{ __('I agree with') }}
                    <a href="{{ route('terms') }}" target="_blank"
                        class="font-bold text-primary-500 hover:underline">{{ __('terms and conditions') }}</a></span>
            </x-checkbox>
        </div>
    </div>

    <x-form-errors />

    <x-buttons.primary title="{{ __('Sign Up') }}" />
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            //WHEN THE IFRAME HAS LOADED
            $('iframe').ready(function() {
                //timeout to allow iframe to load
                setTimeout(function() {
                    //SET THE HEIGHT OF THE IFRAME
                    var height = $('#iframe').contents().find('body').height();
                    //if its zero
                    if (height > 30) {
                        height += 30;
                    }
                    $('#iframe').height(height);
                }, 100);
            });
        });
    </script>
@endpush
