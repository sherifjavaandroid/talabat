@section('title', __('Map Settings'))
<div>

    <x-baseview title="{{ __('Map Settings') }}">

        <x-form action="saveAppSettings">

            <div class="">

                <div class='grid grid-cols-1 gap-4 md:grid-cols-2'>
                    {{-- geoapifyApiKey --}}
                    <div>
                        <x-input title="Geoapify Api Key" name="geoapifyApiKey" />
                        <a href="https://www.geoapify.com/maps-api/" target="_blank"
                            class="mt-1 text-xs text-gray-500 underline">
                            {{ __('Get api key') }}
                        </a>
                        {{-- hint --}}
                        <p class="mt-1 text-xs">
                            {{ __('Geoapify is used to get country, state/province, city geographical boundaries to help in package ordering area of operation and stop matching') }}
                        </p>
                    </div>
                </div>
                <hr class="my-4" />

                <div class='grid grid-cols-1 gap-4 md:grid-cols-2 '>
                    <div>
                        <x-select title="{{ __('Geocoder/Place Search Platform') }}" :options="$geocoders"
                            name="geocoderType" :noPreSelect="true" />
                    </div>

                    <div>
                        <p class="mt-1 font-light">{{ __('Continue using gooecoding from the app') }}</p>
                        <x-checkbox title="{{ __('Enabled') }}" name="useGoogleOnApp"
                            description="{{ __('Irrespective of the platform selected, if enabled the system will keep using google for gecoding and place search on the app') }}" />
                    </div>
                    <div>
                        <x-input title="{{ __('Filter place Search by Country') }}" name="placeFilterCountryCodes"
                            placeholder="e.g US,NG,IN" />
                    </div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div>
                        <x-input title="Google Map Key" name="googleMapKey" />
                        <p class="mt-1 text-xs">Insert google maps key <br />
                            ( <a href="https://console.developers.google.com/apis/dashboard" target="_blank"
                                class="underline text-primary-500">https://console.developers.google.com/apis/dashboard</a>
                            )
                        </p>
                    </div>
                    <div>
                        <x-input title="Opencage Api Key" name="opencageApiKey" />
                        <p class="mt-1 text-xs">Opencage Api Key<br />
                            ( <a href="https://opencagedata.com/api" target="_blank"
                                class="underline text-primary-500">https://opencagedata.com/api</a>
                            )
                        </p>
                    </div>
                    <div>
                        <x-input title="Radar Api Key" name="radarApiKey" />
                        <p class="mt-1 text-xs">Radar Api Key<br />
                            ( <a href="https://radar.com/documentation/api#authentication" target="_blank"
                                class="underline text-primary-500">https://radar.com/documentation/api#authentication</a>
                            )
                        </p>
                    </div>
                    <div>
                        <x-input title="Locationiq Api Access Token" name="locationiqApiKey" />
                        <p class="mt-1 text-xs">Locationiq Api Access Token<br />
                            ( <a href="https://locationiq.com/" target="_blank"
                                class="underline text-primary-500">https://locationiq.com/</a>
                            )
                        </p>
                    </div>



                </div>

                {{-- What3words --}}
                <hr class="my-4" />
                <div>
                    <x-input title="What3words Api Key" name="what3wordsApiKey" />
                    <a href="https://what3words.com/select-plan?currency=USD" target="_blank"
                        class="mt-1 text-xs text-gray-500 underline">Get api key</a>
                </div>
                <x-buttons.primary title="{{ __('Save Changes') }}" />
            </div>
        </x-form>

    </x-baseview>

</div>
