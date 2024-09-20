@section('title', __('Payment Methods'))
<div>

    <x-baseview title="{{ __('Payment Methods') }}" :showNew="true" actionTitle="{{ __('Assign') }}">
        <livewire:tables.vendor-payment-method-table />
    </x-baseview>

    {{-- assign form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal-lg confirmText="{{ __('Save') }}" action="assignPaymentMethods" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Assign Payment Methods To Vendor') }}</p>

            {{-- table of all payment methods --}}
            <table class="w-full table-auto border-collapse border border-slate-500 my-4">
                <thead>
                    <tr>
                        <th class="border border-slate-600 p-2">{{ __('Name') }}</th>
                        <th class="border border-slate-600 p-2">{{ __('Allowed On Regular Pickup') }}</th>
                        <th class="border border-slate-600 p-2">{{ __('Allowed') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentMethods ?? [] as $key => $paymentMethod)
                        <tr>
                            <td class="border border-slate-700 p-2">{{ $paymentMethod->name }}</td>
                            <td class="border border-slate-700 p-2">
                                <div class="mx-auto flex items-center w-full">
                                    <input type="checkbox"
                                        wire:model.defer="vendorPaymentMethods.{{ $key }}.allow_pickup"
                                        value="1">
                                </div>
                            </td>
                            <td class="border border-slate-700 p-2">
                                <div class="mx-auto flex items-center w-full">
                                    <input type="checkbox"
                                        wire:model.defer="vendorPaymentMethods.{{ $key }}.selected"
                                        value="1">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </x-modal-lg>
    </div>
</div>
