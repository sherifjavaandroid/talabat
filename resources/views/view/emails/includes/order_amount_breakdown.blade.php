{{-- subtotal --}}
<tr>
    <td align="left" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px">
        <!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]-->
        <table class="es-left" cellspacing="0" cellpadding="0" align="left"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
            <tr>
                <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="left" style="padding:0;Margin:0">
                                <p
                                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                    {{ __('Subtotal') }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]-->
        <table class="es-right" cellspacing="0" cellpadding="0" align="right"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
            <tr>
                <td align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="right" style="padding:0;Margin:0">
                                <h5
                                    style="Margin:0;line-height:17px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px">
                                    {{ currencyFormat($order->sub_total) }}<br type="_moz"></h5>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td></tr></table><![endif]-->
    </td>
</tr>
{{-- discount --}}
<tr>
    <td align="left" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px">
        <!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]-->
        <table class="es-left" cellspacing="0" cellpadding="0" align="left"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
            <tr>
                <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="left" style="padding:0;Margin:0">
                                <p
                                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                    {{ __('Discount') }}<br type="_moz"></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]-->
        <table class="es-right" cellspacing="0" cellpadding="0" align="right"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
            <tr>
                <td align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="right" style="padding:0;Margin:0">
                                <h5
                                    style="Margin:0;line-height:17px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px">
                                    {{ currencyFormat($order->discount) }}</h5>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td></tr></table><![endif]-->
    </td>
</tr>
{{-- delivery fee --}}
<tr>
    <td align="left" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px">
        <!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]-->
        <table class="es-left" cellspacing="0" cellpadding="0" align="left"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
            <tr>
                <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="left" style="padding:0;Margin:0">
                                <p
                                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                    {{ __('Delivery fee') }}<br type="_moz">
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]-->
        <table class="es-right" cellspacing="0" cellpadding="0" align="right"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
            <tr>
                <td align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="right" style="padding:0;Margin:0">
                                <h5
                                    style="Margin:0;line-height:17px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px">
                                    {{ currencyFormat($order->delivery_fee) }}<br type="_moz"></h5>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td></tr></table><![endif]-->
    </td>
</tr>
{{-- tax --}}
<tr>
    <td align="left" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px">
        <!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]-->
        <table class="es-left" cellspacing="0" cellpadding="0" align="left"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
            <tr>
                <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="left" style="padding:0;Margin:0">
                                <p
                                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                    {{ __('Tax') }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]-->
        <table class="es-right" cellspacing="0" cellpadding="0" align="right"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
            <tr>
                <td align="left" style="padding:0;Margin:0;width:270px">
                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="right" style="padding:0;Margin:0">
                                <h5
                                    style="Margin:0;line-height:17px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px">
                                    {{ currencyFormat($order->tax) }}<br type="_moz"></h5>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso]></td></tr></table><![endif]-->
    </td>
</tr>
{{-- order fees --}}
@if (!empty(json_decode($order->fees ?? []) ?? []))
    {{-- liner --}}
    <tr>
        <td style="padding:0;Margin:0;font-size:0" align="center">
            <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0"
                role="presentation"
                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                <tr>
                    <td
                        style="padding:0;Margin:0;border-bottom:1px solid #cccccc;background:unset;height:1px;width:100%;margin:0px">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @foreach (json_decode($order->fees ?? []) as $orderFee)
        <tr>
            <td align="left"
                style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px">
                <!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]-->
                <table class="es-left" cellspacing="0" cellpadding="0" align="left"
                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
                    <tr>
                        <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:270px">
                            <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                <tr>
                                    <td align="left" style="padding:0;Margin:0">
                                        <p
                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                            {{ $orderFee->name ?? __('Fee') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]-->
                <table class="es-right" cellspacing="0" cellpadding="0" align="right"
                    style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
                    <tr>
                        <td align="left" style="padding:0;Margin:0;width:270px">
                            <table width="100%" cellspacing="0" cellpadding="0" role="presentation"
                                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                <tr>
                                    <td align="right" style="padding:0;Margin:0">
                                        <h5
                                            style="Margin:0;line-height:17px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px">
                                            {{ currencyFormat($orderFee->amount) }}
                                            <br type="_moz">
                                        </h5>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    @endforeach
    <tr>
        <td style="padding:0;Margin:0;font-size:0" align="center">
            <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0"
                role="presentation"
                style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                <tr>
                    <td
                        style="padding:0;Margin:0;border-bottom:1px solid #cccccc;background:unset;height:1px;width:100%;margin:0px">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endif
