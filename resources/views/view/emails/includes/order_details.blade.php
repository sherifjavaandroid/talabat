{{-- vendor section --}}
{{-- vendor --}}
<x-table.email_table_tr>
    <x-slot name="title">
        <p style="color:#000000;font-size:14px">
            {{ __('From') }}
        </p>
        <p style="color:#333333;font-size:16px">
            <strong>{{ $order->vendor->name }}</strong>
        </p>
        <p style="color:#333333;font-size:13px">
            {{ $order->vendor->address }}
        </p>
    </x-slot>
</x-table.email_table_tr>
{{-- to --}}
<x-table.email_table_tr>
    <x-slot name="title">
        <p style="color:#000000;font-size:14px">
            {{ __('To') }}
        </p>
        @empty($orderStop->delivery_addres)
            <p style="color:#333333;font-size:16px">
                <strong>{{ __('Customer Pickup') }}</strong>
            </p>
        @else
            <p style="color:#333333;font-size:16px">
                <strong>{{ $order->delivery_address->name ?? '' }}</strong>
            </p>
            <p style="color:#333333;font-size:12px">
                {{ $order->delivery_address->address ?? '' }}
            </p>
        @endempty
    </x-slot>
</x-table.email_table_tr>

{{-- divider --}}
<tr>
    <td style="height:20px;margin-top:10px;margin-bottom:10px;" colspan="2">
        <hr />
    </td>
</tr>

{{-- products --}}
@if (!empty($order->products) && $order->order_service == null)
    {{-- product list title --}}
    <x-table.email_table_tr>
        <x-slot name="title">
            <p
                style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px;margin-top:10px;">
                <strong>{{ __('Products') }}</strong>
            </p>
        </x-slot>
    </x-table.email_table_tr>
    {{-- product list item --}}

    @foreach ($order->products as $orderProduct)
        <x-table.email_table_tr>
            <x-slot name="title">
                {{ $orderProduct->quantity }} x {{ $orderProduct->product->name }}
            </x-slot>
            <x-slot name="content">
                <p
                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px;text-align:right">
                    {{ currencyFormat($orderProduct->price) }}
                </p>
            </x-slot>
        </x-table.email_table_tr>
    @endforeach
@else
    {{-- service --}}
    <x-table.email_table_tr>
        <x-slot name="title">
            <p
                style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px;margin-top:10px;">
                <strong>{{ __('Service') }}</strong>
            </p>
        </x-slot>
    </x-table.email_table_tr>
    {{-- service item --}}
    <x-table.email_table_tr>
        <x-slot name="title">
            {{ $order->order_service->service->name }}
        </x-slot>
        <x-slot name="content">
            <p
                style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px;text-align:right">
                {{ currencyFormat($order->order_service->price) }}
            </p>
        </x-slot>
    </x-table.email_table_tr>
@endif
