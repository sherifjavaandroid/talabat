@section('title', __('Dashboard'))
<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <x-baseview title="{{ __('Dashboard') }}">

        {{-- Info cards --}}
        <div class="grid gap-6 mt-10 md:grid-cols-2 lg:grid-cols-4">

            {{-- Orders --}}
            <x-dashboard-card bg="bg-primary-100" title="{{ __('TOTAL ORDERS') }}" value="{{ $totalOrders }}">
                <x-heroicon-s-shopping-bag class="w-16 text-primary-600" />
            </x-dashboard-card>

            {{-- Earning --}}
            <x-dashboard-card bg="bg-blue-100" title="{{ __('TOTAL EARNINGS') }}"
                value="{{ setting('currency') }} {{ $totalEarnings }}">
                <x-heroicon-s-cash class="w-16 text-primary-600" />
            </x-dashboard-card>
            @role('admin')
                {{-- Total Vendors --}}
                <x-dashboard-card bg="bg-red-100" title="{{ __('TOTAL VENDORS') }}" value="{{ $totalVendors }}">
                    <x-heroicon-s-cake class="w-16 text-primary-600" />
                </x-dashboard-card>

                {{-- Users --}}
                <x-dashboard-card bg="bg-yellow-100" title="{{ __('TOTAL Clients') }}" value="{{ $totalClients }}">
                    <x-heroicon-s-users class="w-16 text-primary-600" />
                </x-dashboard-card>
            @endrole
        </div>

        {{-- Charts --}}
        <div class="grid gap-6 mt-10 lg:grid-cols-2">

            {{-- Orders --}}
            <x-dashboard-chart>
                <livewire:livewire-column-chart :column-chart-model="$ordersChart" />
            </x-dashboard-chart>

            @role('admin')
                {{-- Users --}}
                <x-dashboard-chart>
                    <livewire:livewire-column-chart :column-chart-model="$usersChart" />
                </x-dashboard-chart>
            @endrole
        </div>

        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4">

            <x-dashboard-chart>
                <livewire:livewire-column-chart :column-chart-model="$topSaleDaysChart" />
            </x-dashboard-chart>

            <x-dashboard-chart>
                <livewire:livewire-column-chart :column-chart-model="$topSaleTimingChart" />
            </x-dashboard-chart>

            @role('admin')
                <x-dashboard-chart>
                    <livewire:livewire-column-chart :column-chart-model="$userRolesChart" />
                </x-dashboard-chart>
            @endrole
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @role('admin')
                {{-- top selling --}}
                <div wire:init="fetchTopSellingVendors" class="space-y-2 border rounded shadow p-4">
                    <p class="text-base font-semibold text-gray-700">
                        {{ __('Top selling vendors') }}
                    </p>
                    {{-- listview --}}
                    <div class="space-y-2">
                        @foreach ($topSellingVendors ?? [] as $vendor)
                            <div>
                                <a href="{{ route('vendor.details', ['id' => $vendor->id]) }}" target="_blank">
                                    <div class="flex items-center justify-start space-x-2">
                                        <img src="{{ $vendor->logo }}" class="object-cover w-10 h-10 rounded" />
                                        <div>
                                            <p class="text-sm">{{ $vendor->name }}</p>
                                            <p class="text-xs font-light">
                                                {{ __('Total Orders') }}: {{ $vendor->successful_sales_count ?? 0 }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- Top rated Vendors --}}
                <div wire:init="fetchTopRatedVendors" class="space-y-2 border rounded shadow p-4">
                    <p class="text-base font-semibold text-gray-700">
                        {{ __('Top rated Vendors') }}
                    </p>
                    {{-- listview --}}
                    <div class="space-y-2">
                        @foreach ($topRatedVendors ?? [] as $vendor)
                            <div>
                                <a href="{{ route('vendor.details', ['id' => $vendor->id]) }}" target="_blank">
                                    <div class="flex items-center justify-start space-x-2">
                                        <img src="{{ $vendor->logo }}" class="object-cover w-10 h-10 rounded" />
                                        <div class="">
                                            <p class="text-sm">{{ $vendor->name }}</p>
                                            <p class="text-xs font-light flex space-x-2">
                                                <x-heroicon-s-star class="w-4 h-4 text-primary-500" />
                                                {{ $vendor->rating ?? 0 }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- Top customers --}}
                <div wire:init="fetchTopCustomers" class="space-y-2 border rounded shadow p-4">
                    <p class="text-base font-semibold text-gray-700">
                        {{ __('Top Buying Customers') }}
                    </p>
                    {{-- listview --}}
                    <div class="space-y-2">
                        @foreach ($topCustomers ?? [] as $user)
                            <div>
                                <a href="{{ route('users.details', ['id' => $user->id]) }}" target="_blank">
                                    <div class="flex items-center justify-start space-x-2">
                                        <img src="{{ $user->photo }}" class="object-cover w-10 h-10 rounded" />
                                        <div class="">
                                            <p class="text-sm">{{ $user->name }}</p>
                                            {{-- quick analysis --}}
                                            <div class="flex space-x-2 items-center justify-center">
                                                <p class="text-xs font-light flex space-x-2">
                                                    {{ __('Total Orders') }}:
                                                    {{ $user->orders_count ?? 0 }}
                                                </p>
                                                <span class="text-xs">|</span>
                                                <p class="text-xs font-light flex space-x-2">
                                                    {{ __('Successful') }}:
                                                    {{ $user->successful_orders_count ?? 0 }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- Top selling products --}}
                <div wire:init="fetchTopSellingProducts" class="space-y-2 border rounded shadow p-4">
                    <p class="text-base font-semibold text-gray-700">
                        {{ __('Top selling Products') }}
                    </p>
                    {{-- listview --}}
                    <div class="space-y-2">
                        @foreach ($topSellingProducts ?? [] as $product)
                            <div>
                                <a href="{{ route('product.details', ['id' => $product->id]) }}" target="_blank">
                                    <div class="flex items-center justify-start space-x-2">
                                        <img src="{{ $product->photo }}" class="object-cover w-10 h-10 rounded" />
                                        <div class="">
                                            <p class="text-sm">{{ $product->name }}</p>
                                            {{-- quick analysis --}}
                                            <div class="flex space-x-2 items-center justify-center">
                                                <p class="text-xs font-light flex space-x-2">
                                                    {{ __('Orders') }}:
                                                    {{ $product->successful_sales_count ?? 0 }}
                                                </p>
                                                <span class="text-xs">|</span>
                                                <p class="text-xs font-light flex space-x-2">
                                                    {{ __('Units') }}:
                                                    {{ $product->successful_sales_sum_quantity ?? 0 }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- my Top selling products --}}
                <div wire:init="fetchMyTopSellingProducts" class="space-y-2 border rounded shadow p-4">
                    <p class="text-base font-semibold text-gray-700">
                        {{ __('Top selling Products') }}
                    </p>
                    {{-- listview --}}
                    <div class="space-y-2">
                        @foreach ($myTopSellingProducts ?? [] as $product)
                            <div>
                                <a href="{{ route('product.details', ['id' => $product->id]) }}" target="_blank">
                                    <div class="flex items-center justify-start space-x-2">
                                        <img src="{{ $product->photo }}" class="object-cover w-10 h-10 rounded" />
                                        <div class="">
                                            <p class="text-sm">{{ $product->name }}</p>
                                            {{-- quick analysis --}}
                                            <div class="flex space-x-2 items-center justify-center">
                                                <p class="text-xs font-light flex space-x-2">
                                                    {{ __('Orders') }}:
                                                    {{ $product->successful_sales_count ?? 0 }}
                                                </p>
                                                <span class="text-xs">|</span>
                                                <p class="text-xs font-light flex space-x-2">
                                                    {{ __('Units') }}:
                                                    {{ $product->successful_sales_sum_quantity ?? 0 }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endrole

        </div>




        {{-- space --}}
        <div class="h-20"></div>

    </x-baseview>
</div>
@push('scripts')
    @livewireChartsScripts
@endpush
