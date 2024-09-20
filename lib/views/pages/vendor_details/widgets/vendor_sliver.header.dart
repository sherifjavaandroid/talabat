import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/vendor.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorDetailsSliverHeader extends StatelessWidget {
  VendorDetailsSliverHeader({
    super.key,
    required this.vendor,
    this.scrollingRate = 1,
    this.onSharePressed,
    this.onFavoritePressed,
  });
  final Vendor vendor;
  final double scrollingRate;
  final void Function(Vendor)? onSharePressed;
  final void Function(Vendor)? onFavoritePressed;
  @override
  Widget build(BuildContext context) {
    double reducedSize = 40;
    final containerRadius = 16 - (scrollingRate * 16);
    double titleTextSize = Vx.dp16;
    titleTextSize -= scrollingRate *
        titleTextSize.percentage(
          reducedSize,
        );
    double subtitleTextSize = Vx.dp8;
    subtitleTextSize -= scrollingRate *
        subtitleTextSize.percentage(
          reducedSize,
        );
    final mLogoSize = 50.0;
    final logoSize =
        mLogoSize - (scrollingRate * mLogoSize.percentage(reducedSize));
    //GET WIDTH OF LEADING ICON
    final leadingIconWidth = kToolbarHeight - 5;
    double padding = 12;
    double leftPadding = padding + (scrollingRate * leadingIconWidth);
    //spacing

    double itemSpacing = 12;
    itemSpacing -= scrollingRate *
        itemSpacing.percentage(
          reducedSize,
        );

    return Container(
      // height: 145,
      padding: EdgeInsets.fromLTRB(leftPadding, padding, padding, padding),
      width: double.infinity,
      decoration: BoxDecoration(
        color: Theme.of(context).colorScheme.surface,
        borderRadius: BorderRadius.vertical(
          top: Radius.circular(containerRadius),
        ),
      ),
      child: HStack(
        [
          //logo
          CustomImage(
            imageUrl: vendor.logo,
            height: logoSize,
            width: logoSize * 1.2,
            boxFit: BoxFit.cover,
          ).box.roundedSM.clip(Clip.antiAlias).make(),
          //
          VStack(
            [
              vendor.name.text
                  .size(titleTextSize)
                  .color(Utils.textColorByBrightness(context))
                  .bold
                  .make(),
              vendor.address.text
                  .size(subtitleTextSize)
                  .color(Utils.textColorByBrightness(context))
                  .maxLines(1)
                  .ellipsis
                  .make(),
            ],
          ).expand(),

          //icons: share, cart, favorite
          HStack(
            [
              //use iconbutton
              IconButton(
                onPressed: () => onSharePressed?.call(vendor),
                icon: Icon(
                  Icons.share,
                  color: Utils.textColorByBrightness(context),
                ),
              ),

              //use iconbutton favorite
              // PageCartAction(),
            ],
          ),
        ],
        spacing: itemSpacing,
      ),
    );
  }
}
