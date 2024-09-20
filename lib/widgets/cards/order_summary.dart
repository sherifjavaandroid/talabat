import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/order_fee.dart';
import 'package:fuodz/widgets/cards/amount_tile.dart';
import 'package:dotted_line/dotted_line.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderSummary extends StatelessWidget {
  const OrderSummary({
    this.subTotal,
    this.discount,
    this.deliveryFee,
    this.tax,
    this.vendorTax,
    required this.total,
    this.driverTip = 0.00,
    this.mCurrencySymbol,
    this.fees = const [],
    Key? key,
  }) : super(key: key);

  final double? subTotal;
  final double? discount;
  final double? deliveryFee;
  final double? tax;
  final String? vendorTax;
  final double total;
  final double? driverTip;
  final String? mCurrencySymbol;
  final List<OrderFee> fees;

  @override
  Widget build(BuildContext context) {
    final currencySymbol =
        mCurrencySymbol != null ? mCurrencySymbol : AppStrings.currencySymbol;
    return VStack(
      [
        "Order Summary".tr().text.semiBold.xl.make().pOnly(bottom: Vx.dp12),
        AmountTile(
          "Subtotal".tr(),
          subTotal.currencyValueFormat(),
        ).py2(),
        AmountTile(
          "Discount".tr(),
          "- " + "$currencySymbol ${discount}".currencyFormat(),
        ).py2(),
        //
        CustomVisibilty(
          visible: deliveryFee != null,
          child: AmountTile(
            "Delivery Fee".tr(),
            "+ " + "$currencySymbol ${deliveryFee ?? 0.00}".currencyFormat(),
          ).py2(),
        ),

        //tax
        CustomVisibilty(
          visible: tax != null,
          child: AmountTile(
            "Tax (%s)".tr().fill([vendorTax ?? 0]),
            "+ " + "$currencySymbol ${tax ?? 0.00}".currencyFormat(),
          ).py2(),
        ),
        Visibility(
          visible: fees.isNotEmpty,
          child: VStack(
            [
              ...((fees).map((fee) {
                return AmountTile(
                  "${fee.name}".tr(),
                  "+ " + " $currencySymbol ${fee.amount}".currencyFormat(),
                ).py2();
              }).toList()),
              DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
            ],
          ),
        ),

        DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
        Visibility(
          visible: driverTip != null,
          child: VStack(
            [
              AmountTile(
                "Driver Tip".tr(),
                "+ " + "$currencySymbol ${driverTip ?? 0.00}".currencyFormat(),
              ).py2(),
              DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
            ],
          ),
        ),
        AmountTile(
          "Total Amount".tr(),
          "$currencySymbol ${total}".currencyFormat(),
        ),
      ],
    );
  }
}
