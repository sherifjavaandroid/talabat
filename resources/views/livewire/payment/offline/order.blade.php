<div wire:init="checkOfflinePayMobileView">
    <div class="">
        <div wire:loading.flex>
            <div class="w-11/12 p-12 mx-auto mt-10 border rounded shadow md:w-6/12 lg:w-4/12">
                <x-heroicon-o-clock class="w-12 h-12 mx-auto text-gray-400 md:h-24 md:w-24" />
                <p class="text-xl font-medium text-center">{{ __('Order Payment') }}</p>
                <p class="text-sm text-center">{{ __('Please wait while we process your payment') }}</p>
            </div>
        </div>

        <div wire:loading.remove.saveOfflinePayment
            class="w-11/12 p-4 mx-auto mt-10 border rounded shadow md:w-6/12 lg:w-4/12 md:grid-cols-2">

            {{-- form --}}
            <div class="{{ $done ? 'hidden' : 'block' }}">
                <div class="flex items-center pb-1 my-1 border-b">
                    <div class="">
                        {{ __('Order Payment') }}
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-2xl font-bold">{{ setting('currency') . '' . $selectedModel->payable_total }}</p>
                        <p class="text-sm font-light">{{ $selectedModel->payment_method->name }}</p>
                    </div>
                </div>
                {{-- instruction --}}
                <p class="mt-1 text-lg font-medium">{{ __('Instructions') }}</p>
                <p class="text-md">{!! nl2br(e($selectedModel->payment_method->instruction)) !!}</p>

                <p class="pt-2 mt-2 text-lg font-medium border-t">{{ __('Payment Details') }}</p>
                {{-- form --}}
                <x-form action="saveOfflinePayment" :noClass="true">
                    <x-input title="{{ __('Transaction Code') }}" name="paymentCode" />
                    <div class="my-4">
                        <span class="my-2 text-gray-700">{{ __('Transaction Photo') }}</span>
                        <x-input.filepond wire:model="photo"
                            acceptedFileTypes="['image/png', 'image/jpeg', 'image/jpg']" allowImagePreview
                            allowFileSizeValidation
                            maxFileSize="{{ setting('filelimit.max_product_digital_files_size', 2) }}mb" />
                    </div>
                    <x-buttons.primary title="{{ __('Submit') }}" />
                </x-form>
            </div>


            {{-- completed --}}
            <div class="{{ $done ? 'block' : 'hidden' }}">

                @if ($error)
                    <x-heroicon-o-emoji-sad class="w-12 h-12 mx-auto text-red-500 md:h-24 md:w-24" />
                @else
                    <x-heroicon-o-emoji-happy class="w-12 h-12 mx-auto text-green-500 md:h-24 md:w-24" />
                @endif
                {{-- <p class="text-xl font-medium text-center">Payment Failed</p> --}}
                <p class="text-sm font-medium text-center">{{ $errorMessage }}</p>
            </div>

        </div>





        {{-- close --}}
        <p class="{{ $done ? 'block' : 'hidden' }} w-full p-4 text-sm text-center text-gray-500">
            {{ __('You can close this window') }}</p>
        <p class="{{ $done ? 'hidden' : 'block' }} w-full p-4 text-sm text-center text-gray-500">
            {{ __('Do not close this window') }}</p>
    </div>
</div>
