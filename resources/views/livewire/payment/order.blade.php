@section('title', __('Order Payment'))
<div>
    @if ($selectedModel->payment_method == null)
        {{-- Select payment methods --}}
        @if ($selectedModel->payment_status != 'request' && $selectedModel->vendor->vendor_type->slug == 'pharmacy')
            <div class="text-center w-11/12 p-4 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12 md:grid-cols-2">
                <p class="font-bold text-2xl">{{ __('Order Payment') }}</p>
                <p class="text-sm">
                    {{ __('You can not complete order payment until vendor request for payment. Please wait and check back later or contact vendor to request for payment') }}
                </p>
            </div>
        @else
            <div>
                <div wire:loading.remove
                    class="w-11/12 p-4 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12 md:grid-cols-2">
                    <div class="flex my-4">
                        <div class="items-center">
                            <img src="{{ $selectedModel->user->photo }}" class="w-18 h-18 rounded-full md:w-24 md:h-24" />
                            <p>{{ $selectedModel->user->name }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-2xl font-bold">{{ currencyFormat($selectedModel->payable_total) }}</p>
                            <p>{{ __('Order Payment') }}</p>
                            <div class="py-2">
                                <livewire:select.language-selector />
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($paymentMethods as $paymentMethod)
                            <button wire:click='setPaymentMethod({{ $paymentMethod->id }})'>
                                <div class="flex items-center p-1 border rounded shadow">
                                    <img src="{{ $paymentMethod->photo }}" class="w-2/12 md:w-3/12" />
                                    <p class="mx-2">{{ $paymentMethod->name }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
                <p class="w-full p-4 text-sm text-center text-gray-500">{{ __('Do not close this window') }}</p>
            </div>
        @endif
    @else
        @if ($selectedModel->payment_method->slug != 'offline')
            <div class="" wire:init="initPayment">
                <div class="w-11/12 p-12 mx-auto mt-20 border rounded shadow md:w-6/12 lg:w-4/12">
                    <x-heroicon-o-clock class="w-12 h-12 mx-auto text-gray-400 md:h-24 md:w-24" />
                    <p class="text-xl font-medium text-center">{{ __('Order Payment') }}</p>
                    <p class="text-sm text-center">
                        {{ __('Please wait while we process your payment') }}</p>
                    @if ($selectedModel->payment_method->slug == 'paypal')
                        <div id="paypal-button-container" class="py-12"></div>
                    @endif
                </div>

                {{-- close --}}
                <p class="w-full p-4 text-sm text-center text-gray-500">
                    {{ __('Do not close this window') }}</p>
            </div>
        @else
            @include('livewire.payment.offline.order')
        @endif
        {{-- paytm --}}
        @include('livewire.payment.gateways.paytm')
        {{-- payU --}}
        @include('livewire.payment.gateways.payu')

        @push('scripts')
            @if ($selectedModel->payment_method->slug == 'stripe')
                <script src="https://js.stripe.com/v3/"></script>
            @elseif($selectedModel->payment_method->slug == 'razorpay')
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            @elseif($selectedModel->payment_method->slug == 'flutterwave')
                <script src="https://checkout.flutterwave.com/v3.js"></script>
            @elseif($selectedModel->payment_method->slug == 'paystack')
                <script src="https://js.paystack.co/v1/inline.js"></script>
            @elseif($selectedModel->payment_method->slug == 'billplz')
                <script src="https://js.paystack.co/v1/inline.js"></script>
            @elseif($selectedModel->payment_method->slug == 'paypal')
                <script
                    src="https://www.paypal.com/sdk/js?client-id={{ $selectedModel->payment_method->public_key }}&currency={{ setting('currencyCode', 'USD') }}&intent=capture">
                </script>
                {{-- //custom payment --}}
            @endif

            <script src="{{ asset('js/payment.js') }}"></script>
        @endpush

    @endif
</div>
