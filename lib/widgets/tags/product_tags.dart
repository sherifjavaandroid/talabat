import 'package:flutter/material.dart';
import 'package:fuodz/models/product.dart';
import 'package:velocity_x/velocity_x.dart';

class ProductTags extends StatelessWidget {
  const ProductTags(this.product, {super.key});
  final Product product;
  @override
  Widget build(BuildContext context) {
    //
    if (product.tags == null || product.tags!.isEmpty) {
      return 0.heightBox;
    }
    return Column(
      children: [
        5.heightBox,
        Wrap(
          children: [
            ...(product.tags!.map((tag) {
              return Container(
                padding: EdgeInsets.symmetric(horizontal: 6, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.grey.shade300,
                  borderRadius: BorderRadius.circular(2),
                ),
                child: "${tag.name}".text.xs.make(),
              );
            }).toList())
          ],
          spacing: 8,
        ),
        5.heightBox,
      ],
    );
  }
}
