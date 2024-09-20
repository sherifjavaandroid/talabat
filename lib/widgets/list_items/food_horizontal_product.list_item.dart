import 'package:flutter/material.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/tags/product_tags.dart';
import 'package:velocity_x/velocity_x.dart';

class FoodHorizontalProductListItem extends StatelessWidget {
  //
  const FoodHorizontalProductListItem(
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
  final EdgeInsets? padding;
  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;

    //
    Widget widget = HStack(
      [
        //
        CustomImage(
          imageUrl: product.photo,
          width: height != null ? (height! / 1.6) : height,
          height: height,
        ).box.clip(Clip.antiAlias).roundedSM.make(),

        //Details
        VStack(
          [
            //name
            product.name.text.lg.medium
                .maxLines(1)
                .overflow(TextOverflow.ellipsis)
                .make(),
            //description
            //hide this if there is an overflow

            "${product.vendor.name}"
                .text
                .xs
                .light
                .gray600
                .maxLines(1)
                .ellipsis
                .make(),
            //price
            Wrap(
              children: [
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
                5.widthBox,
                //discount price
                CustomVisibilty(
                  visible: product.showDiscount,
                  child: CurrencyHStack(
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
                ),
              ],
            ),

            //
            ProductTags(product),
          ],
        ).px12().expand(),
      ],
    ).onInkTap(
      onPressed == null ? null : () => onPressed!(product),
    );

    //height set
    if (height != null) {
      widget = widget.h(height!);
    }

    //
    return Padding(
      padding: padding ?? const EdgeInsets.all(8.0),
      child: widget.box.p4.roundedSM
          .color(context.cardColor)
          .outerShadow
          .makeCentered(),
    );
  }
}
