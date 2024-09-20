import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class ProductDetailsHeader extends StatelessWidget {
  const ProductDetailsHeader({
    required this.product,
    Key? key,
  }) : super(key: key);

  final Product product;

  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;

    return VStack(
      [
        //product name, vendor name, and price
        HStack(
          [
            //name
            VStack(
              [
                //product name
                product.name.text.xl.semiBold.make(),
                //vendor name
                "${product.vendor?.name}".text.lg.medium.make(),
              ],
            ).expand(),

            //price
            VStack(
              [
                //price
                CurrencyHStack(
                  [
                    currencySymbol.text.lg.bold.make(),
                    (product.showDiscount
                            ? product.discountPrice
                            : product.price)
                        .currencyValueFormat()
                        .text
                        .xl2
                        .bold
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
              ],
            ),
          ],
        ),

        //product size details and more
        HStack(
          [
            //deliverable or not
            (product.canBeDelivered
                    ? "Deliverable".tr()
                    : "Not Deliverable".tr())
                .text
                .white
                .sm
                .make()
                .py4()
                .px8()
                .box
                .roundedLg
                .color(
                  product.canBeDelivered ? Vx.green500 : Vx.red500,
                )
                .make(),

            //
            UiSpacer.expandedSpace(),

            //size
            if (product.capacity != null)
              "${product.capacity ?? ''} ${product.unit}"
                  .text
                  .sm
                  .black
                  .make()
                  .py4()
                  .px8()
                  .box
                  .roundedLg
                  .gray500
                  .make()
                  .pOnly(right: Vx.dp12),

            //package items
            if (product.packageCount != null)
              "%s Items"
                  .tr()
                  .fill(["${product.packageCount}"])
                  .text
                  .sm
                  .black
                  .make()
                  .py4()
                  .px8()
                  .box
                  .roundedLg
                  .gray500
                  .make(),
          ],
        ).pOnly(top: Vx.dp10),
      ],
    ).px20().py12();
  }
}
