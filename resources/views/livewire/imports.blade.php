@section('title', __('Data Import'))
    <div>

        <x-baseview title="{{ __('Data Import') }}">

            <div class="grid grid-cols-1 gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

                <div>
                    <x-settings-item title="{{ __('Categories') }}" wireClick="showImportDialog(1, 'Categories')">
                        <x-heroicon-o-folder class="w-5 h-5 mr-4" />
                    </x-settings-item>
                    <a href="{{ asset('xlxs/categories.xlsx') }}" download class="text-sm text-gray-500 underline">{{ __('Download sample') }}</a>
                </div>
                <div>
                    <x-settings-item title="{{ __('Subcategories') }}" wireClick="showImportDialog(5, 'Subcategories')">
                        <x-heroicon-o-folder class="w-5 h-5 mr-4" />
                    </x-settings-item>
                    <a href="{{ asset('xlxs/subcategories.xlsx') }}" download class="text-sm text-gray-500 underline">{{ __('Download sample') }}</a>
                </div>

                <div>
                    <x-settings-item title="{{ __('Vendors') }}" wireClick="showImportDialog(2, 'Vendors')">
                        <x-heroicon-o-shopping-cart class="w-5 h-5 mr-4" />
                    </x-settings-item>
                    <a href="{{ asset('xlxs/vendors.xlsx') }}" download class="text-sm text-gray-500 underline">{{ __('Download sample') }}</a>
                </div>
                <div>
                    <x-settings-item title="{{ __('Menus') }}" wireClick="showImportDialog(3, 'Menus')">
                        <x-heroicon-o-book-open class="w-5 h-5 mr-4" />
                    </x-settings-item>
                    <a href="{{ asset('xlxs/menus.xlsx') }}" download class="text-sm text-gray-500 underline">{{ __('Download sample') }}</a>
                </div>
                <div>
                    <x-settings-item title="{{ __('Products') }}" wireClick="showImportDialog(4, 'Products')">
                        <x-heroicon-o-archive class="w-5 h-5 mr-4" />
                    </x-settings-item>
                    <a href="{{ asset('xlxs/products.xlsx') }}" download class="text-sm text-gray-500 underline">{{ __('Download sample') }}</a>
                </div>

                <div>
                    <x-settings-item title="{{ __('Services') }}" wireClick="showImportDialog(6, 'Services')">
                        <x-heroicon-o-archive class="w-5 h-5 mr-4" />
                    </x-settings-item>
                    {{-- TODO: Service demo file --}}
                    <a href="{{ asset('xlxs/services.xlsx') }}" download class="text-sm text-gray-500 underline">{{ __('Download sample') }}</a>
                </div>

            </div>

        </x-baseview>

        {{-- import dialog --}}
        <div x-data="{ open: @entangle('showCreate') }">
            <x-modal confirmText="Import" action="processImport">
                <p class="text-xl font-semibold">Import {{ $dataTypeName ?? '' }}</p>
                <x-media-upload title="Data File" name="photo" :photo="$photo" :photoInfo="$photoInfo" :image="false"
                    types="xlsx" rules="xls" />
            </x-modal>
        </div>

    </div>
