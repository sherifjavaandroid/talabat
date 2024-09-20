<div>
    <x-form noClass="true" action="saveGeneralSettings">
        <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
            <x-input title="{{ __('App Name') }}" name="appName" />
            <div></div>


            {{-- country code --}}
            <div>
                <x-input title="{{ __('Country Code') }}" name="appCountryCode" />
                <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank"
                    class="mt-1 text-xs text-gray-500 underline">{{ __('List Of Country Codes') }}</a>
                <p class="text-sm text-gray-500">
                    {{ __('Note: For example if you want to allow phone from Ghana you enter GH') }}
                </p>
            </div>
            {{-- custom php regex phone number --}}
            <div>
                <x-input title="{{ __('Custom Phone Number Regex') }}" name="customPhoneNumberRegex" />
                <p class="text-sm text-gray-500">
                    {{ __('Note: This is for advance usage, in cases where your valid country phone number is showing invalid on the system') }}
                </p>
            </div>
        </div>

        <hr class="my-4" />


        <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
            <div class="block mt-4 text-sm">
                <p>{{ __('Multiple Stops(Parcel Delivery)') }}</p>
                <x-checkbox title="{{ __('Enable') }}" name="enableParcelMultipleStops" :defer="true" />
            </div>
            <x-input title="{{ __('Max Stops(Parcel Delivery)') }}" name="maxParcelStops" type="number" />
            {{-- clear firebase --}}
            <div class="block mt-4 text-sm">
                <p>{{ __('Clear Firebase after order') }}</p>
                <x-checkbox title="{{ __('Enable') }}" name="clearFirestore" :defer="true" />
                <p class="text-xs text-gray-500">
                    {{ __('Note: This is to reduce the size of your firebase firestore, by removing completed or failed orders from the firebase firestore') }}
                </p>
            </div>
            <div class="block mt-4 text-sm">
                <p>{{ __('Numeric Order Code') }}</p>
                <x-checkbox title="{{ __('Enable') }}" name="enableNumericOrderCode" :defer="true" />
            </div>
        </div>
        <div class='border-t border-b py-4 my-4 grid grid-cols-1 gap-4 md:grid-cols-2'>
            <div class="block text-sm">
                <p>{{ __('Allow Profile Update') }}</p>
                <x-checkbox title="{{ __('Enable') }}" name="enableProfileUpdate" :defer="true" />
            </div>
        </div>

        <div class='grid grid-cols-1 gap-4 p-4 mt-4 border rounded shadow md:grid-cols-2 '>
            <x-input title="{{ __('Android App Download Link') }}" name="androidDownloadLink" />
            <x-input title="{{ __('iOS App Download Link') }}" name="iosDownloadLink" />
        </div>

        {{-- save button --}}
        <div class="flex justify-end mt-4">
            <x-buttons.primary class="ml-4">
                {{ __('Save') }}
            </x-buttons.primary>
        </div>
    </x-form>
</div>
