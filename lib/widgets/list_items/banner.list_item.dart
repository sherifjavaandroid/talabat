import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:velocity_x/velocity_x.dart';

class BannerListItem extends StatelessWidget {
  const BannerListItem({
    required this.imageUrl,
    this.onPressed,
    this.radius = 4,
    this.noMargin = false,
    Key? key,
  }) : super(key: key);

  final String imageUrl;
  final double radius;
  final bool noMargin;
  final Function? onPressed;
  @override
  Widget build(BuildContext context) {
    return CachedNetworkImage(
      imageUrl: this.imageUrl,
      fit: BoxFit.cover,
      width: double.infinity,
    )
        .onInkTap(this.onPressed != null ? () => this.onPressed!() : null)
        .box
        .withRounded(value: radius)
        .clip(Clip.antiAlias)
        .margin(
          EdgeInsets.symmetric(horizontal: noMargin ? 0.00 : 5.0),
        )
        .make();
  }
}
