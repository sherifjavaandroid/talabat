@section('title', __('Modules'))
<div>

    <x-baseview title="{{ __('Modules') }}" :showNew="true">
        <livewire:tables.vendor-type-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('New') }}" action="save" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('New Module') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            <x-select title="{{ __('Type') }}" :options='$types' name="slug" :defer="false" />
            <x-select2 title="{{ __('Delivery Zone') }}" :options="$deliveryZones ?? []" name="deliveryZonesIDs"
                id="deliveryZonesSelect2" :multiple="true" width="100" :ignore="true" />
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Color') }}" name="color" type="color" class="h-10" />
            <x-input title="{{ __('Description') }}" name="description" />


            <x-media-upload title="{{ __('Logo') }}" name="photo" preview="{{ $selectedModel->logo ?? '' }}"
                :photo="$photo" :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <x-media-upload title="{{ __('Website Header image') }}" name="secondPhoto"
                preview="{{ $selectedModel->website_header ?? '' }}" :photo="$secondPhoto" :photoInfo="$secondPhotoInfo"
                types="PNG or JPEG" rules="image/*" />

            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />
            <x-form-errors />

        </x-modal>
    </div>
    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">

            <p class="text-xl font-semibold">{{ __('Update Module') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            <x-select2 title="{{ __('Delivery Zone') }}" :options="$deliveryZones ?? []" name="deliveryZonesIDs"
                id="editDeliveryZonesSelect2" :multiple="true" width="100" :ignore="true" />
            <x-input title="{{ __('Name') }}" name="name" />
            <x-input title="{{ __('Color') }}" name="color" type="color" class="h-10" />
            <x-input title="{{ __('Description') }}" name="description" />


            <x-media-upload title="{{ __('Logo') }}" name="photo" preview="{{ $selectedModel->logo ?? '' }}"
                :photo="$photo" :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <x-media-upload title="{{ __('Website Header image') }}" name="secondPhoto"
                preview="{{ $selectedModel->website_header ?? '' }}" :photo="$secondPhoto" :photoInfo="$secondPhotoInfo"
                types="PNG or JPEG" rules="image/*" />

            <x-checkbox title="{{ __('Active') }}" name="isActive" :defer="false" />
            <x-form-errors />

        </x-modal>
    </div>


    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal>

            <p class="text-xl font-semibold">
                {{ $selectedModel != null ? $selectedModel->name : '' }}
                {{ __('Details') }}
            </p>
            {{--  --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
                <x-details.item title="{{ __('Color') }}">
                    <div class='h-8 rounded-sm flex items-center justify-center w-32'
                        style="background-color: {{ $selectedModel->color ?? '' }}">{{ $selectedModel->color ?? '' }}
                    </div>
                </x-details.item>
            </div>
            <x-details.item title="{{ __('Description') }}">
                <div>
                    {!! $selectedModel->description ?? '' !!}
                </div>
            </x-details.item>

            <x-details.item title="{{ __('Delivery Zones') }}"
                text="{{ $selectedModel->delivery_zone_names ?? '' }}" />

            {{-- img --}}
            <x-details.item title="{{ __('Logo') }}">
                <img src="{{ $selectedModel->logo ?? '' }}" alt="{{ $selectedModel->name ?? '' }}"
                    class="w-32 h-32 object-cover rounded-md" />
            </x-details.item>

            <div>
                <x-label title="{{ __('Status') }}" />
                <x-table.active :model="$selectedModel" />
            </div>

        </x-modal>
    </div>

</div>
