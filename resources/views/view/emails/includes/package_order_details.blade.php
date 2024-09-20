{{-- from --}}
<x-table.email_table_tr>
    <x-slot name="title">
        <p
            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:20px;color:#000000;font-size:13px">
            {{ __('From') }}</p>
        <p
            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;font-size:16px">
            <strong></strong><strong>{{ $order->stops->first()->name }}
                -
                ({{ $order->stops->first()->phone }})</strong><strong></strong><br type="_moz">
        </p>
        <p
            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:18px;color:#333333;font-size:12px">
            {{ $order->stops->first()->delivery_address->address }}<br type="_moz"></p>
    </x-slot>
</x-table.email_table_tr>

{{-- remaining stops --}}
@foreach ($order->stops as $orderStop)
    {{-- skip the first one --}}
    @if ($loop->first)
        @continue
    @endif
    {{-- to --}}
    <x-table.email_table_tr>
        <x-slot name="title">
            <p
                style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:20px;color:#000000;font-size:13px">
                {{ __('Stop') }}</p>
            <p
                style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;font-size:16px">
                <strong>{{ $orderStop->name }} - ({{ $orderStop->phone }})</strong>
                <br type="_moz">
            </p>
            <p
                style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:18px;color:#333333;font-size:12px">
                {{ $orderStop->delivery_address->address }}
                <br type="_moz">
            </p>
        </x-slot>
    </x-table.email_table_tr>
@endforeach


{{-- divider --}}
<tr>
    <td style="height:20px;margin-top:10px;margin-bottom:10px;" colspan="2">
        <hr />
    </td>
</tr>
{{-- details --}}
<x-table.email_table_tr title=" {{ __('Package details') }}" content="{{ $order->package_type->name }}" />
<x-table.email_table_tr title="{{ __('Weight') }}" content=" {{ $order->weight }}kg" />
<x-table.email_table_tr title="{{ __('Width') }}" content="{{ $order->width }}cm" />
<x-table.email_table_tr title="{{ __('Length') }}" content="{{ $order->length }}cm" />
<x-table.email_table_tr title="{{ __('Height') }}" content="{{ $order->height }}cm" />
