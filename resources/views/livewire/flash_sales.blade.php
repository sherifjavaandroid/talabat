@section('title', __('Flash Sales'))
<div>

    <x-baseview title="{{ __('Flash Sales') }}" :showNew="true">
        <livewire:tables.flash-sale-table />
    </x-baseview>

    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('New Flash Sale') }}</p>
            <x-input name="title" title="{{ __('Title') }}" />
            {{-- vendor type  --}}
            <x-label for="vendor_type_id" title="{{ __('Vendor Type') }}">
                <livewire:select.sales-vendor-type-select name="vendor_type_id" />
            </x-label>

            <x-label for="product_id" title="{{ __('Vendor') }}">
                <livewire:select.vendor-select name="vendor_id" placeholder="{{ __('Search vendor') }}"
                    :searchable="true" :depends-on="['vendor_type_id']" />
            </x-label>

            {{-- search bar  --}}
            {{-- <x-autocomplete-input title="{{ __('Items/Products') }}" placeholder="{{ __('Enter search keyword') }}"
                :dataList="$searchResult" name="search_input" emitFunction="productSelected" /> --}}
            <x-label for="product_id" title="{{ __('Items/Products') }}">
                <livewire:select.multiple-product-select name="product_id" placeholder="{{ __('Search products') }}"
                    :multiple="true" :searchable="true" :depends-on="['vendor_type_id', 'vendor_id']" />
            </x-label>
            {{-- items  --}}
            @if (!empty($selectedProducts))
                <div class="p-2 my-2 border rounded">
                    <div class="mb-2 text-2xl font-bold">
                        {{ __('Selected Items/Products') }}
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($selectedProducts as $key => $selectedProduct)
                            <div class="flex items-center px-4 py-2 border rounded-sm shadow-sm">
                                <p class="w-full">{{ $selectedProduct->name ?? '' }}</p>
                                <x-buttons.plain h="h-8" bgColor="bg-red-400"
                                    wireClick="removeItem('{{ $key }}')">
                                    <x-heroicon-o-x class="w-4 h-4" />
                                </x-buttons.plain>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <x-input type="datetime-local" name="expires_at" title="{{ __('Expires At') }}" />


            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
        </x-modal>
    </div>

    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Edit Flash Sale') }}</p>
            <x-input name="title" title="{{ __('Title') }}" />
            {{-- vendor type  --}}
            <x-label for="vendor_type_id" title="{{ __('Vendor Type') }}">
                <livewire:select.sales-vendor-type-select name="vendor_type_id" />
            </x-label>

            <x-label for="product_id" title="{{ __('Vendor') }}">
                <livewire:select.vendor-select name="vendor_id" placeholder="{{ __('Search vendor') }}"
                    :searchable="true" :depends-on="['vendor_type_id']" />
            </x-label>

            {{-- search bar  --}}
            {{-- <x-autocomplete-input title="{{ __('Items/Products') }}" placeholder="{{ __('Enter search keyword') }}"
                :dataList="$searchResult" name="search_input" emitFunction="productSelected" /> --}}
            <x-label for="product_id" title="{{ __('Items/Products') }}">
                <livewire:select.multiple-product-select name="product_id" placeholder="{{ __('Search products') }}"
                    :multiple="true" :searchable="true" :depends-on="['vendor_type_id', 'vendor_id']" />
            </x-label>
            {{-- items  --}}
            @if (!empty($selectedProducts))
                <div class="p-2 my-2 border rounded">
                    <div class="mb-2 text-2xl font-bold">
                        {{ __('Selected Items/Products') }}
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($selectedProducts as $key => $selectedProduct)
                            <div class="flex items-center px-4 py-2 border rounded-sm shadow-sm">
                                <p class="w-full">{{ $selectedProduct->name ?? '' }}</p>
                                <x-buttons.plain h="h-8" bgColor="bg-red-400"
                                    wireClick="removeItem('{{ $key }}')">
                                    <x-heroicon-o-x class="w-4 h-4" />
                                </x-buttons.plain>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            <x-input type="datetime-local" name="expires_at" title="{{ __('Expires At') }}" />
            <x-checkbox title="{{ __('Active') }}" name="is_active" :defer="false" />
        </x-modal>
    </div>
</div>
