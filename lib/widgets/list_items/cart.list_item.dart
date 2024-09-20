import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/models/cart.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/widgets/buttons/qty_stepper.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class CartListItem extends StatelessWidget {
  const CartListItem(
    this.cart, {
    required this.onQuantityChange,
    this.deleteCartItem,
    Key? key,
  }) : super(key: key);

  final Cart cart;
  final Function(int) onQuantityChange;
  final Function? deleteCartItem;

  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;

    return Stack(
      children: [
        HStack(
          [
            //
            //PRODUCT IMAGE
            CustomImage(
              imageUrl: cart.product!.photo,
              width: context.percentWidth * 12,
              height: context.percentWidth * 12,
            ).box.clip(Clip.antiAlias).roundedSM.make(),
            VStack(
              [
                VStack(
                  [
                    //product name
                    "${cart.product?.name}"
                        .text
                        .medium
                        .lg
                        .maxLines(4)
                        .ellipsis
                        .make(),
                    // product rating
                    HStack(
                      [
                        VxRating(
                          value: cart.product?.rating ?? 0,
                          isSelectable: false,
                          count: 5,
                          size: 12,
                          selectionColor: AppColor.primaryColor,
                          onRatingUpdate: (value) {},
                        ),
                        "(${cart.product?.reviewsCount ?? 0})".text.xs.make(),
                      ],
                      spacing: 5,
                    ),

                    //cart item price
                    ("$currencySymbol" +
                            "${cart.price ?? cart.product!.sellPrice}")
                        .currencyFormat()
                        .text
                        .semiBold
                        .make(),
                  ],
                  spacing: 2,
                ),
                //product options
                if (cart.optionsSentence.isNotEmpty)
                  cart.optionsSentence.text.sm.gray600.make(),
              ],
              spacing: 6,
            ).expand(),
            //qty stepper
            SizedBox(
              height: 46,
              width: context.percentWidth * 20,
              child: FittedBox(
                child: QtyStepper(
                  defaultValue: cart.selectedQty ?? 1,
                  min: 0,
                  max: cart.product?.availableQty ?? 20,
                  disableInput: true,
                  onChange: onQuantityChange,
                  valueSize: 26,
                )
                    .box
                    .color(context.theme.colorScheme.surface)
                    .roundedSM
                    .outerShadow
                    .clip(Clip.antiAlias)
                    .make(),
              ),
            ),
          ],
          spacing: 10,
          alignment: MainAxisAlignment.start,
          crossAlignment: CrossAxisAlignment.start,
        )
            .px12()
            .py(8)
            .box
            .roundedSM
            .outerShadowSm
            .color(context.theme.colorScheme.surface)
            .make(),

        //
        //delete icon
        Icon(
          FlutterIcons.x_fea,
          size: 16,
          color: Colors.white,
        )
            .p(6)
            .onInkTap(
              this.deleteCartItem != null ? () => this.deleteCartItem!() : null,
            )
            .box
            .roundedSM
            .color(Colors.red)
            .make()
            .positioned(
              top: 0,
              left: 0,
            ),
      ],
    );
  }
}
