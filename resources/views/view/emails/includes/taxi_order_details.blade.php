{{-- From/To Taxi --}}
<x-table.email_table_tr>
    <x-slot name="title">
        <p style="color:#000000;font-size:14px">
            {{ __('From') }}
        </p>
        <p style="color:#333333;font-size:16px">
            <strong>{{ $order->taxi_order->vehicle_type->name ?? '' }}</strong>
        </p>
        <p style="color:#333333;font-size:12px">
            {{ $order->taxi_order->pickup_address ?? '' }}
        </p>
    </x-slot>
</x-table.email_table_tr>
<x-table.email_table_tr>
    <x-slot name="title">
        <p style="color:#000000;font-size:14px">
            {{ __('To') }}
        </p>
        <p style="color:#333333;font-size:16px">
            {{ $order->taxi_order->dropoff_address }}
        </p>

    </x-slot>
</x-table.email_table_tr>
