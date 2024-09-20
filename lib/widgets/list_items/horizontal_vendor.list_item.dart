import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class HorizontalVendorListItem extends StatelessWidget {
  //
  const HorizontalVendorListItem(
    this.vendor, {
    required this.onPressed,
    Key? key,
  }) : super(key: key);

  //
  final Vendor vendor;
  final Function(Vendor) onPressed;
  @override
  Widget build(BuildContext context) {
    //
    return HStack(
      [
        //
        Hero(
          tag: vendor.heroTag ?? vendor.id,
          child: CustomImage(imageUrl: vendor.logo)
              .wh(Vx.dp64, Vx.dp64)
              .box
              .clip(Clip.antiAlias)
              .roundedSM
              .make(),
        ),

        //Details
        VStack(
          [
            //name
            vendor.name.text.lg.medium
                .maxLines(2)
                .overflow(TextOverflow.ellipsis)
                .make(),
            //description
            vendor.description.text.sm.medium
                .maxLines(1)
                .overflow(TextOverflow.ellipsis)
                .make(),
          ],
        ).px12().expand(),

        //rating
        HStack(
          [
            Icon(
              FlutterIcons.star_ant,
              size: 15,
              color: Colors.yellow[800],
            ).pOnly(right: 2),
            vendor.rating.text.xl.make(),
          ],
          crossAlignment: CrossAxisAlignment.center,
          alignment: MainAxisAlignment.center,
        ).pOnly(right: 20),
      ],
    )
        .wFull(context)
        .onInkTap(() => onPressed(vendor))
        .box
        .p4
        .roundedSM
        .color(context.cardColor)
        .outerShadow
        .makeCentered()
        .p8();
  }
}
