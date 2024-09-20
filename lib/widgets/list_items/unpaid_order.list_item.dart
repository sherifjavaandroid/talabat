import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class UnPaidOrderListItem extends StatelessWidget {
  const UnPaidOrderListItem({
    required this.order,
    Key? key,
  }) : super(key: key);

  final Order order;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        VStack(
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
        ).pLTRB(12, 12, 12, 6).opacity(value: 0.4),
        UiSpacer.divider(),
        //unpaid info
        "Order payment yet to be completed, hence you can't open order"
            .tr()
            .text
            .xs
            .bold
            .make()
            .pLTRB(12, 6, 12, 12)
            .box
            .make(),
      ],
    )
        .box
        .shadowXs
        .color(context.theme.colorScheme.surface)
        .clip(Clip.antiAlias)
        .roundedSM
        .make();
  }
}
