@section('title', __('Orders'))
<div>

    <x-baseview title="{{ __('Orders') }}" :showNew="true">
        <livewire:tables.order-table />
    </x-baseview>

    {{-- details moal --}}
    <div x-data="{ open: @entangle('showDetails') }">
        <x-modal-xl>
            <p class="text-xl font-semibold">{{ __('Order Details') }}</p>
            @if (!empty($selectedModel))
                @switch($selectedModel->order_type)
                    @case('package')
                        @include('livewire.order.package_order_details')
                    @break

                    @case('parcel')
                        @include('livewire.order.package_order_details')
                    @break

                    @case('service')
                        @include('livewire.order.service_order_details')
                    @break

                    @case('taxi')
                        @include('livewire.order.taxi_order_details')
                    @break

                    @default
                        @include('livewire.order.regular_order_details')
                    @break
                @endswitch
            @endif
        </x-modal-xl>
    </div>

    {{-- edit modal --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">

            <p class="text-xl font-semibold">{{ __('Edit Order') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Code') }}" text="#{{ $selectedModel->code ?? '' }}" />
                <x-details.item title="{{ __('Status') }}" text="{{ $selectedModel->status ?? '' }}" />
                <x-details.item title="{{ __('Payment Status') }}" text="{{ $selectedModel->payment_status ?? '' }}" />
                <x-details.item title="{{ __('Payment Method') }}"
                    text="{{ $selectedModel->payment_method->name ?? '' }}" />
            </div>
            @if ($selectedModel->can_edit_products ?? false)
                <div class="gap-4 mt-5 border-t">
                    <p class="w-full py-4 text-center underline cursor-pointer text-primary-500"
                        wire:click="showEditOrderProducts">{{ __('Edit Products') }}</p>
                </div>
            @endif
            <div class="gap-4 mt-5 border-t">
                {{-- with initial emit --}}
                {{-- delivery boy --}}
                <x-label for="deliveryBoyId" title="{{ __('Delivery Personal') }}">
                    <livewire:select.edit-order-driver-select name="deliveryBoyId"
                        placeholder="{{ __('Delivery Personal/Driver') }}" :searchable="true" />
                </x-label>
                <x-select title="{{ __('Status') }}" :options="$orderStatus ?? []" name="status" />
                <x-select title="{{ __('Payment Status') }}" :options="$orderPaymentStatus ?? []" name="paymentStatus" />
                <x-input title="{{ __('Note') }}" name="note" />

            </div>
        </x-modal>
    </div>


    {{-- payment review moal --}}
    <div x-data="{ open: @entangle('showAssign') }">
        <x-modal confirmText="{{ __('Approve') }}" action="approvePayment">

            <p class="text-xl font-semibold">{{ __('Order Payment Proof') }}</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-details.item title="{{ __('Transaction Code') }}"
                    text="{{ $selectedModel->payment->ref ?? '' }}" />
                <x-details.item title="{{ __('Status') }}" text="{{ $selectedModel->payment->status ?? '' }}" />
                <x-details.item title="{{ __('Payment Method') }}"
                    text="{{ $selectedModel->payment_method->name ?? '' }}" />
                <div>
                    <x-label title="{{ __('Transaction Photo') }}" />
                    <a href="{{ $selectedModel->payment->photo ?? '' }}" target="_blank">
                        <img src="{{ $selectedModel->payment->photo ?? '' }}" class="w-32 h-32" />
                    </a>
                </div>
            </div>
        </x-modal>
    </div>


</div>
