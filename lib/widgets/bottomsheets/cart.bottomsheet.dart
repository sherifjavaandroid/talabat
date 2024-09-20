import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/views/pages/cart/cart.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class CartViewBottomSheet extends StatelessWidget {
  const CartViewBottomSheet({super.key});

  @override
  Widget build(BuildContext context) {
    if (!AppUISettings.showCart) {
      return SizedBox.shrink();
    }
    //
    return StreamBuilder<int>(
      stream: CartServices.cartItemsCountStream.stream,
      initialData: CartServices.productsInCart.length,
      builder: (context, snapshot) {
        //
        if (!snapshot.hasData || snapshot.data == 0) {
          return SizedBox.shrink();
        }
        //
        return Container(
          //add top shadow, padding and background color
          decoration: BoxDecoration(
            color: Colors.white,
            boxShadow: [
              BoxShadow(
                color: Colors.grey.withOpacity(0.25),
                spreadRadius: 7,
                blurRadius: 10,
                offset: Offset(0, 1),
              ),
            ],
          ),
          padding: EdgeInsets.all(20),
          width: double.infinity,
          child: HStack(
            [
              // details
              VStack(
                [
                  "Item: %s".tr().fill([2]).text.medium.make(),
                  "Total: %s"
                      .tr()
                      .fill(
                        [
                          "${AppStrings.currencySymbol} ${CartServices.totalSubtotal}"
                              .currencyFormat(),
                        ],
                      )
                      .text
                      .semiBold
                      .lg
                      .color(AppColor.primaryColor)
                      .make(),
                ],
              ).expand(),

              // view cart button
              CustomButton(
                title: "View Cart",
                onPressed: () {
                  Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (context) => CartPage(),
                    ),
                  );
                },
              ),
            ],
            spacing: 20,
          ),
        );
      },
    );
  }
}
