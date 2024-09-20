import 'package:flutter/material.dart';

import 'package:velocity_x/velocity_x.dart';

class CustomHScrollView extends StatelessWidget {
  const CustomHScrollView({
    super.key,
    required this.itemCount,
    required this.itemBuilder,
    this.hideEmpty = true,
    this.isLoading = false,
    this.itemSpacing,
    this.itemWidth,
    this.padding,
  });

  final bool hideEmpty;
  final bool isLoading;
  final int itemCount;
  final double? itemSpacing;
  final EdgeInsets? padding;
  final double? itemWidth;
  final Widget Function(BuildContext context, int index) itemBuilder;

  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        0.widthBox,
        for (int i = 0; i < itemCount; i++)
          itemBuilder(context, i).w(itemWidth ?? context.screenWidth),
        //add spacing at the end
        0.widthBox,
      ],
      crossAlignment: CrossAxisAlignment.start,
      alignment: MainAxisAlignment.start,
      spacing: itemSpacing,
    ).scrollHorizontal(padding: padding);
  }
}
