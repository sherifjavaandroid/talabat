@section('title',  __('Vendor Subscriptions') )
<div>

    <x-baseview title="{{ __('Vendor Subscriptions') }}">
        <livewire:tables.vendor-subscription-table />
    </x-baseview>

     {{-- payment review moal --}}
     <div x-data="{ open: @entangle('showCreate') }">
        <x-modal :withForm="false">

            <p class="text-xl font-semibold">{{ __('Subscription Payment Proof') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Transaction Code') }}"
                    text="{{ $selectedVendorSubscription->code ?? '' }}" />
                <x-details.item title="{{ __('Status') }}"
                    text="{{ $selectedVendorSubscription->status ?? '' }}" />
                <x-details.item title="{{ __('Payment Method') }}"
                    text="{{ $selectedVendorSubscription->payment_method->name ?? '' }}" />
                <div>
                    <x-label title="{{ __('Transaction Photo') }}" />
                    <a href="{{ $selectedVendorSubscription->photo ?? '' }}"
                        target="_blank">
                        <img src="{{ $selectedVendorSubscription->photo ?? '' }}"
                            class="w-32 h-32" />
                    </a>
                </div>
                <div></div>
                <div class="grid items-center w-full grid-cols-2 space-x-2 text-center">
                    <x-buttons.deactivate title="{{ __('Reject') }}" bgColor="bg-red-500" wireClick="rejectPayment" />
                    <x-buttons.activate title="{{ __('Approve') }}" bgColor="bg-red-500" wireClick="approvePayment" />
                </div>
            </div>
        </x-modal>
    </div>

</div>


