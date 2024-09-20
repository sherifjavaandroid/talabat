import 'package:dotted_line/dotted_line.dart';
import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiOrderListItem extends StatelessWidget {
  const TaxiOrderListItem({
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
        HStack(
          [
            CustomImage(
              imageUrl: order.user.photo,
            ).wh(35, 35).box.clip(Clip.antiAlias).roundedSM.make(),
            //
            UiSpacer.hSpace(10),
            VStack(
              [
                "${order.user.name}".text.semiBold.base.make(),
                HStack([
                  Icon(
                    FlutterIcons.star_ant,
                    color: AppColor.ratingColor,
                    size: 16,
                  ),
                  UiSpacer.hSpace(10),
                  "${order.user.rating}".text.light.gray700.make(),
                ]),
              ],
            ).expand(),

            VStack(
              [
                "Fee".tr().text.sm.light.gray500.make(),
                "${order.taxiOrder?.currency != null ? order.taxiOrder?.currency?.symbol : AppStrings.currencySymbol}${order.total}"
                    .text
                    .semiBold
                    .base
                    .make(),
              ],
            ),
            UiSpacer.hSpace(),
            VStack(
              [
                "Status".tr().text.sm.light.gray500.make(),
                "${order.taxiStatus}"
                    .text
                    .semiBold
                    .sm
                    .color(AppColor.getStausColor(order.status))
                    .make(),
              ],
            ),
            // VStack(
            //   [
            //     "Time".tr().text.sm.light.gray500.make(),
            //     HStack(
            //       [
            //         "${order.duration}".text.semiBold.base.make(),
            //         "mins".tr().text.light.xs.gray600.make(),
            //       ],
            //     ),
            //   ],
            // ),
          ],
        ).p12(),
        UiSpacer.divider(),
        VStack(
          [
            //
            HStack(
              [
                Image.asset(AppImages.pickupLocation).wh(12, 12),
                UiSpacer.horizontalSpace(space: 10),
                "${order.taxiOrder?.pickupAddress}"
                    .text
                    .medium
                    .ellipsis
                    .make()
                    .expand(),
                UiSpacer.hSpace(5),
                //time
                "${order.taxiOrder?.startTime}".text.light.gray500.sm.make(),
              ],
            ),
            DottedLine(
              direction: Axis.vertical,
              lineThickness: 2,
              dashGapLength: 1,
              dashColor: AppColor.primaryColor,
            ).wh(1, 15).px4(),
            HStack(
              [
                Image.asset(AppImages.dropoffLocation).wh(12, 12),
                UiSpacer.horizontalSpace(space: 10),
                "${order.taxiOrder?.dropoffAddress}"
                    .text
                    .medium
                    .overflow(TextOverflow.ellipsis)
                    .make()
                    .expand(),
                UiSpacer.hSpace(5),
                //time
                "${order.taxiOrder?.endTime}".text.light.gray500.sm.make(),
              ],
            ),
          ],
        ).px16().py8()
      ],
    )
        .onInkTap(() => orderPressed())
        .box
        .color(context.theme.colorScheme.surface)
        .shadowXs
        .clip(Clip.antiAlias)
        .roundedSM
        .make();
  }
}
