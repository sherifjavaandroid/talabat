@section('title', __('Orders'))
<div>

    <x-baseview title="{{ __('Orders') }}">
        @if ($stopRefresh)
            <div>
            @else
                <div wire:poll.20000ms="refreshDataTable">
        @endif
        <livewire:tables.fleet-order-table />
</div>
</x-baseview>

{{-- details moal --}}
<div x-data="{ open: @entangle('showDetails') }">
    <x-modal-lg>
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
    </x-modal-lg>
</div>


</div>
