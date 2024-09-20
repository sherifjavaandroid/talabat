import 'package:dotted_line/dotted_line.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/sizes.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/fee.dart';
import 'package:fuodz/views/pages/cart/widgets/amount_tile.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderSummary extends StatelessWidget {
  const OrderSummary({
    this.subTotal,
    this.discount,
    this.deliveryFee,
    this.deliveryDiscount,
    this.tax,
    this.vendorTax,
    this.fees = const [],
    required this.total,
    this.driverTip = 0.00,
    this.mCurrencySymbol,
    this.customWidget,
    Key? key,
  }) : super(key: key);

  final double? subTotal;
  final double? discount;
  final double? deliveryFee;
  final double? deliveryDiscount;
  final double? tax;
  final String? vendorTax;
  final double total;
  final double? driverTip;
  final String? mCurrencySymbol;
  final List<Fee> fees;
  final Widget? customWidget;
  @override
  Widget build(BuildContext context) {
    final currencySymbol =
        mCurrencySymbol != null ? mCurrencySymbol : AppStrings.currencySymbol;

    TextStyle totalStyle = context.textTheme.bodyLarge!.copyWith(
      fontSize: Sizes.fontSizeLarge * 0.90,
      fontWeight: FontWeight.w600,
    );
    TextStyle summaryStyle = context.textTheme.bodyLarge!.copyWith(
      fontSize: Sizes.fontSizeLarge,
    );

    //view
    return VStack(
      [
        "Order Summary".tr().text.semiBold.xl.make().pOnly(bottom: Vx.dp12),
        //custom details
        if (customWidget != null) customWidget!,
        AmountTile(
          "Subtotal".tr(),
          "$currencySymbol ${subTotal ?? 0}".currencyFormat(currencySymbol),
          amountStyle: summaryStyle,
        ).py2(),
        Visibility(
          visible: discount != null,
          child: AmountTile(
            "Discount".tr(),
            "- " +
                "$currencySymbol ${discount ?? 0}"
                    .currencyFormat(currencySymbol),
            amountStyle: summaryStyle,
          ).py2(),
        ),
        AmountTile(
          "Tax (%s)".tr().fill(["${vendorTax ?? 0}%"]),
          "+ " + " $currencySymbol ${tax ?? 0}".currencyFormat(currencySymbol),
          amountStyle: summaryStyle,
        ).py2(),
        Visibility(
          visible: deliveryFee != null,
          child: VStack(
            [
              DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
              AmountTile(
                "Delivery Fee".tr(),
                "+ " +
                    "$currencySymbol ${deliveryFee ?? 0}"
                        .currencyFormat(currencySymbol),
                amountStyle: summaryStyle,
              ),
              Visibility(
                visible: deliveryDiscount != null,
                child: AmountTile(
                  "Delivery Discount".tr(),
                  "- " +
                      "$currencySymbol ${deliveryDiscount ?? 0}"
                          .currencyFormat(currencySymbol),
                  amountStyle: summaryStyle,
                ),
              ),
            ],
          ).py2(),
        ),
        DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
        Visibility(
          visible: fees.isNotEmpty,
          child: VStack(
            [
              ...((fees).map((fee) {
                //fixed
                if ((fee.percentage != 1)) {
                  return AmountTile(
                    "${fee.name}".tr(),
                    "+ " +
                        " $currencySymbol ${fee.value}"
                            .currencyFormat(currencySymbol),
                    amountStyle: summaryStyle,
                  ).py2();
                } else {
                  //percentage
                  return AmountTile(
                    "${fee.name} (%s)".tr().fill(["${fee.value}%"]),
                    "+ " +
                        " $currencySymbol ${fee.getRate(subTotal ?? 0)}"
                            .currencyFormat(currencySymbol),
                    amountStyle: summaryStyle,
                  ).py2();
                }
              }).toList()),
              DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
            ],
          ),
        ),
        Visibility(
          visible: driverTip != null && driverTip! > 0,
          child: VStack(
            [
              AmountTile(
                "Driver Tip".tr(),
                "+ " +
                    "$currencySymbol ${driverTip ?? 0}"
                        .currencyFormat(currencySymbol),
                amountStyle: summaryStyle,
              ).py2(),
              DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
            ],
          ),
        ),
        AmountTile(
          "Total Amount".tr(),
          "$currencySymbol ${total}".currencyFormat(currencySymbol),
          amountStyle: totalStyle,
        ),
      ],
    );
  }
}
