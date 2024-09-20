@section('title', __('Summary Report') )
<div>

    <x-baseview title="{{ __('Summary Report') }}">


        <div class="p-4 border rounded-md shadow-sm">
            <p class="text-sm font-light">{{ __("Show Data by Date Range") }}</p>
            {{-- filter form  --}}
            <x-form action="loadData">
            <div class="grid grid-cols-2 gap-4 mt-1 mb-6 md:grid-cols-3">
                <x-input type="date" name="startDate" />
                <x-input type="date" name="endDate" />
                <x-buttons.primary title="{{ __('Show') }}" />
            </div>
            </x-form>
            {{-- money  --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
                {{-- admin  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-user-solid class="w-6 h-6 text-gray-500" />
                    <div>
                        <p class="font-semibold">{{ __("Admin earned") }}</p>
                        <p class="font-medium text-primary-600">{{ currencyFormat($adminEarned ?? 0.00) }}</p>
                    </div>
                </div>
                {{-- store  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-store-solid class="w-6 h-6 text-gray-500" />
                    <div>
                        <p class="font-semibold">{{ __("Store earned") }}</p>
                        <p class="font-medium text-primary-700">{{ currencyFormat($vendorsTotalEarned ?? 0.00) }}</p>
                    </div>
                </div>
                {{-- store  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-running-solid class="w-6 h-6 text-gray-500" />
                    <div>
                        <p class="font-semibold">{{ __("Delivery Fee earned") }}</p>
                        <p class="font-medium text-primary-800">{{ currencyFormat($driversTotalEarned ?? 0.00) }}</p>
                    </div>
                </div>
                {{-- store  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-money-bill-solid class="w-6 h-6 text-gray-500" />
                    <div>
                        <p class="font-semibold">{{ __("Total Sell") }}</p>
                        <p class="font-medium text-primary-900">{{ currencyFormat($totalSales ?? 0.00) }}</p>
                    </div>
                </div>
            </div>
            {{-- orders  --}}
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3 lg:grid-cols-4">
                {{-- admin  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-running-solid class="w-6 h-6" style="color: {{ setting('appColorTheme.enrouteColor') }}" />
                    <div>
                        <p class="font-semibold">{{ __("In progress") }}</p>
                        <p class="font-medium text-primary-600">{{ $progressOrder ?? 0 }}</p>
                    </div>
                </div>
                {{-- store  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-check-double-solid class="w-6 h-6" style="color: {{ setting('appColorTheme.deliveredColor') }}" />
                    <div>
                        <p class="font-semibold">{{ __("Delivered/Completed") }}</p>
                        <p class="font-medium text-primary-700">{{ $completedOrder ?? 0 }}</p>
                    </div>
                </div>
                {{-- store  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-exclamation-circle-solid class="w-6 h-6" style="color: {{ setting('appColorTheme.failedColor') }}" />
                    <div>
                        <p class="font-semibold">{{ __("Failed") }}</p>
                        <p class="font-medium text-primary-800">{{ $failedOrder ?? 0 }}</p>
                    </div>
                </div>
                {{-- store  --}}
                <div class="flex items-start p-4 space-x-4 border rounded-md shadow-sm">
                    <x-lineawesome-ban-solid class="w-6 h-6 " style="color: {{ setting('appColorTheme.cancelledColor') }}" />
                    <div>
                        <p class="font-semibold">{{ __("Canceled") }}</p>
                        <p class="font-medium text-primary-900">{{ $cancelledOrder ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

    </x-baseview>

</div>
