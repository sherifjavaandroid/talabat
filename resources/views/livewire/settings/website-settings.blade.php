@section('title', __('Website Settings'))
<div wire:init="loadContents">

    <x-baseview title="{{ __('Website Settings') }}">

        <x-form action="saveAppSettings">


            <div class="space-y-8">
                <x-input name="websiteHeaderTitle" title="{{ __('Website Header Title') }}" />
                <x-textarea name="websiteHeaderSubtitle" title="{{ __('Website Header Subtitle') }}" />
                <x-textarea name="websiteFooterBrief" title="{{ __('Website Footer Brief') }}" />

                <hr class="my-4" />

                {{-- features --}}
                <div>
                    <p class="font-semibold">{{ __('Feature Section') }}</p>
                    <x-input name="websiteFeatureTitle" title="{{ __('Website Feature Title') }}" />
                    <x-textarea name="websiteFeatureSubtitle" title="{{ __('Website Feature Subtitle') }}" />
                    {{-- features --}}
                    <div class="mt-4">
                        <p class="font-semibold mb-2">{{ __('Feature List') }}</p>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                            @foreach ($features ?? [] as $key => $feature)
                                <div class="border p-4 rounded">
                                    <x-input name="features.{{ $key }}.title" title="{{ __('Title') }}" />
                                    <x-textarea name="features.{{ $key }}.description"
                                        title="{{ __('Description') }}" />
                                    <x-input type="file" name="features.{{ $key }}.image"
                                        title="{{ __('Image') }}" />
                                </div>
                            @endforeach
                        </div>
                        @empty($features ?? [])
                            <div
                                class="rounded border-2 border-dotted p-12 border-gray-300 flex justify-center items-center text-center">
                                <p>{{ __("No Feature listed yet. Click on the 'Add Feature' button below to add a new feature") }}
                                </p>
                            </div>
                        @endempty
                        {{-- add btn --}}
                        <x-buttons.primary title="{{ __('Add Feature') }}" wireClick="addFeature()" type="button" />

                    </div>
                </div>


                <hr class="my-4" />
                {{-- join us --}}
                <div>
                    <p class="font-semibold">{{ __('Join Us Section') }}</p>
                    <x-input name="websiteDriverJoinTitle" title="{{ __('Driver Join Title') }}" />
                    <x-input.summernote name="websiteDriverJoinDescription" id="websiteDriverJoinDescription"
                        title="{{ __('Driver Join Description') }}" />
                    <x-input type="file" name="websiteDriverJoinImage" title="{{ __('Image') }}" />
                    <hr class="my-4 mx-40" />
                    <x-input name="websiteVendorJoinTitle" title="{{ __('Seller/Vendor Join Title') }}" />
                    <x-input.summernote name="websiteVendorJoinDescription" id="websiteVendorJoinDescription"
                        title="{{ __('Seller/Vendor Join Description') }}" />
                    <x-input type="file" name="websiteVendorJoinImage" title="{{ __('Image') }}" />

                </div>


                <hr class="my-4" />
                {{-- about us --}}
                <div>
                    <p class="font-semibold">{{ __('About Us Section') }}</p>
                    <x-input.summernote name="websiteAboutUs" id="websiteAboutUs" title="{{ __('About Us') }}" />
                    <x-input type="file" name="websiteAboutUsImage" title="{{ __('Image') }}" />
                </div>
                <hr class="my-4" />
                {{-- contact us --}}
                <div>
                    <p class="font-semibold">{{ __('Contact Us Section') }}</p>
                    <x-input.summernote name="websiteContactUs" id="websiteContactUs" title="{{ __('Contact Us') }}" />
                    <x-input type="file" name="websiteContactUsImage" title="{{ __('Image') }}" />
                </div>
                <hr class="my-4" />

                {{-- images --}}
                <div>
                    <p>{{ __('Images') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="border rounded-sm px-4 py-1">
                            {{-- logo --}}
                            <div class="flex items-center space-x-10 mx-auto my-auto">

                                <img src="{{ $websiteHeaderImage != null ? $websiteHeaderImage->temporaryUrl() : $oldWebsiteHeaderImage }}"
                                    class="w-24 h-24 rounded" />

                                <x-input title="{{ __('Website Home Image') }}" name="websiteHeaderImage"
                                    :defer="false" type="file" />
                            </div>

                        </div>
                        <div class="border rounded-sm px-4 py-1">
                            {{-- intro image --}}
                            <div class="flex items-center space-x-10 mx-auto my-auto">

                                <img src="{{ $websiteIntroImage != null ? $websiteIntroImage->temporaryUrl() : $oldWebsiteIntroImage }}"
                                    class="w-24 h-24 rounded" />

                                <x-input title="{{ __('Website Intro Image') }}" name="websiteIntroImage"
                                    :defer="false" type="file" />
                            </div>

                        </div>
                        <div class="border rounded-sm px-4 py-1">
                            {{-- footer image --}}
                            <div class="flex items-center space-x-10 mx-auto my-auto">

                                <img src="{{ $websiteFooterImage != null ? $websiteFooterImage->temporaryUrl() : $oldWebsiteFooterImage }}"
                                    class="w-24 h-24 rounded" />

                                <x-input title="{{ __('Website Footer Image') }}" name="websiteFooterImage"
                                    :defer="false" type="file" />
                            </div>

                        </div>
                    </div>
                </div>

                {{-- social links --}}
                <div>
                    <p class="font-semibold">{{ __('Social Media Links') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <x-input name="fbLink" title="{{ __('Facebook Link') }}" />
                        <x-input name="igLink" title="{{ __('Instagram Link') }}" />
                        <x-input name="twLink" title="{{ __('Twitter Link') }}" />
                        <x-input name="yuLink" title="{{ __('Youtube Link') }}" />
                    </div>
                </div>
            </div>


            <x-buttons.primary title="{{ __('Save Changes') }}" />

        </x-form>

    </x-baseview>

</div>
