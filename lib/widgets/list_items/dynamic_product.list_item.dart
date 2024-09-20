import 'package:flutter/material.dart';
import 'package:fuodz/models/product.dart';
import 'package:fuodz/widgets/list_items/food_horizontal_product.list_item.dart';

class DynamicProductListItem extends StatelessWidget {
  const DynamicProductListItem(
    this.product, {
    this.onPressed,
    this.h,
    this.padding,
    Key? key,
  }) : super(key: key);

  final Product product;
  final Function(Product)? onPressed;
  final double? h;
  final EdgeInsets? padding;
  @override
  Widget build(BuildContext context) {
    return FoodHorizontalProductListItem(
      product,
      height: h ?? 90,
      qtyUpdated: null,
      onPressed: onPressed,
      padding: padding,
    );
    // return product.vendor.vendorType.isFood
    //     ? FoodHorizontalProductListItem(
    //         product,
    //         height: 90,
    //         qtyUpdated: null,
    //         onPressed: onPressed,
    //       )
    //     : product.vendor.vendorType.isCommerce
    //         ? CommerceProductListItem(product, height: 100)
    //         : ProductListItem(product: product, qtyUpdated: null);
  }
}
