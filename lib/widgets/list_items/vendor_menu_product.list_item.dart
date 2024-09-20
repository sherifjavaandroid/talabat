import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class VendorMenuProductListItem extends StatelessWidget {
  //
  const VendorMenuProductListItem(
    this.product, {
    this.onPressed,
    required this.qtyUpdated,
    this.height,
    this.padding,
    Key? key,
  }) : super(key: key);

  //
  final Product product;
  final Function(Product)? onPressed;
  final Function(Product, int)? qtyUpdated;
  final double? height;
  final double? padding;
  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;

    //
    Widget widget = HStack(
      [
        //
        Hero(
          tag: product.heroTag ?? product.id,
          child: CustomImage(
            imageUrl: product.photo,
            width: context.screenWidth * 0.15,
            height: context.screenWidth * 0.15,
            boxFit: BoxFit.cover,
          ),
        ),

        //Details
        VStack(
          [
            //name
            product.name.text.semiBold.maxLines(2).ellipsis.make(),
            product.vendor.name.text.gray300.sm.ellipsis.make(),
            // ratings
            HStack(
              [
                // ratings
                RatingBar(
                  itemSize: 11,
                  initialRating: product.rating ?? 0,
                  ignoreGestures: true,
                  ratingWidget: RatingWidget(
                    full: Icon(
                      FlutterIcons.ios_star_ion,
                      size: 12,
                      color: Colors.yellow[800],
                    ),
                    half: Icon(
                      FlutterIcons.ios_star_half_ion,
                      size: 12,
                      color: Colors.yellow[800],
                    ),
                    empty: Icon(
                      FlutterIcons.ios_star_ion,
                      size: 12,
                      color: Colors.grey.shade400,
                    ),
                  ),
                  onRatingUpdate: (value) {},
                ),
                //review count
                "(${product.reviewsCount})".text.gray400.sm.make(),
              ],
              crossAlignment: CrossAxisAlignment.center,
              alignment: MainAxisAlignment.start,
              spacing: 5,
            ),
            // capacity
            if (product.unit != null)
              "(${product.unit})".text.gray400.sm.make(),
          ],
        ).expand(),
        // pricing
        VStack(
          [
            //price
            CurrencyHStack(
              [
                currencySymbol.text.sm.make(),
                (product.showDiscount
                        ? product.discountPrice.currencyValueFormat()
                        : product.price.currencyValueFormat())
                    .text
                    .lg
                    .semiBold
                    .make(),
              ],
              crossAlignment: CrossAxisAlignment.end,
            ),
            //discount
            if (product.showDiscount)
              CurrencyHStack(
                [
                  currencySymbol.text.lineThrough.xs.make(),
                  product.price
                      .currencyValueFormat()
                      .text
                      .lineThrough
                      .lg
                      .medium
                      .make(),
                ],
              ),
          ],
        ),
      ],
      spacing: 14,
    ).onInkTap(
      onPressed == null ? null : () => onPressed!(product),
    );

    //height set
    if (height != null) {
      widget = widget.h(height!);
    }

    //
    return widget.box.p4
        .withRounded(value: 5)
        .color(context.cardColor)
        .withShadow(
          [
            BoxShadow(
              color: AppColor.primaryColor.withOpacity(0.3),
              blurRadius: 4,
              spreadRadius: 0.4,
              offset: Offset(0, 2),
            ),
          ],
        )
        .makeCentered()
        .p(padding ?? 8);
  }
}
