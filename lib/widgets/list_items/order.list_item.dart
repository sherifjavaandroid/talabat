import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class OrderListItem extends StatelessWidget {
  const OrderListItem({
    required this.order,
    this.onPayPressed,
    required this.orderPressed,
    Key? key,
  }) : super(key: key);

  final Order order;
  final Function? onPayPressed;
  final Function orderPressed;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        "#${order.code}".text.xl.medium.make(),
        //amount and total products
        HStack(
          [
            (order.isPackageDelivery
                    ? order.packageType!.name
                    : order.isSerice
                        ? "${order.orderService?.service?.category?.name}"
                        : "%s Product(s)"
                            .tr()
                            .fill([order.orderProducts?.length]))
                .text
                .medium
                .make()
                .expand(),
            "${AppStrings.currencySymbol} ${order.total}"
                .currencyFormat()
                .text
                .xl
                .semiBold
                .make(),
          ],
        ),
        //time & status
        HStack(
          [
            //time
            order.formattedDate.text.sm.make().expand(),
            order.status
                .tr()
                .allWordsCapitilize()
                .text
                .lg
                .color(
                  AppColor.getStausColor(order.status),
                )
                .medium
                .make(),
          ],
        ),
      ],
    )
        .p12()
        .onInkTap(() => orderPressed())
        .card
        .elevation(1)
        .clip(Clip.antiAlias)
        .roundedSM
        .make();
  }
}
