import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/order_product.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderProductListItem extends StatelessWidget {
  const OrderProductListItem({
    required this.orderProduct,
    Key? key,
  }) : super(key: key);

  final OrderProduct orderProduct;
  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        //qty
        "x ${orderProduct.quantity}".text.semiBold.make(),
        VStack(
          [
            //
            "${orderProduct.product?.name}".text.medium.make(),
            Visibility(
              visible: orderProduct.options != null &&
                  orderProduct.options!.isNotEmpty,
              child:
                  "${orderProduct.options ?? ''}".text.sm.gray500.medium.make(),
            ),
          ],
        ).px12().expand(),
        "${AppStrings.currencySymbol}${orderProduct.price}"
            .currencyFormat()
            .text
            .semiBold
            .make(),
        //
      ],
    );
  }
}
