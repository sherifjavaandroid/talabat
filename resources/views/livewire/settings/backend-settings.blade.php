<div>

    <x-form action="saveAppSettings" :noClass="true">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="">

                <x-input title="{{ __('Backend Name') }}" name="websiteName" />
                <x-input title="{{ __('Backend Route Path') }}" name="backend_prefix">
                    <x-slot name="hint">
                        {{-- {{ __('Note: This is the route path that will be used to access the backend') }} --}}
                        {{-- exmaple --}}
                        {{-- <br> --}}
                        <span class="text-sm text-gray-500">
                            {{ __('Example: If you enter') }} "admin" {{ __('the backend will be accessed via') }}
                            <span class="text-blue-500">{{ url('') }}/admin</span>
                        </span>

                    </x-slot>
                </x-input>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    {{-- language selector --}}
                    <div>
                        <x-select title="{{ __('Language') }}" :options='$languages' name="locale"
                            selected="{{ $locale }}" :defer="true" />
                        <p class="text-sm text-gray-500">
                            {{ __('Note: Notifications, mails etc will be sent in this selected language') }}</p>
                    </div>
                    {{-- timeZone --}}
                    <div>
                        <x-select title="{{ __('Timezone') }}" name="timeZone" :options="DateTimeZone::listIdentifiers(DateTimeZone::ALL)" />
                    </div>
                </div>
                <div>
                    <x-input title="{{ __('Website Color') }}" name="websiteColor" type="color" class="h-10" />
                    <p class="text-sm text-gray-500">
                        {{ __('Note: You will need to refresh the page for the color to take effect') }}</p>
                </div>


                <div>
                    <x-input title="{{ __('Phone Country Code (Country of operations)') }}" name="countryCode" />
                    <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank"
                        class="mt-1 text-xs text-gray-500 underline">{{ __('List Of Country Codes') }}</a>
                    <p class="text-sm text-gray-500">
                        {{ __('Note: For example if you want to allow phone from Ghana you enter') }}
                        GH</p>
                    <p class="text-sm text-gray-500">
                        {{ __('Note: if you want to allow phone from any country enter') }}
                        AUTO
                    </p>
                </div>


            </div>
            <div>
                {{-- logo --}}
                <div class="flex items-center mt-5 space-x-10">
                    <img src="{{ $websiteLogo != null ? $websiteLogo->temporaryUrl() : $oldWebsiteLogo }}"
                        class="w-24 h-24 rounded" />
                    <x-input title="{{ __('Website Logo') }}" name="websiteLogo" :defer="false" type="file" />
                </div>

                {{-- favicon --}}
                <div class="flex items-center mt-5 space-x-10">
                    <img src="{{ $favicon != null ? $favicon->temporaryUrl() : $oldFavicon }}"
                        class="w-24 h-24 rounded" />
                    <x-input title="{{ __('Website Favicon') }}" name="favicon" :defer="false" type="file" />
                </div>

                {{-- loginImage --}}
                <div class="flex items-center mt-5 space-x-10">
                    <img src="{{ $loginImage != null ? $loginImage->temporaryUrl() : $oldLoginImage }}"
                        class="w-24 h-24 rounded" />
                    <x-input title="{{ __('Login Image') }}" name="loginImage" :defer="false" type="file" />
                </div>

                {{-- registerImage --}}
                <div class="flex items-center my-5 space-x-10">
                    <img src="{{ $registerImage != null ? $registerImage->temporaryUrl() : $oldRegisterImage }}"
                        class="w-24 h-24 rounded" />
                    <x-input title="{{ __('Register Image') }}" name="registerImage" :defer="false"
                        type="file" />
                </div>

            </div>
        </div>
        <x-buttons.primary title="{{ __('Save Changes') }}" />
    </x-form>

</div>
