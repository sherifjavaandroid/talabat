import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class FeaturedVendorListItem extends StatelessWidget {
  const FeaturedVendorListItem({
    required this.vendor,
    required this.onPressed,
    Key? key,
  }) : super(key: key);

  final Vendor vendor;
  final Function(Vendor) onPressed;
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        Stack(
          children: [
            //
            Hero(
              tag: vendor.heroTag ?? vendor.id,
              child: CustomImage(
                imageUrl: vendor.featureImage,
                height: 100,
                width: context.screenWidth,
              ),
            ),

            //closed
            Positioned(
              child: Visibility(
                visible: !vendor.isOpen,
                child: VxBox(
                  child: "Closed".tr().text.lg.white.bold.makeCentered(),
                )
                    .color(
                      AppColor.closeColor.withOpacity(0.6),
                    )
                    .make(),
              ),
              bottom: 0,
              right: 0,
              left: 0,
              top: 0,
            ),
          ],
        ),

        //name
        vendor.name.text.sm.medium
            .maxLines(1)
            .overflow(TextOverflow.ellipsis)
            .make()
            .px8()
            .pOnly(top: Vx.dp8),
        //
        //description
        "${vendor.description}"
            .text
            .gray400
            .minFontSize(9)
            .size(9)
            .maxLines(1)
            .overflow(TextOverflow.ellipsis)
            .make()
            .px8(),
        //words
        Wrap(
          spacing: Vx.dp12,
          children: [
            //rating
            HStack(
              [
                "${vendor.rating.numCurrency} "
                    .text
                    .minFontSize(6)
                    .size(10)
                    .color(AppColor.ratingColor)
                    .medium
                    .make(),
                Icon(
                  FlutterIcons.star_ent,
                  color: AppColor.ratingColor,
                  size: 10,
                ),
              ],
            ),

            //
            //
            Visibility(
              visible: vendor.distance != null,
              child: HStack(
                [
                  Icon(
                    FlutterIcons.direction_ent,
                    color: AppColor.primaryColor,
                    size: 10,
                  ),
                  " ${vendor.distance?.numCurrency}km"
                      .text
                      .minFontSize(6)
                      .size(10)
                      .make(),
                ],
              ),
            ),
          ],
        ).px8(),

        //delivery fee && time
        Wrap(
          spacing: Vx.dp12,
          children: [
            //
            Visibility(
              visible: vendor.minOrder != null,
              child: CurrencyHStack(
                [
                  "${AppStrings.currencySymbol}"
                      .text
                      .minFontSize(6)
                      .size(10)
                      .gray600
                      .medium
                      .maxLines(1)
                      .make(),
                  //
                  Visibility(
                    visible: vendor.minOrder != null,
                    child: "${vendor.minOrder}"
                        .text
                        .minFontSize(6)
                        .size(10)
                        .gray600
                        .medium
                        .maxLines(1)
                        .make(),
                  ),
                  //
                  Visibility(
                    visible: vendor.minOrder != null && vendor.maxOrder != null,
                    child: " - "
                        .text
                        .minFontSize(6)
                        .size(10)
                        .gray600
                        .medium
                        .maxLines(1)
                        .make(),
                  ),
                  //
                  Visibility(
                    visible: vendor.maxOrder != null,
                    child: "${vendor.maxOrder} "
                        .text
                        .minFontSize(6)
                        .size(10)
                        .gray600
                        .medium
                        .maxLines(1)
                        .make(),
                  ),
                ],
              ),
            ),
          ],
        ).px8(),
        5.heightBox,
      ],
    )
        .onInkTap(
          () => this.onPressed(this.vendor),
        )
        .box
        .outerShadow
        .color(context.theme.colorScheme.surface)
        .clip(Clip.antiAlias)
        .withRounded(value: 5)
        .make()
        .pOnly(bottom: 5);
  }
}
