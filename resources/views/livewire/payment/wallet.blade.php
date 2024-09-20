@section('title', __('Wallet Topup'))

<div class="">

    <div wire:loading.flex>
        <div class="w-11/12 p-12 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12">
            <x-heroicon-o-clock class="w-12 h-12 mx-auto text-gray-400 md:h-24 md:w-24" />
            <p class="text-xl font-medium text-center">{{ __('Wallet Topup') }}</p>
            <p class="text-sm text-center">{{ __('Please wait while we process your payment') }}</p>
        </div>
    </div>

    @if (($selectedPaymentMethod->slug ?? '') != 'offline')
        <div wire:loading.remove
            class="w-11/12 p-4 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12 md:grid-cols-2">
            <div class="flex my-4">
                <div class="items-center">
                    <img src="{{ $selectedModel->wallet->user->photo }}" class="w-24 h-24 rounded-full md:w-32 md:h-32" />
                    <p>{{ $selectedModel->wallet->user->name }}</p>
                </div>
                <div class="ml-auto text-right">
                    <p class="text-2xl font-bold">{{ currencyFormat($selectedModel->amount) }}</p>
                    <p>{{ __('Account Top-up') }}</p>
                    <div class="py-2">
                        <livewire:select.language-selector />
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($paymentMethods as $paymentMethod)
                    <button wire:click='initPayment({{ $paymentMethod->id }})'>
                        <div class="flex items-center p-1 border rounded shadow">
                            <img src="{{ $paymentMethod->photo }}" class="w-2/12 md:w-3/12" />
                            <p class="mx-2">{{ $paymentMethod->name }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
        <p class="w-full p-4 text-sm text-center text-gray-500">{{ __('Do not close this window') }}</p>

        <div x-data="{ open: {{ $selectedPaymentMethod != null && $selectedPaymentMethod->slug == 'paypal' ? 'true' : 'false' }} }">
            <x-modal onCancel="resetSelectedMethod" :clickAway="false">
                <div id="paypal-button-container" class="py-12"></div>
            </x-modal>
        </div>
    @else
        @include('livewire.payment.offline.wallet')
    @endif
    {{-- paytm --}}
    @include('livewire.payment.gateways.paytm')
    {{-- payU --}}
    @include('livewire.payment.gateways.payu')
    {{-- close --}}
</div>
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ $paypalMethod->public_key ?? '' }}&currency={{ setting('currencyCode', 'USD') }}&intent=capture">
    </script>
    <script src="{{ asset('js/topup.js') }}"></script>
    {{-- //custom payment  --}}
@endpush
