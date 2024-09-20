import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/services/cart.service.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/view_models/home.vm.dart';
import 'package:velocity_x/velocity_x.dart';

class CartHomeFab extends StatelessWidget {
  const CartHomeFab(this.model, {Key? key}) : super(key: key);

  final HomeViewModel model;

  @override
  Widget build(BuildContext context) {
    return FloatingActionButton(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
      backgroundColor: AppColor.primaryColorDark,
      materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
      onPressed: model.openCart,
      child: StreamBuilder(
        stream: CartServices.cartItemsCountStream.stream,
        initialData: CartServices.productsInCart.length,
        builder: (BuildContext context, AsyncSnapshot snapshot) {
          return Icon(
            FlutterIcons.shopping_cart_faw,
            color: Colors.white,
          ).badge(
            position: Utils.isArabic
                ? VxBadgePosition.leftTop
                : VxBadgePosition.rightTop,
            count: snapshot.data,
            color: Colors.white,
            textStyle: context.textTheme.bodyLarge?.copyWith(
              color: AppColor.primaryColor,
              fontSize: 10,
            ),
          );
        },
      ),
    );
    // : SizedBox(
    //     height: 40,
    //     child: FloatingActionButton.extended(
    //       backgroundColor: AppColor.primaryColorDark,
    //       materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
    //       onPressed: model.openCart,
    //       icon: Icon(
    //         FlutterIcons.shopping_cart_faw,
    //         color: Colors.white,
    //       ).badge(
    //         position: Utils.isArabic
    //             ? VxBadgePosition.leftTop
    //             : VxBadgePosition.rightTop,
    //         count: model.totalCartItems,
    //         color: Colors.white,
    //         textStyle: context.textTheme.bodyLarge?.copyWith(
    //           color: AppColor.primaryColor,
    //           fontSize: 10,
    //         ),
    //       ),
    //       label: "Cart".tr().text.white.make(),
    //     ),
    //   );
  }
}
