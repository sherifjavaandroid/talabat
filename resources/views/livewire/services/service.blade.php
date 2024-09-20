@section('title', __('Services/Booking'))
<div>

    <x-baseview title="{{ __('Services/Booking') }}" :showNew="true">
        <livewire:tables.service-table />
    </x-baseview>
    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Create Services/Booking') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            @role('manager')
            @else
                <livewire:component.autocomplete-input title="{{ __('Vendor') }}" column="name" model="Vendor"
                    errorMessage="{{ $errors->first('vendor_id') }}" :queryClause="$vendorSearchClause"
                    emitFunction="autocompleteVendorSelected" :extraQueryData="['service', 'book']" customQuery="vendor_type" />
                <x-input-error message="{{ $errors->first('vendor_id') }}" />
            @endrole

            <livewire:component.autocomplete-input title="{{ __('Category') }}" column="name" model="Category"
                errorMessage="{{ $errors->first('category_id') }}" emitFunction="autocompleteCategorySelected"
                customQuery="vendor_type_service" :extraQueryData="['service', 'book']" />
            <x-input-error message="{{ $errors->first('category_id') }}" />

            <x-select title="{{ __('Subcategory') }}" name="subcategory_id" :options="$subcategories" :noPreSelect="true" />

            <x-input title="{{ __('Name') }}" name="name" />
            <x-input.summernote name="description" title="{{ __('Description') }}" id="newContent" />

            {{-- photos --}}
            <livewire:component.multiple-media-upload title="{{ __('Images') }}" name="photos" types="PNG or JPEG"
                fileTypes="image/*" emitFunction="photoSelected" max="{{ setting('filelimit.max_service_images', 3) }}"
                maxSize="{{ setting('filelimit.service_image_size', 200) }}" />

            <x-select title="{{ __('Duration Type') }}" :options="$durationTypes" name="duration" :defer="false" />

            <div class="grid grid-cols-2 space-x-2">
                <x-input title="{{ __('Price') }}" name="price" />
                <x-input title="{{ __('Discount Price') }}" name="discount_price" />
            </div>
            <div class="grid grid-cols-2 space-x-2">
                <x-checkbox title="{{ __('Location Required') }}" name="location" :defer="false" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
            </div>

            <x-checkbox title="{{ __('Age Restriction') }}" name="age_restricted"
                description="{{ __('Customer will be informed they must be of legal age when buying this service') }}"
                :defer="false" />

        </x-modal-lg>
    </div>

    {{-- edit service --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Update') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Edit Services/Booking') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            @role('manager')
            @else
                <livewire:component.autocomplete-input title="{{ __('Vendor') }}" column="name" model="Vendor"
                    errorMessage="{{ $errors->first('vendor_id') }}" :queryClause="$vendorSearchClause"
                    emitFunction="autocompleteVendorSelected" initialEmit="preselectedVendorEmit" :extraQueryData="['service', 'book']"
                    customQuery="vendor_type" />
                <x-input-error message="{{ $errors->first('vendor_id') }}" />
            @endrole



            <livewire:component.autocomplete-input title="{{ __('Category') }}" column="name" model="Category"
                errorMessage="{{ $errors->first('category_id') }}" emitFunction="autocompleteCategorySelected"
                initialEmit="preselectedCategoryEmit" customQuery="vendor_type_service" :extraQueryData="['service', 'book']" />
            <x-input-error message="{{ $errors->first('category_id') }}" />

            <x-select title="{{ __('Subcategory') }}" name="subcategory_id" :options="$subcategories" :noPreSelect="true" />

            <x-input title="{{ __('Name') }}" name="name" />
            <x-input.summernote name="description" title="{{ __('Description') }}" id="editContent" />
            {{-- photos --}}
            <livewire:component.multiple-media-upload title="{{ __('Images') }}" name="photos" types="PNG or JPEG"
                fileTypes="image/*" emitFunction="photoSelected" max="{{ setting('filelimit.max_service_images', 3) }}"
                maxSize="{{ setting('filelimit.service_image_size', 200) }}" />

            <x-select title="{{ __('Duration Type') }}" :options="$durationTypes" name="duration" :defer="false" />
            <div class="grid grid-cols-2 space-x-2">
                <x-input title="{{ __('Price') }}" name="price" />
                <x-input title="{{ __('Discount Price') }}" name="discount_price" />
            </div>
            <div class="grid grid-cols-2 space-x-2">
                <x-checkbox title="{{ __('Location Required') }}" name="location" :defer="false" />
                <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
            </div>

            <x-checkbox title="{{ __('Age Restriction') }}" name="age_restricted"
                description="{{ __('Customer will be informed they must be of legal age when buying this service') }}"
                :defer="false" />

        </x-modal-lg>
    </div>

    {{-- show service details --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-lg>
            <p class="text-xl font-semibold">{{ __('Services/Booking') }} {{ __('Details') }}</p>
            <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
            <x-details.item title="{{ __('Description') }}">
                {!! $selectedModel->description ?? '' !!}
            </x-details.item>
            <div class="grid grid-cols-2 space-x-2">
                <x-details.item title="{{ __('Vendor') }}" text="{{ $selectedModel->vendor->name ?? '' }}" />
                <x-details.item title="{{ __('Category') }}" text="{{ $selectedModel->category->name ?? '' }}" />
            </div>
            <div class="grid grid-cols-2 space-x-2">
                <x-details.item title="{{ __('Price') }}"
                    text="{{ currencyFormat($selectedModel->price ?? '') }}" />
                <x-details.item title="{{ __('Discount') }}" text="{{ $selectedModel->discount_price ?? '' }}" />
            </div>
            <div class="grid grid-cols-2 space-x-2">
                <div>
                    <x-label title="{{ __('Location Required') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->location ?? false }}" />
                </div>
                <div>
                    <x-label title="{{ __('Active') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->is_active ?? false }}" />
                </div>
                <div>
                    <x-label title="{{ __('Per Hour') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->per_hour ?? false }}" />
                </div>

            </div>
            <x-label title="{{ __('Images') }}" />
            <div class="grid grid-cols-2 space-x-2">
                @foreach ($selectedModel != null ? $selectedModel->getMedia() : [] as $photo)
                    <a href="{{ $photo->getFullUrl() }}" target="_blank">{{ $photo }}</a>
                @endforeach

            </div>

        </x-modal-lg>

    </div>


</div>
