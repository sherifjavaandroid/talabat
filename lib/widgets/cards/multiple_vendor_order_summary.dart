import 'package:dotted_line/dotted_line.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/views/pages/cart/widgets/amount_tile.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class MultipleVendorOrderSummary extends StatelessWidget {
  const MultipleVendorOrderSummary({
    required this.subTotal,
    required this.discount,
    required this.deliveryFee,
    this.totalTax,
    this.totalFee,
    required this.total,
    this.driverTip = 0.00,
    this.mCurrencySymbol,
    this.taxes = const [],
    required this.vendors,
    this.subtotals = const [],
    this.deliveryDiscount,
    Key? key,
  }) : super(key: key);

  final double? subTotal;
  final double? discount;
  final double? deliveryFee;
  final double? totalTax;
  final double? totalFee;
  final double total;
  final double driverTip;
  final String? mCurrencySymbol;
  final List<double> taxes;
  final List<dynamic> vendors;
  final List<double> subtotals;
  final double? deliveryDiscount;
  @override
  Widget build(BuildContext context) {
    final currencySymbol =
        mCurrencySymbol != null ? mCurrencySymbol : AppStrings.currencySymbol;
    return VStack(
      [
        "Order Summary".tr().text.semiBold.xl.make().pOnly(bottom: Vx.dp12),
        //vendor fees like tax summary
        ...vendorAmounts(context, taxes, vendors, subtotals),
        DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
        //
        AmountTile(
          "Subtotal".tr(),
          (subTotal ?? 0).currencyValueFormat(),
        ).py2(),
        AmountTile(
          "Tax".tr(),
          "+ " + (totalTax).currencyValueFormat(),
        ).py2(),
        AmountTile(
          "Service Fee(s)".tr(),
          "+ " + (totalFee).currencyValueFormat(),
        ).py2(),
        Visibility(
          visible: discount != null,
          child: AmountTile(
            "Discount".tr(),
            "- " +
                "$currencySymbol ${discount ?? 0}"
                    .currencyFormat(currencySymbol),
          ).py2(),
        ),

        Visibility(
          visible: deliveryFee != null,
          child: VStack([
            DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
            AmountTile(
              "Delivery Fee".tr(),
              "+ " +
                  "$currencySymbol ${deliveryFee ?? 0}"
                      .currencyFormat(currencySymbol),
            ),
            Visibility(
              visible: deliveryDiscount != null,
              child: AmountTile(
                "Delivery Discount".tr(),
                "- " +
                    "$currencySymbol ${deliveryDiscount ?? 0}"
                        .currencyFormat(currencySymbol),
              ),
            ),
          ]).py2(),
        ),
        //
        "Note: Delivery fee for each vendor is sum up to get the total delivery fee"
            .tr()
            .text
            .sm
            .gray600
            .italic
            .make(),
        // AmountTile(
        //   "Tax".tr(),
        //   "+ " + " $currencySymbol ${tax ?? 0}".currencyFormat(),
        // ).py2(),

        DottedLine(dashColor: context.textTheme.bodyLarge!.color!).py8(),
        Visibility(
          visible: driverTip > 0,
          child: VStack(
            [
              AmountTile(
                "Driver Tip".tr(),
                "+ " + "$currencySymbol ${driverTip}".currencyFormat(),
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

  List<Widget> vendorAmounts(
    BuildContext context,
    List<dynamic> taxes,
    List<dynamic> vendors,
    List<double> subtotals,
  ) {
    final currencySymbol =
        mCurrencySymbol != null ? mCurrencySymbol : AppStrings.currencySymbol;
    List<Widget> items = [];
    TextStyle amountStyle = TextStyle(
      fontSize: 13,
      fontWeight: FontWeight.w600,
    );
    //
    for (var i = 0; i < taxes.length; i++) {
      final vendor = vendors[i] as Vendor;
      double vendorSumTotalFees = 0;
      double vendorSubtotal = subtotals[i];
      double? vendorCalTax = taxes[i];
      vendorSumTotalFees += subtotals[i];
      vendorSumTotalFees += vendorCalTax ?? 0;

      Widget widget = VStack(
        [
          "${vendor.name}".text.bold.lg.make(),
          AmountTile(
            "Subtotal".tr(),
            " $currencySymbol ${vendorSubtotal}".currencyFormat(),
            amountStyle: amountStyle,
          ).py1(),
          AmountTile(
            "Tax (%s)".tr().fill(["${vendor.tax}%"]),
            " $currencySymbol ${vendorCalTax ?? 0}"
                .currencyFormat(currencySymbol),
            amountStyle: amountStyle,
          ).py1(),

          ...(vendor.fees.map((fee) {
            //fixed
            if ((fee.percentage != 1)) {
              //
              vendorSumTotalFees += fee.value;
              //
              return AmountTile(
                "${fee.name}".tr(),
                "$currencySymbol ${fee.value}".currencyFormat(currencySymbol),
                amountStyle: amountStyle,
              ).py1();
            } else {
              //
              vendorSumTotalFees += fee.getRate(vendorSubtotal);
              //percentage
              return AmountTile(
                "${fee.name} (%s)".tr().fill(["${fee.value}%"]),
                "$currencySymbol ${fee.getRate(vendorSubtotal)}"
                    .currencyFormat(currencySymbol),
                amountStyle: amountStyle,
              ).py1();
            }
          }).toList()),

          //
          DottedLine(
              dashColor: context.textTheme.bodyLarge!.color!.withOpacity(0.3)),
          AmountTile(
            "",
            " $currencySymbol ${vendorSumTotalFees}"
                .currencyFormat(currencySymbol),
            amountStyle: amountStyle,
          ).py(2),
          DottedLine(
            dashColor: context.textTheme.bodyLarge!.color!.withOpacity(0.3),
          ),
        ],
      ).box.p8.border(color: Utils.textColorByTheme()).roundedSM.make().py2();
      items.add(widget);
    }
    return items;
  }
}
