@extends('view.emails.plain')
@section('body')
    <div>
        <!--[if gte mso 9]>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <v:fill type="tile" color="#f6f6f6"></v:fill>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </v:background>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <![endif]-->
        {{-- table --}}
        <table cellspacing="0" cellpadding="0" style="">
            <tr>
                <td valign="top" style="padding:0;Margin:0">
                    <table class="es-content" cellspacing="0" cellpadding="0" align="center"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%; margin-bottom:20px;">
                        <tr>
                            <td align="center" style="padding:0;Margin:0">
                                <h3
                                    style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">
                                    @if ($order->taxi_order != null)
                                        {{ __('Trip Receipt') }}
                                    @else
                                        {{ __('Order Receipt') }}
                                    @endif
                                </h3>

                                <p
                                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#9ca3af;font-size:14px;">
                                    {{ $order->updated_at->format('Y-m-d') }}
                                    {{ __('at') }}
                                    {{ $order->updated_at->format('h:ia') }}
                                </p>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 20px;">
                                <h3
                                    style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">
                                    {{ __('Hi') }} {{ $order->user->name }},
                                </h3>
                                <p
                                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                    {{ __('This is your receipt.') }}
                                </p>
                            </td>
                        </tr>
                        {{-- spacing only --}}
                        <tr>
                            <td style="padding-top: 20px;">
                                <hr />
                            </td>
                        </tr>
                    </table>
                    {{--  --}}
                    {{-- listed info --}}
                    <table class="es-content" cellspacing="0" cellpadding="0" align="center"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;border-collapse: separate;
                        border-spacing: 10px;">

                        {{-- order items/service/taxi --}}
                        @empty($order->vendor)
                            @include('view.emails.includes.taxi_order_details')
                        @else
                            {{--  --}}
                            {{-- regular order --}}
                            @empty($order->package_type)
                                @include('view.emails.includes.order_details')
                            @else
                                @include('view.emails.includes.package_order_details')
                            @endempty
                        @endempty

                        {{-- divider --}}
                        <tr>
                            <td style="height:20px;margin-top:10px;margin-bottom:10px;" colspan="2">
                                <hr />
                            </td>
                        </tr>
                        {{-- amount breakdown --}}
                        <x-table.email_table_tr title="{{ __('Subtotal') }}"
                            content="{{ currencyFormat($order->sub_total ?? '') }}" />
                        <x-table.email_table_tr title="{{ __('Discount') }}"
                            content="{{ currencyFormat($order->discount ?? '') }}" />
                        <x-table.email_table_tr title="{{ __('Delivery fee') }}"
                            content="{{ currencyFormat($order->delivery_fee ?? '') }}" />
                        <x-table.email_table_tr title="{{ __('Tax') }}"
                            content="{{ currencyFormat($order->tax ?? '') }}" />
                        <x-table.email_table_tr title="{{ __('Total Fare') }}"
                            content="{{ currencyFormat($order->total ?? '') }}" />
                        {{-- divider --}}
                        <tr>
                            <td style="height:20px;margin-top:10px;margin-bottom:10px;" colspan="2">
                                <hr />
                            </td>
                        </tr>
                        <x-table.email_table_tr title="{{ __('Payment Method') }}"
                            content="{{ $order->payment_method->name ?? '' }}" />

                    </table>
                    {{-- outro --}}
                </td>
            </tr>
        </table>
    </div>
@endsection
