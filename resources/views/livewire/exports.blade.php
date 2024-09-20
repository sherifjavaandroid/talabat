@section('title', __('Data Export'))
    <div>

        <x-baseview title="{{ __('Data Export') }}">

            <div class="grid grid-cols-1 gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

                <div>
                    <x-settings-item title="{{ __('Categories') }}" wireClick="exportData(1, 'Categories')">
                        <x-heroicon-o-folder class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>
                <div>
                    <x-settings-item title="{{ __('Subcategories') }}" wireClick="exportData(2, 'Subcategories')">
                        <x-heroicon-o-folder class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>

                <div>
                    <x-settings-item title="{{ __('Vendors') }}" wireClick="exportData(3, 'Vendors')">
                        <x-heroicon-o-shopping-cart class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>
                <div>
                    <x-settings-item title="{{ __('Menus') }}" wireClick="exportData(4, 'Menus')">
                        <x-heroicon-o-book-open class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>
                <div>
                    <x-settings-item title="{{ __('Products') }}" wireClick="exportData(5, 'Products')">
                        <x-heroicon-o-archive class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>
                <div>
                    <x-settings-item title="{{ __('Services') }}" wireClick="exportData(6, 'Services')">
                        <x-heroicon-o-archive class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>

                <div>
                    <x-settings-item title="{{ __('Earnings') }}" wireClick="exportData(7, 'Earnings')">
                        <x-heroicon-o-archive class="w-5 h-5 mr-4" />
                    </x-settings-item>
                </div>
                <div>
                    <x-settings-item title="{{ __('Payouts') }}" wireClick="exportData(8, 'Payouts')">
                        <x-heroicon-o-archive class="w-5 h-5 mr-4" />
                    </x-settings-item>
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
