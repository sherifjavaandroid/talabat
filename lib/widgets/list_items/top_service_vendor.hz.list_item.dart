import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class TopServiceVendorHorizontalListItem extends StatelessWidget {
  const TopServiceVendorHorizontalListItem({
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
        HStack(
          [
            CustomImage(
              imageUrl: vendor.logo,
              height: 60,
              width: 60,
            ).box.rounded.clip(Clip.antiAlias).make(),
            UiSpacer.vSpace(6),
            //info
            VStack(
              [
                //name
                vendor.name.text.lg.semiBold.maxLines(1).ellipsis.make(),
                //
                HStack(
                  [
                    //rating
                    VxRating(
                      maxRating: 5.0,
                      value: double.parse(vendor.rating.toString()),
                      isSelectable: false,
                      onRatingUpdate: (value) {},
                      selectionColor: AppColor.ratingColor,
                      size: 14,
                    ),
                    //number of reviews
                    ("${vendor.reviews_count} " + "Reviews".tr()).text.make(),
                  ],
                  spacing: 5,
                ),

                //address
                HStack(
                  [
                    //location icon
                    Icon(
                      Icons.location_on,
                      size: 14,
                      color: context.theme.colorScheme.primary,
                    ),
                    vendor.address.text.maxLines(1).ellipsis.make().expand(),
                  ],
                  spacing: 5,
                ),
              ],
            ).expand(),
          ],
          alignment: MainAxisAlignment.start,
          crossAlignment: CrossAxisAlignment.start,
          spacing: 6,
        ),

        //description: max 1 line
        vendor.description.text.maxLines(1).ellipsis.make(),
      ],
      crossAlignment: CrossAxisAlignment.center,
      alignment: MainAxisAlignment.center,
      spacing: 8,
    )
        .p(10)
        .onInkTap(
          () => this.onPressed(this.vendor),
        )
        .box
        .color(context.cardColor)
        .outerShadowSm
        .clip(Clip.antiAlias)
        .roundedSM
        .make();
  }
}
