@section('title', __('Products'))
<div>

    <x-baseview title="{{ __('Products') }}" :showNew="true">
        <livewire:tables.product-table />
    </x-baseview>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Create Product') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            {{-- vendor --}}
            <livewire:component.autocomplete-input title="{{ __('Vendor') }}" column="name" model="Vendor"
                emitFunction="autocompleteVendorSelected" initialEmit="preselectedVendorEmit"
                disable="{{ auth()->user()->hasRole('manager') ?? 'false' }}" />
            {{--  --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <x-input title="{{ __('Name') }}" name="name" />
                </div>
                @can('order-product-visibilities')
                    <x-input title="{{ __('In Order Number') }}" name="in_order" />
                @endcan
            </div>
            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('SKU') }}" name="sku" />
                <x-input title="{{ __('Barcode') }}" name="barcode" />
            </div>

            <x-input.filepond wire:model="photos" title="{{ __('Photo(s)') }}"
                acceptedFileTypes="['image/png', 'image/jpeg', 'image/jpg']" allowImagePreview="true"
                imagePreviewMaxHeight="80" grid="3" multiple="true" allowFileSizeValidation="true"
                maxFileSize="{{ setting('filelimit.product_image_size', 200) }}kb" />
            <x-input-error message="{{ $errors->first('photos') }}" />

            <x-input.summernote name="description" title="{{ __('Description') }}" id="newContent" />

            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Price') }}" name="price" />
                <x-input title="{{ __('Discount Price') }}" name="discount_price" />
            </div>


            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Capacity') }}" name="capacity" placeholder="e.g 15" />
                <x-input title="{{ __('Unit') }}" name="unit"
                    placeholder="{{ __('Enter the unit of product. Default is kilogram(kg). e.g Kg, g, m, L') }}" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Package Count') }}" name="package_count"
                    placeholder="{{ __('Number of item per package (ex: 6, 10)') }}" />
                <x-input title="{{ __('Available Qty') }}" name="available_qty"
                    placeholder="{{ __('Number of item available qty') }}" />
            </div>


            {{-- categories --}}
            <div class="">
                <livewire:component.autocomplete-input title="{{ __('Categories') }}" column="name" model="Category"
                    customQuery="vendor_vendor_typecategories" emitFunction="autocompleteCategorySelected"
                    updateQueryClauseName="categoryQueryClasueUpdate" :clear="true" :queryClause="$categorySearchClause ?? ''"
                    onclearCalled="clearAutocompleteFieldsEvent" />

                {{-- selected categories --}}
                <x-item-chips :items="$selectedCategories ?? []" onRemove="removeSelectedCategory" />
            </div>

            {{-- show menu if the vendor doesn't use subcategories --}}
            @if ($vendor != null && !$vendor->has_sub_categories)
                {{-- menu --}}
                <x-label for="menu" title="{{ __('Menu') }}">
                    <livewire:select.vendor-menu-select name="menu_id" placeholder="{{ __('Select Menu') }}"
                        :multiple="true" :searchable="true" :depends-on="['vendor_id']" />
                    {{-- selected menu --}}
                    <x-item-chips :items="$selectedMenus ?? []" onRemove="removeSelectedMenu" />
                </x-label>
            @elseif ($vendor != null && $vendor->has_sub_categories)
                {{-- subcategories --}}
                <x-label for="subcategories" title="{{ __('Subcategories') }}">
                    <livewire:select.multiple-subcategory-select name="subcategory_id"
                        placeholder="{{ __('Select Subcategory') }}" :multiple="true" :searchable="true"
                        :depends-on="['category_id']" />
                    {{-- selected menu --}}
                    <x-item-chips :items="$selectedSubcategories ?? []" onRemove="removeSelectedSubcategory" />
                </x-label>
            @endif


            {{-- tags --}}
            <x-label for="tags" title="{{ __('Tags') }}">
                <livewire:select.tag-select name="tag_id" placeholder="{{ __('Select Tag') }}" :multiple="true"
                    :searchable="true" :depends-on="['vendor_type_id']" />
                {{-- selected Services --}}
                <x-item-chips :items="$selectedTags ?? []" onRemove="removeSelectedTag" />
            </x-label>

            <hr class="my-2" />

            <div class="grid items-center grid-cols-2 gap-4">
                <x-checkbox title="{{ __('Plus Option') }}" name="plus_option"
                    description="{{ __('Option price should be added to product price') }}" />
                <x-checkbox title="{{ __('Can be Delivered') }}" name="deliverable"
                    description="{{ __('If product can be delivered to customers') }}" />

                <x-checkbox title="{{ __('Age Restriction') }}" name="age_restricted"
                    description="{{ __('Customer will be informed they must be of legal age when buying this product') }}" />
            </div>

            {{-- digital --}}
            <div class="px-4 my-4 border rounded pb-4">
                <x-checkbox title="{{ __('Digital') }}" name="digital"
                    description="{{ __('If product is digital and can be downloaded') }}" :defer="false" />
                @if ($digital)
                    <x-input.filepond wire:model="digitalFile" allowImagePreview="false" allowFileSizeValidation="true"
                        maxFileSize="{{ setting('filelimit.max_product_digital_files_size', 2) }}mb" />
                @endif
            </div>




            {{-- options --}}
            @if (!$digital ?? false)
                <hr class="my-4" />
                <div class="border p-4 rounded-sm my-4">
                    <p class="font-semibold">{{ __('Variations/Option Groups') }}</p>
                    <div class="space-y-2 mt-4">
                        @foreach ($optionGroups ?? [] as $key => $optionGroup)
                            <div class="border rounded-sm p-4 m-2">
                                {{-- name --}}
                                <x-input title="{{ __('Name') }}" name="optionGroups.{{ $key }}.name" />
                                <div class="gap-4 grid grid-cols-2">
                                    <x-checkbox title="{{ __('Required') }}"
                                        name="optionGroups.{{ $key }}.required" />
                                    <x-checkbox title="{{ __('Multiple') }}"
                                        name="optionGroups.{{ $key }}.multiple" :defer="false" />
                                </div>
                                {{-- if multiple is true --}}
                                @if ($optionGroup['multiple'] ?? false)
                                    <x-input title="{{ __('Max Options') }}"
                                        name="optionGroups.{{ $key }}.max_options" />
                                @endif
                                {{-- options --}}
                                <div>
                                    <table class="table w-full">
                                        <tbody>
                                            @foreach ($optionGroup['options'] ?? [] as $optionKey => $optionGroupOption)
                                                <tr>
                                                    <td>
                                                        <x-input title="{{ __('Name') }}"
                                                            name="optionGroups.{{ $key }}.options.{{ $optionKey }}.name" />
                                                    </td>
                                                    <td class="px-4">
                                                        <x-input title="{{ __('Price') }}"
                                                            name="optionGroups.{{ $key }}.options.{{ $optionKey }}.price" />
                                                    </td>
                                                    <td class="my-auto">
                                                        <x-buttons.plain bgColor="my-auto bg-red-500"
                                                            wireClick="removeOption('{{ $optionKey }}', '{{ $key }}')">
                                                            <x-heroicon-o-trash class="w-5 h-5" />
                                                        </x-buttons.plain>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <x-buttons.primary title="{{ __('Add Option') }}"
                                        wireClick="newOption('{{ $key }}')" type="button" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- add button --}}
                    <x-buttons.primary title="{{ __('Add Option Group') }}" wireClick="newOptionGroup"
                        type="button" />
                </div>
                <hr class="my-4" />
            @endif
            <x-checkbox title="{{ __('Active') }}" name="isActive" />
        </x-modal-lg>
    </div>

    {{-- update form --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal-lg confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Update Product') }}</p>
            {{-- show all errors --}}
            <x-form-errors />
            {{-- vendor --}}
            <livewire:component.autocomplete-input title="{{ __('Vendor') }}" column="name" model="Vendor"
                emitFunction="autocompleteVendorSelected" initialEmit="preselectedVendorEmit"
                disable="{{ auth()->user()->hasRole('manager') ?? 'false' }}" />

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <x-input title="{{ __('Name') }}" name="name" />
                </div>
                @can('order-product-visibilities')
                    <x-input title="{{ __('In Order Number') }}" name="in_order" />
                @endcan
            </div>
            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('SKU') }}" name="sku" />
                <x-input title="{{ __('Barcode') }}" name="barcode" />
            </div>

            <x-input.filepond wire:model="photos" title="{{ __('Photo(s)') }}" id="editProductInput"
                allowAddFileEvent="true" acceptedFileTypes="['image/png', 'image/jpeg', 'image/jpg']"
                allowImagePreview multiple="true" allowFileSizeValidation imagePreviewMaxHeight="80"
                maxFileSize="{{ setting('filelimit.product_image_size', 200) }}kb" />

            <x-input.summernote name="description" title="{{ __('Description') }}" id="editContent" />

            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Price') }}" name="price" />
                <x-input title="{{ __('Discount Price') }}" name="discount_price" />
            </div>


            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Capacity') }}" name="capacity" placeholder="e.g 15" />
                <x-input title="{{ __('Unit') }}" name="unit"
                    placeholder="{{ __('Enter the unit of product. Default is kilogram(kg). e.g Kg, g, m, L') }}" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-input title="{{ __('Package Count') }}" name="package_count"
                    placeholder="{{ __('Number of item per package (ex: 6, 10)') }}" />
                <x-input title="{{ __('Available Qty') }}" name="available_qty"
                    placeholder="{{ __('Number of item available qty') }}" />
            </div>

            {{-- categories --}}
            <div class="">
                <livewire:component.autocomplete-input title="{{ __('Categories') }}" column="name"
                    model="Category" customQuery="vendor_vendor_typecategories"
                    emitFunction="autocompleteCategorySelected" updateQueryClauseName="categoryQueryClasueUpdate"
                    :clear="true" :queryClause="$categorySearchClause ?? ''" onclearCalled="clearAutocompleteFieldsEvent" />

                {{-- selected categories --}}
                <x-item-chips :items="$selectedCategories ?? []" onRemove="removeSelectedCategory" />
            </div>

            {{-- show menu if the vendor doesn't use subcategories --}}
            @if ($vendor != null && !$vendor->has_sub_categories)
                {{-- menu --}}
                <x-label for="menu" title="{{ __('Menu') }}">
                    <livewire:select.vendor-menu-select name="menu_id" placeholder="{{ __('Select Menu') }}"
                        :multiple="true" :searchable="true" :depends-on="['vendor_id']" />
                    {{-- selected menu --}}
                    <x-item-chips :items="$selectedMenus ?? []" onRemove="removeSelectedMenu" />
                </x-label>
            @elseif ($vendor != null && $vendor->has_sub_categories)
                {{-- subcategories --}}
                <x-label for="subcategories" title="{{ __('Subcategories') }}">
                    <livewire:select.multiple-subcategory-select name="subcategory_id"
                        placeholder="{{ __('Select Subcategory') }}" :multiple="true" :searchable="true"
                        :depends-on="['category_id']" />
                    {{-- selected menu --}}
                    <x-item-chips :items="$selectedSubcategories ?? []" onRemove="removeSelectedSubcategory" />
                </x-label>
            @endif

            {{-- tags --}}
            <x-label for="tags" title="{{ __('Tags') }}">
                <livewire:select.tag-select name="tag_id" placeholder="{{ __('Select Tag') }}" :multiple="true"
                    :searchable="true" :depends-on="['vendor_type_id']" />
                {{-- selected Services --}}
                <x-item-chips :items="$selectedTags ?? []" onRemove="removeSelectedTag" />
            </x-label>

            <hr class="my-2" />

            <div class="grid items-center grid-cols-2 gap-4">
                <x-checkbox title="{{ __('Plus Option') }}" name="plus_option"
                    description="{{ __('Option price should be added to product price') }}" />
                <x-checkbox title="{{ __('Can be Delivered') }}" name="deliverable"
                    description="{{ __('If product can be delivered to customers') }}" />

                <x-checkbox title="{{ __('Age Restriction') }}" name="age_restricted"
                    description="{{ __('Customer will be informed they must be of legal age when buying this product') }}" />

            </div>
            <div class="p-2 border rounded-sm">
                <x-checkbox title="{{ __('Digital') }}" name="digital"
                    description="{{ __('If product is digital and can be downloaded') }}" :defer="false" />
                @if ($digital)
                    <x-input.filepond wire:model="digitalFile" allowImagePreview="false"
                        allowFileSizeValidation="true"
                        maxFileSize="{{ setting('filelimit.max_product_digital_files_size', 2) }}mb" />
                @endif
            </div>



            {{-- options --}}
            @if (!$digital ?? false)
                <hr class="my-4" />
                <div class="border p-4 rounded-sm my-4">
                    <p class="font-semibold">{{ __('Variations/Option Groups') }}</p>
                    <div class="space-y-2 mt-4">
                        @foreach ($optionGroups ?? [] as $key => $optionGroup)
                            <div class="border rounded-sm p-4 m-2">
                                {{-- name --}}
                                <x-input title="{{ __('Name') }}" name="optionGroups.{{ $key }}.name" />
                                <div class="gap-4 grid grid-cols-2">
                                    <x-checkbox title="{{ __('Required') }}"
                                        name="optionGroups.{{ $key }}.required" />
                                    <x-checkbox title="{{ __('Multiple') }}"
                                        name="optionGroups.{{ $key }}.multiple" :defer="false" />
                                </div>
                                {{-- if multiple is true --}}
                                @if ($optionGroup['multiple'] ?? false)
                                    <x-input title="{{ __('Max Options') }}"
                                        name="optionGroups.{{ $key }}.max_options" />
                                @endif
                                {{-- options --}}
                                <div>
                                    <table class="table w-full">
                                        <tbody>
                                            @foreach ($optionGroup['options'] ?? [] as $optionKey => $optionGroupOption)
                                                <tr>
                                                    <td>
                                                        <x-input title="{{ __('Name') }}"
                                                            name="optionGroups.{{ $key }}.options.{{ $optionKey }}.name" />
                                                    </td>
                                                    <td class="px-4">
                                                        <x-input title="{{ __('Price') }}"
                                                            name="optionGroups.{{ $key }}.options.{{ $optionKey }}.price" />
                                                    </td>
                                                    <td class="my-auto">
                                                        <x-buttons.plain bgColor="my-auto bg-red-500"
                                                            wireClick="removeOption('{{ $optionKey }}', '{{ $key }}')">
                                                            <x-heroicon-o-trash class="w-5 h-5" />
                                                        </x-buttons.plain>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <x-buttons.primary title="{{ __('Add Option') }}"
                                        wireClick="newOption('{{ $key }}')" type="button" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- add button --}}
                    <x-buttons.primary title="{{ __('Add Option Group') }}" wireClick="newOptionGroup"
                        type="button" />
                </div>
                <hr class="my-4" />
            @endif

            <x-checkbox title="{{ __('Active') }}" name="isActive" />

        </x-modal-lg>
    </div>

    {{-- Assign Subcategories --}}
    <div x-data="{ open: @entangle('showAssignSubcategories') }">
        <x-modal confirmText="{{ __('Add') }}" action="assignSubcategories">
            <p class="text-xl font-semibold">{{ __('Assign To Sub-categories') }}</p>
            <p class="text-sm text-gray-500">
                {{ __('Note: Only sub-categories of the assigned product categories will be listed here') }}</>
            <div class="grid grid-cols-1 lg:grid-cols-2">
                @foreach ($subCategories as $subCategory)
                    <x-checkbox title="{{ $subCategory->name }}({{ $subCategory->category->name }})"
                        name="subCategoriesIDs" value="{{ $subCategory->id }}" />
                @endforeach
            </div>

        </x-modal>
    </div>

    {{-- Assign menus --}}
    <div x-data="{ open: @entangle('showAssign') }">
        <x-modal confirmText="{{ __('Add') }}" action="assignMenus">
            <p class="text-xl font-semibold">{{ __('Add to Menus') }}</p>
            <p class="text-sm text-gray-500">
                {{ __('Note: Menus of selected vendor for product will be listed here') }}</>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($menus as $menu)
                    <x-checkbox title="{{ $menu->name }}" name="menusIDs" value="{{ $menu->id }}" />
                @endforeach
            </div>

        </x-modal>
    </div>

    {{-- details modal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-lg>

            <p class="text-xl font-semibold">{{ $selectedModel->name ?? '' }} {{ __('Details') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Name') }}" text="{{ $selectedModel->name ?? '' }}" />
                <x-details.item title="{{ __('SKU') }}" text="{{ $selectedModel->sku ?? '' }}" />
            </div>
            <x-details.item title="{{ __('Description') }}" text="">
                {!! $selectedModel->description ?? '' !!}
            </x-details.item>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Price') }}"
                    text="{{ currencyFormat($selectedModel->price ?? '') }}" />
                <x-details.item title="{{ __('Discount Price') }}"
                    text="{{ currencyFormat($selectedModel->discount_price ?? '') }}" />


                {{-- <x-details.item title="" text="" /> --}}
                <x-details.item title="{{ __('Capacity') }}" text="{{ $selectedModel->capacity ?? '' }}" />
                <x-details.item title="{{ __('Unit') }}" text="{{ $selectedModel->unit ?? '' }}" />


                <x-details.item title="{{ __('Package Count') }}"
                    text="{{ $selectedModel->package_count ?? '0' }}" />
                <x-details.item title="{{ __('Available Qty') }}"
                    text="{{ $selectedModel->available_qty ?? '' }}" />


                <x-details.item title="{{ __('Vendor') }}" text="{{ $selectedModel->vendor->name ?? '' }}" />
                <x-details.item title="{{ __('Menus') }}" text="">
                    @if ($selectedModel != null)
                        {{ implode(', ', $selectedModel->menus()->pluck('name')->toArray()) }}
                    @endif
                </x-details.item>



            </div>
            <x-details.item title="{{ __('Photos') }}" text="">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($selectedModel->photos ?? [] as $photo)
                        <a href="{{ $photo }}" target="_blank"><img src="{{ $photo }}"
                                class="w-24 h-24 mx-2 rounded-sm" /></a>
                    @endforeach
                </div>
            </x-details.item>
            {{-- list option groups and options --}}
            @if ($selectedModel->has_options ?? false)
                <div>
                    <hr class="my-4" />
                    {{-- <p class="font-medium my-2">{{ __('Option Groups') }}</p> --}}
                    <table class="w-full table-auto border-collapse border border-slate-500">
                        <thead class="bg-slate-400">
                            <tr class="bg-slate-400">
                                <th class="p-2 border border-slate-400 bg-gray-100">{{ __('Option Group') }}</th>
                                <th class="p-2 border border-slate-400 bg-gray-100 w-20">{{ __('Active') }}</th>
                                <th class="p-2 border border-slate-400 bg-gray-100">{{ __('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedModel->option_groups ?? [] as $optionGroup)
                                <tr>
                                    <td class="p-2 border border-slate-400">{{ $optionGroup->name }}</td>
                                    <td class="p-2 border border-slate-400">
                                        @if ($optionGroup->is_active)
                                            <x-table.check />
                                        @else
                                            <x-table.close />
                                        @endif
                                    </td>
                                    <td class="p-2 border border-slate-400 wrap space-x-2">
                                        @foreach ($optionGroup->options ?? [] as $option)
                                            <span class="rounded-full bg-gray-200 px-2 text-sm">
                                                {{ $option->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($selectedModel->digital ?? false)
                <hr class="my-4" />
                <x-details.item title="{{ __('File') }}" text="">
                    <div class="space-y-3">
                        @foreach ($selectedModel->digital_files ?? [] as $file)
                            <div class="flex items-center p-2 border rounded">
                                <div class="w-full text-wrap">
                                    <p>{{ $file->name }}</p>
                                    <p>
                                        <span class="text-xs font-thin text-primary-400">
                                            {{ $file->size }} bytes
                                        </span>
                                    </p>
                                </div>

                                <a href="{{ $file->link }}" target="_blank"
                                    class="font-medium hover:underline text-primary-500 hover:text-primary-800 hover:font-bold">
                                    {{ __('Download') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </x-details.item>
            @endif
            <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">

                <div>
                    <x-label title="{{ __('Status') }}" />
                    <x-table.active :model="$selectedModel" />
                </div>

                <div>
                    <x-label title="{{ __('Plus Option') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->plus_option ?? false }}" />
                </div>

                <div>
                    <x-label title="{{ __('Available for Delivery') }}" />
                    <x-table.bool isTrue="{{ $selectedModel->deliverable ?? false }}" />
                </div>

            </div>

            <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">





            </div>

        </x-modal-lg>
    </div>


    {{-- timing form --}}
    <div x-data="{ open: @entangle('showDayAssignment') }">
        @include('livewire.product-timing')
    </div>
</div>
