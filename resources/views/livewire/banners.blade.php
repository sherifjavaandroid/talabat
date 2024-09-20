@section('title', __('Banners'))
<div>


    <x-baseview title="{{ __('Banners') }}" :showNew="inProduction()">
        <livewire:tables.banner-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('Create Banner') }}</p>
            <x-media-upload title="{{ __('Photo') }}" name="photo" :photo="$photo" :photoInfo="$photoInfo"
                types="PNG or JPEG" rules="image/*" />
            <hr class="my-2">

            {{-- visibility --}}
            <x-select title="{{ __('Section/Page Visible') }}" :options='$vendorTypes' name="vendor_type_id" />
            {{-- type --}}
            <x-select title="{{ __('Type') }}" :options='$types' name="type" :noPreSelect="true"
                :defer="false" />

            {{-- link --}}
            <div class="{{ $type == 'link' ? 'block' : 'hidden' }}">
                <x-input title="{{ __('External Link') }}" name="link" placeholder="" />
            </div>
            {{-- vendors --}}
            <div class="{{ $type == 'vendor' ? 'block' : 'hidden' }}">
                <x-label for="vendor_id" title="{{ __('Vendor') }}">
                    <livewire:select.vendor-select name="vendor_id" placeholder="{{ __('Search vendor') }}"
                        :searchable="true" />
                </x-label>
                <x-input-error message="{{ $errors->first('vendor_id') }}" />
            </div>
            {{-- category --}}
            <div class="{{ $type == 'category' ? 'block' : 'hidden' }}">
                <x-label for="category_id" title="{{ __('Category') }}">
                    <livewire:select.category-select name="category_id" placeholder="{{ __('Search category') }}"
                        :searchable="true" />
                </x-label>
                <x-input-error message="{{ $errors->first('category_id') }}" />
            </div>
            {{-- product --}}
            <div class="{{ $type == 'product' ? 'block' : 'hidden' }}">
                <x-label for="product_id" title="{{ __('Product') }}">
                    <livewire:select.product-select name="product_id" placeholder="{{ __('Search product') }}"
                        :searchable="true" />
                </x-label>
                <x-input-error message="{{ $errors->first('product_id') }}" />
            </div>

            <hr class="my-2">
            <x-checkbox title="{{ __('Active') }}" name="isActive" />
            <x-checkbox title="{{ __('Featured') }}"
                description="{{ __('Can featured on home screen of customer app') }}" name="featured" />

            <x-form-errors />

        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Banner') }}</p>
            <x-media-upload title="{{ __('Photo') }}" name="photo" preview="{{ $selectedModel->photo ?? '' }}"
                :photo="$photo" :photoInfo="$photoInfo" types="PNG or JPEG" rules="image/*" />
            <hr class="my-2">
            {{-- visibility --}}
            <x-select title="{{ __('Section/Page Visible') }}" :options='$vendorTypes' name="vendor_type_id" />
            {{-- type --}}
            <x-select title="{{ __('Type') }}" :options='$types' name="type" :noPreSelect="true"
                :defer="false" />

            {{-- link --}}
            <div class="{{ $type == 'link' ? 'block' : 'hidden' }}">
                <x-input title="{{ __('External Link') }}" name="link" placeholder="" />
            </div>
            {{-- vendors --}}
            <div class="{{ $type == 'vendor' ? 'block' : 'hidden' }}">
                <x-label for="vendor_id" title="{{ __('Vendor') }}">
                    <livewire:select.vendor-select name="vendor_id" placeholder="{{ __('Search vendor') }}"
                        :searchable="true" />
                </x-label>
                <x-input-error message="{{ $errors->first('vendor_id') }}" />
            </div>
            {{-- category --}}
            <div class="{{ $type == 'category' ? 'block' : 'hidden' }}">
                <x-label for="category_id" title="{{ __('Category') }}">
                    <livewire:select.category-select name="category_id" placeholder="{{ __('Search category') }}"
                        :searchable="true" />
                </x-label>
                <x-input-error message="{{ $errors->first('category_id') }}" />
            </div>
            {{-- product --}}
            <div class="{{ $type == 'product' ? 'block' : 'hidden' }}">
                <x-label for="product_id" title="{{ __('Product') }}">
                    <livewire:select.product-select name="product_id" placeholder="{{ __('Search product') }}"
                        :searchable="true" />
                </x-label>
                <x-input-error message="{{ $errors->first('product_id') }}" />
            </div>

            <hr class="my-2">
            <x-checkbox title="{{ __('Active') }}" name="isActive" />
            <x-checkbox title="{{ __('Featured') }}"
                description="{{ __('Can featured on home screen of customer app') }}" name="featured" />

            <x-form-errors />


        </x-modal>
    </div>
</div>
