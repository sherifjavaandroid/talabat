import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/widgets/custom_image.view.dart';
import 'package:velocity_x/velocity_x.dart';

class ManageProductListItem extends StatelessWidget {
  //
  const ManageProductListItem(
    this.product, {
    this.isLoading = false,
    required this.onPressed,
    required this.onEditPressed,
    required this.onToggleStatusPressed,
    required this.onDeletePressed,
    Key? key,
  }) : super(key: key);

  //
  final Product product;
  final bool isLoading;
  final Function(Product) onPressed;
  final Function(Product) onEditPressed;
  final Function(Product) onToggleStatusPressed;
  final Function(Product) onDeletePressed;
  @override
  Widget build(BuildContext context) {
    //
    final currencySymbol = AppStrings.currencySymbol;

    //
    return HStack(
      [
        //
        CustomImage(
          imageUrl: product.photo,
          width: context.percentWidth * 18,
          height: context.percentWidth * 14,
        ).box.clip(Clip.antiAlias).roundedSM.make(),

        //Details
        VStack(
          [
            //name
            product.name.text.scale(0.95).semiBold.maxLines(1).ellipsis.make(),
            //
            HStack(
              [
                //discount
                if (product.showDiscount)
                  "$currencySymbol ${product.price}"
                      .currencyFormat()
                      .text
                      .lineThrough
                      .xs
                      .make(),

                //price
                "$currencySymbol ${product.sellPrice}"
                    .currencyFormat()
                    .text
                    .scale(0.90)
                    .semiBold
                    .make(),
              ],
              spacing: 10,
            ),
          ],
          spacing: 2,
        ).expand(),

        //actions
        Container(
          constraints: BoxConstraints(
            maxWidth: context.percentWidth * 24,
          ),
          child: Wrap(
            children: [
              IconButton(
                iconSize: 18,
                padding: EdgeInsets.zero,
                color: context.primaryColor,
                onPressed: () => onEditPressed(product),
                icon: Icon(
                  FlutterIcons.edit_2_fea,
                ),
              ),
              // IconButton(
              //   iconSize: 18,
              //   padding: EdgeInsets.zero,
              //   onPressed: () => onToggleStatusPressed(product),
              //   color: product.isActive != 1 ? Colors.green : Colors.red[400],
              //   icon: Icon(
              //     product.isActive != 1
              //         ? FlutterIcons.check_ant
              //         : FlutterIcons.close_ant,
              //   ),
              // ),
              IconButton(
                iconSize: 18,
                padding: EdgeInsets.zero,
                onPressed: () => onDeletePressed(product),
                color: Colors.red,
                icon: Icon(
                  FlutterIcons.trash_fea,
                ),
              ),
            ],
            spacing: 0,
          ),
        ),
        //
      ],
      spacing: 15,
    ).onInkTap(() => onPressed(product)).card.make();
  }
}
