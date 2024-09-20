@section('title', __('Vendors'))
<div>

    <x-baseview title="{{ __('Vendors') }}" :showNew="\Auth::user()->hasAnyRole('admin|city-admin')">
        <livewire:tables.vendor-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Create Vendor') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            <div class="flex space-x-2">
                <div class="grow">
                    <x-input title="{{ __('Name') }}" name="name" />
                </div>
                <div class="flex-none">
                    <x-input title="{{ __('In Order Number') }}" name="in_order" />
                </div>
            </div>

            {{-- vendor type --}}
            <x-select title="{{ __('Vendor Type') }}" :options='$vendorTypes ?? []' name="vendor_type_id" :defer="false" />
            {{-- <x-input title="{{ __('Description') }}" name="description" /> --}}
            <x-input.summernote name="description" title="{{ __('Description') }}" id="newContent" />


            <div class="grid grid-cols-2 gap-4">
                {{-- <x-input title="{{ __('Phone') }}" name="phone" /> --}}
                <x-phoneselector />
                <x-input title="{{ __('Email') }}" name="email" />
            </div>

            <div class="p-2 mt-4 bg-gray-100 border border-gray-300 rounded">
                <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address" />
                <x-input-error message="{{ $errors->first('address') }}" />

                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Latitude') }}" name="latitude" />
                    <x-input title="{{ __('Longitude') }}" name="longitude" />
                </div>
                {{-- delivery zones --}}
                <div class="{{ !$isPackageVendor ? 'block' : 'hidden' }} grid items-center grid-cols-1 gap-4">
                    <x-select2 title="{{ __('Delivery Zone') }}" :options="$deliveryZones ?? []" name="deliveryZonesIDs"
                        id="deliveryZonesSelect2" :multiple="true" width="100" :ignore="true" />
                </div>
            </div>



            {{-- categories --}}
            <livewire:component.autocomplete-input title="{{ __('Categories') }}" column="name" model="Category"
                emitFunction="autocompleteCategorySelected" updateQueryClauseName="categoryQueryClasueUpdate"
                :clear="true" :queryClause="$categorySearchClause" onclearCalled="clearAutocompleteFieldsEvent" />

            {{-- selected categories --}}
            <x-item-chips :items="$selectedCategories ?? []" onRemove="removeSelectedCategory" />
            <x-input-error message="{{ $errors->first('categories') }}" />

            {{-- sub-categories --}}
            <div class="{{ !$isServiceVendor ? 'block' : 'hidden' }} grid items-center grid-cols-1 gap-4">
                <x-checkbox title="{{ __('Has Sub-Categories') }}" name="has_sub_categories"
                    description="{{ __('This will allow products to be attached to sub-categories') }}"
                    :defer="false" />
            </div>

            <hr class="mt-5" />

            <div class="{{ !$isPackageVendor ? 'block' : 'hidden' }}">
                <div class="{{ !$isServiceVendor ? 'block' : 'hidden' }} grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Minimum Order Amount') }}" name="min_order" />
                    <x-input title="{{ __('Maximum Order Amount') }}" name="max_order" />
                </div>
                @showDeliveryFeeSetting
                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Base Delivery Fee') }}" name="base_delivery_fee" />
                    <x-input title="{{ __('Delivery Fee') }}" name="delivery_fee" />
                </div>
                @endshowDeliveryFeeSetting
                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Delivery Range(KM)') }}" name="delivery_range" />
                    <x-checkbox title="{{ __('Charge per KM') }}" name="charge_per_km"
                        description="{{ __('Delivery fee will be per KM') }}" />
                </div>
                <div class="{{ !$isServiceVendor ? 'block' : 'hidden' }} grid items-center grid-cols-2 gap-4">
                    <x-checkbox title="{{ __('Pickup') }}" name="pickup"
                        description="{{ __('Allows pickup orders') }}" />
                    <x-checkbox title="{{ __('Delivery') }}" name="delivery"
                        description="{{ __('Allows delivery orders') }}" />
                </div>

                <hr class="mt-5" />
            </div>
            <div class="grid items-center grid-cols-2 gap-4">
                <x-checkbox title="{{ __('Schedule Order') }}" name="allow_schedule_order"
                    description="{{ __('Allows customer to schedule orders') }}" />
                @if (!$isServiceVendor)
                    <x-checkbox title="{{ __('Order Auto Assignment') }}" name="auto_assignment"
                        description="{{ __('System will automatic assign order to delivery boy') }}" />
                @endif

                <x-checkbox title="{{ __('Auto Accept Order') }}" name="auto_accept"
                    description="{{ __('System will automatic change pending order to preparing') }}" />
            </div>
            <hr class="mt-4" />
            <div class="grid grid-cols-2 gap-4">
                <x-input-group>
                    <x-slot name="label">
                        <x-label title="{{ __('Prepare Time') }}" />
                    </x-slot>
                    <x-slot name="input">
                        <x-input name="prepare_time" placeholder="{{ __('10') }}" />
                    </x-slot>
                    <x-slot name="append">
                        <x-select-plain name="prepare_time_unit" :options="$timeUnits ?? []" />
                    </x-slot>
                </x-input-group>

                <x-input-group>
                    <x-slot name="label">
                        <x-label title="{{ __('Delivery Time') }}" />
                    </x-slot>
                    <x-slot name="input">
                        <x-input name="delivery_time" placeholder="{{ __('15') }}" />
                    </x-slot>
                    <x-slot name="append">
                        <x-select-plain name="delivery_time_unit" :options="$timeUnits ?? []" />
                    </x-slot>
                </x-input-group>

            </div>
            <hr class="mt-4" />
            <div class="grid grid-cols-2 gap-4">
                <x-checkbox title="{{ __('Has Own Drivers') }}" name="has_drivers" />
                <x-checkbox title="{{ __('Use Subscription') }}" name="use_subscription" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('System Commission(%)') }}" name="commission" {{--  disable="{{ $use_subscription ? true:false }}" --}} />
                <x-input title="{{ __('Tax') }}" name="tax" />
            </div>
            {{--  fees  --}}
            <x-select2 title="{{ __('Fees') }}" :options="$fees ?? []" name="feesIDs" id="feesSelect2"
                :multiple="true" width="100" :ignore="true" />
            <hr class="mt-4" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-media-upload title="{{ __('Logo') }}" name="photo" :photo="$photo" :photoInfo="$photoInfo"
                    types="PNG or JPEG" rules="image/*" />

                <x-media-upload title="{{ __('Featured Image') }}" name="secondPhoto" :photo="$secondPhoto"
                    :photoInfo="$secondPhotoInfo" types="PNG or JPEG" rules="image/*" />
            </div>
            {{-- document changing --}}
            @can('modify-vendor-document')
                <div class="border-t border-b py-6 my-2">
                    <x-label title="{{ __('Documents') }}" />
                    <x-input.filepond wire:model="documents" name="documents" multiple="true"
                        acceptedFileTypes="['image/*','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document']"
                        allowImagePreview="false" allowFileSizeValidation="true" maxFileSize="10mb" />
                </div>
            @endcan

            @hasanyrole('city-admin|admin')
                <x-checkbox title="{{ __('Active') }}" name="isActive" />
                <x-checkbox title="{{ __('Featured') }}" name="featured" />
            @endhasanyrole

            <x-form-errors />

        </x-modal-lg>
    </div>

    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Update Vendor') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            <div class="flex space-x-2">
                <div class="grow">
                    @hasanyrole('city-admin|admin')
                        <x-input title="{{ __('Name') }}" name="name" />
                    @else
                        <x-label title="{{ __('Name') }}">
                            <p class="font-medium text-base">{{ $selectedModel->name ?? '' }} </p>
                        </x-label>
                    @endhasanyrole
                </div>
                <div class="flex-none">
                    @hasanyrole('city-admin|admin')
                        <x-input title="{{ __('In Order Number') }}" name="in_order" :disable="true" />
                    @endhasanyrole
                </div>
            </div>

            @hasanyrole('city-admin|admin')
                {{-- vendor type --}}
                <x-select title="{{ __('Vendor Type') }}" :options='$vendorTypes ?? []' name="vendor_type_id" :defer="false" />
            @else
                <x-label title="{{ __('Vendor Type') }}">
                    <p class="font-medium text-base">{{ $selectedModel->vendor_type->name ?? '' }} </p>
                </x-label>
            @endhasanyrole
            {{-- <x-input title="{{ __('Description') }}" name="description" /> --}}
            <x-input.summernote name="description" title="{{ __('Description') }}" id="editContent" />

            <div class="grid grid-cols-2 gap-4">
                {{-- <x-input title="{{ __('Phone') }}" name="phone" /> --}}
                <x-phoneselector inputId="phoneEdit" />
                <x-input title="{{ __('Email') }}" name="email" />
            </div>


            <div class="p-2 mt-4 bg-gray-100 border border-gray-300 rounded">
                @hasanyrole('city-admin|admin')
                    <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address"
                        address="{{ $address ?? '' }}" />
                    <x-input-error message="{{ $errors->first('address') }}" />
                    <div class="grid grid-cols-2 gap-4">
                        <x-input title="{{ __('Latitude') }}" name="latitude" />
                        <x-input title="{{ __('Longitude') }}" name="longitude" />
                    </div>
                @else
                    <x-label title="{{ __('Address') }}">
                        <p class="font-medium text-base">{{ $selectedModel->address ?? '' }} </p>
                    </x-label>
                    <div class="grid grid-cols-2 gap-4">
                        <x-label title="{{ __('Latitude') }}">
                            <p class="font-medium text-base">{{ $selectedModel->latitude ?? '' }} </p>
                        </x-label>
                        <x-label title="{{ __('Longitude') }}">
                            <p class="font-medium text-base">{{ $selectedModel->longitude ?? '' }} </p>
                        </x-label>
                    </div>
                @endhasanyrole
                {{-- delivery zones --}}
                <div class="{{ !$isPackageVendor ? 'block' : 'hidden' }} grid items-center grid-cols-1 gap-4">
                    <x-select2 title="{{ __('Delivery Zone') }}" :options="$deliveryZones ?? []" name="deliveryZonesIDs"
                        id="editDeliveryZonesSelect2" :multiple="true" width="100" :ignore="true" />
                </div>
            </div>


            {{-- categories --}}
            <livewire:component.autocomplete-input title="{{ __('Categories') }}" column="name" model="Category"
                emitFunction="autocompleteCategorySelected" updateQueryClauseName="categoryQueryClasueUpdate"
                :clear="true" :queryClause="$categorySearchClause" onclearCalled="clearAutocompleteFieldsEvent" />

            {{-- selected categories --}}
            <x-item-chips :items="$selectedCategories ?? []" onRemove="removeSelectedCategory" />
            <x-input-error message="{{ $errors->first('categories') }}" />


            {{-- sub-categories --}}
            <div class="{{ !$isServiceVendor ? 'block' : 'hidden' }} grid items-center grid-cols-1 gap-4">
                <x-checkbox title="{{ __('Has Sub-Categories') }}" name="has_sub_categories"
                    description="{{ __('This will allow products to be attached to sub-categories') }}"
                    :defer="false" />
            </div>
            <hr class="mt-5" />
            <div class="{{ !$isPackageVendor ? 'block' : 'hidden' }}">
                <div class="{{ !$isServiceVendor ? 'block' : 'hidden' }} grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Minimum Order Amount') }}" name="min_order" />
                    <x-input title="{{ __('Maximum Order Amount') }}" name="max_order" />
                </div>
                @showDeliveryFeeSetting
                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Base Delivery Fee') }}" name="base_delivery_fee" />
                    <x-input title="{{ __('Delivery Fee') }}" name="delivery_fee" />
                </div>
                @endshowDeliveryFeeSetting
                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('Delivery Range(KM)') }}" name="delivery_range" />
                    @showDeliveryFeeSetting
                    <x-checkbox title="{{ __('Charge per KM') }}" name="charge_per_km"
                        description="{{ __('Delivery fee will be per KM') }}" />
                    @endshowDeliveryFeeSetting
                </div>
                <div class="{{ !$isServiceVendor ? 'block' : 'hidden' }} grid items-center grid-cols-2 gap-4">
                    <x-checkbox title="{{ __('Pickup') }}" name="pickup"
                        description="{{ __('Allows pickup orders') }}" />
                    <x-checkbox title="{{ __('Delivery') }}" name="delivery"
                        description="{{ __('Allows delivery orders') }}" />
                </div>

                <hr class="mt-5" />
            </div>
            <div class="grid items-center grid-cols-2 gap-4">
                <x-checkbox title="{{ __('Schedule Order') }}" name="allow_schedule_order"
                    description="{{ __('Allows customer to schedule orders') }}" />
                @if (!$isServiceVendor)
                    <x-checkbox title="{{ __('Order Auto Assignment') }}" name="auto_assignment"
                        description="{{ __('System will automatic assign order to delivery boy') }}" />
                @endif

                <x-checkbox title="{{ __('Auto Accept Order') }}" name="auto_accept"
                    description="{{ __('System will automatic change pending order to preparing') }}" />
            </div>
            <hr class="mt-4" />
            <div class="grid grid-cols-2 gap-4">
                <x-input-group>
                    <x-slot name="label">
                        <x-label title="{{ __('Prepare Time') }}" />
                    </x-slot>
                    <x-slot name="input">
                        <x-input name="prepare_time" placeholder="{{ __('10') }}" />
                    </x-slot>
                    <x-slot name="append">
                        <x-select-plain name="prepare_time_unit" :options="$timeUnits ?? []" />
                    </x-slot>
                </x-input-group>

                <x-input-group>
                    <x-slot name="label">
                        <x-label title="{{ __('Delivery Time') }}" />
                    </x-slot>
                    <x-slot name="input">
                        <x-input name="delivery_time" placeholder="{{ __('10') }}" />
                    </x-slot>
                    <x-slot name="append">
                        <x-select-plain name="delivery_time_unit" :options="$timeUnits ?? []" />
                    </x-slot>
                </x-input-group>

            </div>

            @role('admin')
                <hr class="mt-4" />
                <div class="grid grid-cols-2 gap-4">
                    <x-checkbox title="{{ __('Has Own Drivers') }}" name="has_drivers" />
                    <x-checkbox title="{{ __('Use Subscription') }}" name="use_subscription" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <x-input title="{{ __('System Commission(%)') }}" name="commission" {{--  disable="{{ $use_subscription ? true:false }}" --}} />
                    <x-input title="{{ __('Tax') }}" name="tax" />
                </div>
                {{--  fees  --}}
                <x-select2 title="{{ __('Fees') }}" :options="$fees ?? []" name="feesIDs" id="editFeesSelect2"
                    :multiple="true" width="100" :ignore="true" />
                <hr class="mt-4" />
            @endrole

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <x-media-upload title="{{ __('Logo') }}" name="photo"
                    preview="{{ $selectedModel->logo ?? '' }}" :photo="$photo" :photoInfo="$photoInfo"
                    types="PNG or JPEG" rules="image/*" />

                <x-media-upload title="{{ __('Featured Image') }}" name="secondPhoto"
                    preview="{{ $selectedModel->feature_image ?? '' }}" :photo="$secondPhoto" :photoInfo="$secondPhotoInfo"
                    types="PNG or JPEG" rules="image/*" />

            </div>
            {{-- document changing --}}
            @can('modify-vendor-document')
                <div class="border-t border-b py-6 my-2">
                    <x-label title="{{ __('Documents') }}" />
                    <x-input.filepond wire:model="documents" name="documents" multiple="true"
                        acceptedFileTypes="['image/*','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document']"
                        allowImagePreview="false" allowFileSizeValidation="true" maxFileSize="10mb" />
                </div>
            @endcan

            <hr />
            @hasanyrole('city-admin|admin')
                <x-checkbox title="{{ __('Active') }}" name="isActive" />
                <x-checkbox title="{{ __('Featured') }}" name="featured" />
            @endhasanyrole

            <x-form-errors />

        </x-modal-lg>
    </div>

    {{-- assign form --}}
    <div x-data="{ open: @entangle('showAssign') }">
        <x-modal confirmText="{{ __('Assign') }}" action="assignManagers" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Assign Managers To Vendor') }}</p>
            <x-select2 title="{{ __('Managers') }}" :options="$managers ?? []" name="managersIDs" id="managersSelect2"
                :multiple="true" width="100" :ignore="true" />

        </x-modal>
    </div>

    {{-- timing form --}}
    <div x-data="{ open: @entangle('showDayAssignment') }">
        @include('livewire.vendor_timing')
    </div>

    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-lg>

            <p class="text-xl font-semibold">
                {{ $selectedModel != null ? $selectedModel->name : '' }}
                {{ __('Details') }}
            </p>
            {{--  --}}
            <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
            <div class="grid grid-cols-1 gap-4 mt-4 border-t border-b pb-4 md:grid-cols-2 ">
                {{-- logo --}}
                <div>
                    <x-label title="{{ __('Logo') }}" />
                    <img src="{{ $selectedModel->logo ?? '' }}" class="h-24 rounded-sm object-cover" />
                </div>
                {{-- featured image --}}
                <div>
                    <x-label title="{{ __('Featured Image') }}" />
                    <img src="{{ $selectedModel->feature_image ?? '' }}" class="h-24 rounded-sm object-cover" />
                </div>
            </div>
            <x-details.item title="{{ __('Description') }}">
                <div>
                    {!! $selectedModel->description ?? '' !!}
                </div>
            </x-details.item>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <x-details.item title="{{ __('Phone') }}" text="{{ $selectedModel->phone ?? '' }}" />
                <x-details.item title="{{ __('Email') }}" text="{{ $selectedModel->email ?? '' }}" />
            </div>
            <x-details.item title="{{ __('Address') }}" text="{{ $selectedModel->address ?? '' }}" />
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Latitude') }}" text="{{ $selectedModel->latitude ?? '' }}" />
                <x-details.item title="{{ __('Longitude') }}" text="{{ $selectedModel->longitude ?? '' }}" />

            </div>
            {{-- categories --}}
            <x-details.item title="{{ __('Categories') }}" text="">
                {{ $selectedModel != null ? implode(', ', $selectedModel->categories()->pluck('name')->toArray()) : '' }}
            </x-details.item>
            <div class="grid grid-cols-1 gap-4 mt-4 border-t md:grid-cols-2 ">
                <x-details.item title="{{ __('Prepare Time') }}"
                    text="{{ $selectedModel->prepare_time ?? '-' }} {{ $selectedModel->prepare_time_unit ?? __('minutes') }}" />
                <x-details.item title="{{ __('Delivery Time') }}"
                    text="{{ $selectedModel->delivery_time ?? '-' }} {{ $selectedModel->delivery_time_unit ?? __('minutes') }}" />

            </div>
            <div class="grid grid-cols-1 gap-4 mt-4 border-t md:grid-cols-2 ">
                <x-details.item title="{{ __('Tax') }}" text="{{ $selectedModel->tax ?? '0' }}%" />
                <x-details.item title="{{ __('Commission') }}" text="{{ $selectedModel->commission ?? '0' }}%" />

            </div>

            @if ($selectedModel ? ($selectedModel->vendor_type ? $selectedModel->vendor_type->slug == 'parcel' : false) : true)
                <div class="grid grid-cols-1 gap-4 mt-4 border-t md:grid-cols-2 ">
                    <x-details.item title="{{ __('Delivery Fee') }}"
                        text="{{ $selectedModel->delivery_fee ?? '' }}" />
                    <x-details.item title="{{ __('Delivery Range') }}"
                        text="{{ $selectedModel->delivery_range ?? '0' }} KM" />
                </div>
                <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <x-label title="{{ __('Status') }}" />
                        <x-table.active :model="$selectedModel" />
                    </div>

                    <div>
                        <x-label title="{{ __('Available for Pickup') }}" />
                        <x-table.bool isTrue="{{ $selectedModel->pickup ?? false }}" />
                    </div>

                    <div>
                        <x-label title="{{ __('Available for Delivery') }}" />
                        <x-table.bool isTrue="{{ $selectedModel->delivery ?? false }}" />
                    </div>

                    <div>
                        <x-label title="{{ __('Open') }}" />
                        <x-table.bool isTrue="{{ $selectedModel->is_open ?? false }}" />
                    </div>
                    <div>
                        <x-label title="{{ __('Featured') }}" />
                        <x-table.bool isTrue="{{ $selectedModel->featured ?? false }}" />
                    </div>

                </div>
            @endif

            <hr class="my-4" />
            <p class="font-semibold text-sm mb-2">{{ __('Documents') }}</p>
            @if (!empty($selectedModel->documents ?? []))
                <div class="mt-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if ($selectedModel != null)
                        @foreach ($selectedModel->getMedia('documents') ?? [] as $document)
                            @if (isMediaImage($document))
                                <a href="{{ $document->getUrl() }}" target="_blank">
                                    <img src="{{ $document->getUrl() }}"
                                        class="h-24 rounded-sm object-cover w-full" />
                                </a>
                            @else
                                <a href="{{ $document->getUrl() }}" target="_blank">
                                    <div class="h-24 border rounded overflow-hidden p-2 border-primary-500">
                                        <p class="line-clamp-1">
                                            <span class='font-bold'>{{ __('Name') }}</span>:
                                            {{ $document->file_name }}
                                        </p>
                                        <p>
                                            <span class='font-bold'>{{ __('Size') }}</span>:
                                            {{ $document->human_readable_size }}
                                        </p>
                                        <p class="text-sm italic text-primary-500 hover:underline">
                                            {{ __('Click to download/preview') }}
                                        </p>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif
            <div class="my-2">
                @if ($selectedModel != null && $selectedModel->document_requested)
                    <div class="border p-2 text-center rounded text-sm space-y-2 py-4">
                        <p>{{ __('Driver has been notified to upload documents') }}</p>
                        {{-- cancel request button --}}
                        <div class="flex justify-center">
                            <x-buttons.plain wireClick="cancelDocumentRequest">
                                {{ __('Cancel Request') }}
                            </x-buttons.plain>
                        </div>
                    </div>
                @else
                    <div class="flex justify-end w-full md:w-6/12 lg:w-4/12 mx-auto">
                        <x-buttons.primary wireClick="requestDocuments">
                            {{ __('Request Documents') }}
                        </x-buttons.primary>
                    </div>
                @endif
            </div>

        </x-modal-lg>
    </div>
</div>
@include('layouts.partials.phoneselector')
