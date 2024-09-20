@section('title', __('Product Details'))
<div>

    <x-baseview title="">

        @empty($selectedModel)
            <div class="p-4 border-2 rounded-xl text-primary-500 border-primary-500 opacity-20 centered">
                {{ __('No Product Found') }}
            </div>
        @else
            <div class="flex items-center">
                <div class="w-full space-y-1">
                    <p class='text-2xl font-semibold'> {{ $selectedModel->name }}</p>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-1 text-sm">
                            <x-heroicon-o-calendar class="w-4 h-4" />
                            <p>{{ __('Created On') }} : {{ $selectedModel->created_at->format('d M Y h:i a') }}</p>
                        </div>
                        <p>|</p>
                        <div class="flex items-center space-x-1 text-sm">
                            <x-heroicon-o-calendar class="w-4 h-4" />
                            <p>{{ __('Last Updated') }} : {{ $selectedModel->updated_at->format('d M Y h:i a') }}</p>
                        </div>
                    </div>
                </div>

            </div>
            {{-- analytics  --}}
            <div class="grid gap-6 mt-8 md:grid-cols-2 lg:grid-cols-4">

                {{-- Orders --}}
                <x-dashboard-card bg="bg-primary-500 text-white border-primary-500"
                    title="{{ __('Total Orders') }} [{{ __('Successful') }}]" value="{{ $ordersCount ?? 0 }}">
                    <x-heroicon-s-shopping-bag class="w-16 text-white" />
                </x-dashboard-card>

                {{-- unit sold  --}}
                <x-dashboard-card bg="bg-primary-100" title="{{ __('Total Unit Sold') }} [{{ __('Successful') }}]"
                    value="{{ $totalUnitSold ?? 0 }}">
                    <x-heroicon-o-archive class="w-16 " />
                </x-dashboard-card>

                {{-- totalPriceSold  --}}
                <x-dashboard-card bg="bg-primary-100" title="{{ __('Total Amount Sold') }} [{{ __('Successful') }}]"
                    value="{{ currencyformat($totalPriceSold ?? 0.0) }}">
                    <x-lineawesome-gifts-solid class="w-16 " />
                </x-dashboard-card>



            </div>

            <div class="p-4 mt-10 bg-white rounded-md shadow ">

                {{-- user order list  --}}
                <p class="pb-4 text-xl font-bold">{{ __('Orders') }}</p>
                <livewire:tables.product-order-table productId="{{ $selectedModel->id }}" />

            </div>
        @endempty
    </x-baseview>

</div>
