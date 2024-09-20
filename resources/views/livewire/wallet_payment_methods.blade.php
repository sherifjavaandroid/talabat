@section('title', __('Wallet Payment Methods') )
<div>

    <x-baseview title="{{ __('Wallet Payment Methods') }}">
        <p>
            <span class="font-semibold text-red-500">{{ __("Note") }}:</span>
            <span class="text-sm text-gray-500">
            {{ __("Any payment method enabled here will show to wallet top-up even if the payment method is deactivated in the payment methods page") }}
            </span>
        </p>
        <livewire:tables.wallet-payment-methods-table />
    </x-baseview>

</div>
