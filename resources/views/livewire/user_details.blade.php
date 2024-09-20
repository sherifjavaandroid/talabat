@section('title', __('User Details'))
<div>

    <x-baseview title="">

        @empty($selectedModel)
            <div class="p-4 border-2 rounded-xl text-primary-500 border-primary-500 opacity-20 centered">
                {{ __('No User Found') }}
            </div>
        @else
            <div class="flex items-center">
                <div class="w-full">
                    <p class='text-2xl font-semibold'>{{ __('User ID') }} #{{ $selectedModel->id }}</p>

                    <div class="flex items-center space-x-2 font-medium text-gray-500">
                        <p class="text-sm">{{ $selectedModel->code }}</p>
                        <p class="text-sm">{{ $selectedModel->role_name }}</p>
                        <div class="flex items-center">
                            <x-heroicon-o-calendar class="w-5 h-5 rounded-full" />
                            <p>{{ __('Joined at') }} : {{ $selectedModel->created_at->format('d M Y h:i a') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if (!empty($prevUserId))
                        <a href="{{ route('users.details', $prevUserId) }}"
                            class="text-white bg-gray-500 rounded-full hover:text-gray-300 hover:bg-gray-700"
                            title="{{ __('Prev User') }}">
                            <x-heroicon-o-arrow-left class="w-8 h-8 p-2" />
                        </a>
                    @endif
                    @if (!empty($nextUserId))
                        <a href="{{ route('users.details', $nextUserId) }}"
                            class="text-white bg-gray-500 rounded-full hover:text-gray-300 hover:bg-gray-700"
                            title="{{ __('Next User') }}">
                            <x-heroicon-o-arrow-right class="w-8 h-8 p-2" />
                        </a>
                    @endif
                </div>
            </div>
            {{-- wallet and Loyalty points  --}}
            <div class="grid gap-6 mt-8 md:grid-cols-2 lg:grid-cols-4">

                {{-- profile details  --}}
                <x-dashboard-card-plain bg="bg-primary-500 text-white border-primary-500">
                    <p class="text-2xl font-semibold">{{ $selectedModel->name }}</p>
                    @production
                        <p>{{ $selectedModel->email }} <span>|</span> {{ $selectedModel->phone }}</p>
                    @else
                        <p>{{ \Str::padLeft('', Str::of($selectedModel->email ?? '')->length(), '*') }} <span>|</span>
                            {{ \Str::padLeft('', Str::of($selectedModel->phone ?? '')->length(), '*') }}</p>
                    @endproduction
                </x-dashboard-card-plain>
                {{-- wallet balance  --}}
                <x-dashboard-card bg="bg-primary-100" title="{{ __('Wallet Balance') }}"
                    value="{{ currencyformat($selectedModel->wallet->balance ?? 0.0) }}">
                    <x-lineawesome-wallet-solid class="w-16 " />
                </x-dashboard-card>

                {{-- loyalty point  --}}
                @if ((bool) setting('finance.enableLoyalty', false))
                    <x-dashboard-card bg="bg-primary-100" title="{{ __('Loyalty Points') }}"
                        value="{{ $loyaltyPoints ?? 0.0 }}">
                        <x-lineawesome-gifts-solid class="w-16 " />
                    </x-dashboard-card>
                @endif

                {{-- Orders --}}
                <x-dashboard-card bg="bg-primary-500 text-white border-primary-500" title="{{ __('Total Orders') }}"
                    value="{{ $ordersCount ?? 0 }}">
                    <x-heroicon-s-shopping-bag class="w-16 text-white" />
                </x-dashboard-card>
                {{-- most expensive order  --}}
                @if (!(bool) setting('finance.enableLoyalty', false))
                    <x-dashboard-card bg="bg-primary-100" title="{{ __('Most Expensive Order') }}"
                        value="{{ currencyformat($expensiveOrders ?? 0) }}">
                        <x-lineawesome-superscript-solid class="w-16 " />
                    </x-dashboard-card>
                @endif
            </div>

            <div class="flex p-4 mt-10 space-x-0 bg-white rounded-md shadow md:space-x-6">
                <div class="w-full md:w-6/12">
                    {{-- user order list  --}}
                    <p class="pb-4 text-xl font-bold">{{ __('Orders') }}</p>
                    <livewire:tables.user-order-table userId="{{ $selectedModel->id }}" />
                </div>
                <div class="w-full md:w-6/12">
                    {{-- wallet transactions list  --}}
                    <p class="pb-4 text-xl font-bold">{{ __('Wallet Transactions') }}</p>
                    <livewire:tables.user-wallet-transaction-table walletId="{{ $selectedModel->wallet->id ?? '' }}" />
                </div>
            </div>

        @endempty
    </x-baseview>

</div>
