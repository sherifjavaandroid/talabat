import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class GridViewServiceListItem extends StatelessWidget {
  const GridViewServiceListItem({
    required this.service,
    required this.onPressed,
    Key? key,
  }) : super(key: key);

  final Function(Service) onPressed;
  final Service service;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //service image
        Stack(
          children: [
            Hero(
              tag: service.heroTag ?? service.id,
              child: CustomImage(
                imageUrl:
                    "${service.photos.isNotEmpty ? service.photos.first : ''}",
                boxFit: BoxFit.cover,
                width: double.infinity,
                height: Vx.dp64 * 2,
              ),
            ),

            //discount
            Positioned(
              bottom: 0,
              left: Utils.isArabic ? 0 : null,
              right: !Utils.isArabic ? 0 : null,
              child: service.showDiscount
                  ? "${AppStrings.currencySymbol}${service.price - service.discountPrice} ${'Off'.tr()}"
                      .text
                      .white
                      .semiBold
                      .make()
                      .p2()
                      .px4()
                      .box
                      .red500
                      .topRightRounded(value: !Utils.isArabic ? 0 : 10)
                      .topLeftRounded(value: Utils.isArabic ? 0 : 10)
                      .make()
                  : UiSpacer.emptySpace(),
            ),
          ],
        ),

        //
        VStack(
          [
            service.name.text.medium.xl.make(),
            HStack(
              [
                "${AppStrings.currencySymbol}"
                    .text
                    .lg
                    .light
                    .color(AppColor.primaryColor)
                    .make(),
                service.price.text.semiBold
                    .color(AppColor.primaryColor)
                    .xl
                    .make(),
                ("/" + "${service.duration}".tr()).text.medium.xl.make(),
              ],
            ),
          ],
        ).p12(),
      ],
    )
        .box
        .withRounded(value: 10)
        .color(context.cardColor)
        .outerShadow
        .clip(Clip.antiAlias)
        .makeCentered()
        .onInkTap(
          () => this.onPressed(this.service),
        );
  }
}
