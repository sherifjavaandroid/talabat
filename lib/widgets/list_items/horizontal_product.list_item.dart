import 'package:flutter/material.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:fuodz/widgets/states/product_stock.dart';
import 'package:velocity_x/velocity_x.dart';

class HorizontalProductListItem extends StatelessWidget {
  //
  const HorizontalProductListItem(
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
          child: CustomImage(imageUrl: product.photo)
              .wh(Vx.dp40, Vx.dp40)
              .box
              .clip(Clip.antiAlias)
              .roundedSM
              .make(),
        ),

        //Details
        VStack(
          [
            //name
            product.name.text.lg.medium
                .maxLines(2)
                .overflow(TextOverflow.ellipsis)
                .make(),
            "${product.vendor.name}"
                .text
                .sm
                .semiBold
                .maxLines(2)
                .overflow(TextOverflow.ellipsis)
                .make(),
            // //description
            // product.description.text.xs.light
            //     .maxLines(1)
            //     .overflow(TextOverflow.ellipsis)
            //     .make(),
          ],
        ).px12().expand(),

        //
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
            product.showDiscount
                ? CurrencyHStack(
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
                  )
                : UiSpacer.emptySpace(),

            // plus/min icon here
            ProductStockState(
              product,
              qtyUpdated: qtyUpdated,
            ),
          ],
          crossAlignment: CrossAxisAlignment.end,
        ),
      ],
    ).onInkTap(
      onPressed == null ? null : () => onPressed!(product),
    );

    //height set
    if (height != null) {
      widget = widget.h(height!);
    }

    //
    return widget.box.p4.roundedSM
        .color(context.cardColor)
        .outerShadow
        .makeCentered()
        .p(padding ?? 8);
  }
}
