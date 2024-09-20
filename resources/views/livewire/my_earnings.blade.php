@section('title', __('My Earning'))
<div>

    <x-baseview title="{{ __('My Earning') }}">
        <div class="w-full p-4 rounded-sm shadow md:w-6/12 lg:w-5/12 xl:w-3/12">
            <p class="flex items-center justify-between">
                <span>{{ __("Vendor") }}:</span>
                <span class="text-xl font-semibold rtl:mr-auto ltr:ml-auto">{{ $earning->vendor->name ?? '' }}</span>
            </p>
            <p class="flex items-center justify-between">
                <span>{{ __("Amount") }}:</span>
                <span class="text-xl font-semibold rtl:mr-auto ltr:ml-auto">{{ currencyFormat($earning->amount ?? 0.00) }}</span>
            </p>
            <p class="flex items-center justify-between">
                <span>{{ __("Updated At") }}:</span>
                <span class="text-xl font-semibold rtl:mr-auto ltr:ml-auto">{{ $earning->formatted_updated_date ?? '' }}</span>
            </p>
            <hr class="my-4" />
            <x-buttons.payout :model="$earning" />
        </div>
    </x-baseview>

    {{-- payout --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Payout') }}" action="payout">
            <p class="text-xl font-semibold">{{ __('Pay') }} {{ Str::ucfirst(Str::singular($type ?? '')) }}</p>
            <x-input title="{{ __('Amount') }}" name="amount" placeholder="10" />
            <x-select title="{{ __('Payment Method') }}" :options="$paymentMethods" name="payment_method_id" />
            <x-input title="{{ __('Note') }}" name="note" placeholder="" />
        </x-modal>
    </div>


</div>
