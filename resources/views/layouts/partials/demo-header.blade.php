<div class="bg-white shadow-sm mb-4">
    @php
        $showBuyNowBanner = env('APP_ENV') != 'production' || request()->getHost() === 'fuodz.edentech.online';
    @endphp
    @if ($showBuyNowBanner)
        <div class="block md:flex items-center justify-center text-center space-x-4 py-2 text-sm md:text-base">
            <p>
                This is a demo website - Buy genuine & offical Version of Glover using our official link
            </p>
            <a href="https://codecanyon.net/item/fuodz-grocery-food-pharmacy-store-parcelcourier-delivery-mobile-app-with-php-laravel-backend/31145802"
                target="_blank">
                {{-- button --}}
                <button type="button"
                    class="text-white inline-flex items-center justify-center px-4 py-2 text-xs md:text-sm font-medium bg-primary-600 rounded-md shadow hover:bg-primary-700">
                    Buy Glover
                </button>
            </a>

        </div>
    @endif
</div>
