<div class=" w-full md:w-10/12 lg:w-6/12">
    <x-form noClass="true" action="saveHomeSettings">
        <div class='grid grid-cols-1 gap-4 lg:grid-cols-2'>
            <x-checkbox title="{{ __('Wallet') }}" description="{{ __('Show Wallet On Home Screen') }}"
                name="showWalletOnHomeScreen" />
            <x-select title="{{ __('Home Screen Style') }}" :options="$homeViewStyles" name="homeViewStyle" />
        </div>
        <hr class="my-2 mt-4" />
        <div class='grid grid-cols-1 gap-4 lg:grid-cols-2'>
            <x-checkbox title="{{ __('Banner') }}" description="{{ __('Show Banner On Home Screen') }}"
                name="showBannerOnHomeScreen" />
            <x-select title="{{ __('Banner Position') }}" :options="['Top', 'Bottom']" name="bannerPosition" />
            <x-input title="{{ __('Banner Height') }}" name="bannerHeight" type="number" />
        </div>
        <hr class="my-2 mt-4" />
        <div class='grid grid-cols-1 gap-4 lg:grid-cols-2'>
            <x-select title="{{ __('Vendor Type Listing Style') }}" :options="['Both', 'GridView', 'ListView']" name="vendortypeListStyle" />
            <x-input title="{{ __('Vendor Type Per Row') }}" name="vendortypePerRow" type="number" />
        </div>
        <hr class="my-2 mt-4" />
        <div class='grid grid-cols-1 gap-4 lg:grid-cols-2'>
            <x-input title="{{ __('Vendor Type Image Height') }}" name="vendortypeHeight" type="number" />
            <x-input title="{{ __('Vendor Type Image Width') }}" name="vendortypeWidth" type="number" />

            <x-select title="{{ __('Vendor Type Image Style') }}" :options="['fill', 'cover', 'center']" name="vendortypeImageStyle" />
        </div>
        <x-buttons.primary title="{{ __('Save') }}" />
    </x-form>
</div>
