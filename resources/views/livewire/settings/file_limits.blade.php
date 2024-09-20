<div>

    @php
        $kbHint = __('Note: In kilobytes');
        $mbHint = __('Note: In megabytes');
    @endphp


    <x-form action="saveFileLimits" :noClass="true">
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Banner') }} {{ __('File Size') }}" name="banner_limit" type="number"
                hint="{{ $kbHint }}" />
            <x-input title="{{ __('Vendor Type') }} {{ __('File Size') }}" name="vendor_type_limit" type="number"
                hint="{{ $kbHint }}" />
            <x-input title="{{ __('Package Type') }} {{ __('File Size') }}" name="package_type_limit" type="number"
                hint="{{ $kbHint }}" />
        </div>
        {{-- vendor  --}}
        <p class="mt-4 font-bold">{{ __('Vendor') }}</p>
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Logo') }} {{ __('File Size') }}" name="vendor_logo_limit" type="number"
                hint="{{ $kbHint }}" />
            <x-input title="{{ __('Featured Image') }} {{ __('File Size') }}" name="vendor_feature_limit"
                type="number" hint="{{ $kbHint }}" />
        </div>
        <p class="mt-4 font-bold">{{ __('Category') }}</p>
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Category Image File Size') }}" name="category_limit" type="number"
                hint="{{ $kbHint }}" />
            <x-input title="{{ __('Sub-Category Image File Size') }}" name="sub_category_limit" type="number"
                hint="{{ $kbHint }}" />
        </div>
        {{-- product  --}}
        <p class="mt-4 font-bold">{{ __('Product') }}</p>
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Max Images') }}" name="max_product_images_limit" type="number" />
            <x-input title="{{ __('Images File Size') }}" name="product_image_size_limit" type="number"
                hint="{{ $kbHint }}" />
        </div>
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Digital Files Size') }}" name="max_product_digital_files_size" type="number"
                hint="{{ $mbHint }}" />
        </div>

        {{-- services  --}}
        <p class="mt-4 font-bold">{{ __('Service/Booking') }}</p>
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Max Service/Booking Images') }}" name="max_service_images_limit" type="number" />
            <x-input title="{{ __('Service/Booking Images') }} {{ __('File Size') }}" name="service_image_size_limit"
                type="number" hint="{{ $kbHint }}" />
        </div>

        {{-- prescription  --}}
        <p class="mt-4 font-bold">{{ __('Prescription') }}</p>
        <div class="grid grid-cols-1 gap-1 md:gap-4 md:grid-cols-2">
            <x-input title="{{ __('Max Prescription Images') }}" name="prescription_file_limit" type="number" />
            <x-input title="{{ __('Prescription Images') }} {{ __('File Size') }}" name="prescription_file_size_limit"
                type="number" hint="{{ $kbHint }}" />
        </div>
        {{-- --}}
        <div class="my-4">
            <x-buttons.primary title="{{ __('Save Changes') }}" />
        </div>
    </x-form>

</div>
