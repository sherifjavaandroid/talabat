import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:velocity_x/velocity_x.dart';

class CustomImage extends StatelessWidget {
  const CustomImage({
    required this.imageUrl,
    this.height = Vx.dp40,
    this.width,
    this.boxFit,
    this.hideDefaultImg = false,
    Key? key,
  }) : super(key: key);

  final String imageUrl;
  final double height;
  final double? width;
  final BoxFit? boxFit;
  final bool hideDefaultImg;

  @override
  Widget build(BuildContext context) {
    if (hideDefaultImg && !imageUrl.isNotDefaultImage) {
      return 0.widthBox;
    }

    //if default image
    if (!imageUrl.isNotDefaultImage) {
      return Image.asset(
        AppImages.appLogo,
        fit: BoxFit.cover,
        height: height,
        width: width,
      );
    }

    return CachedNetworkImage(
      imageUrl: this.imageUrl,
      fit: this.boxFit ?? BoxFit.cover,
      errorWidget: (context, imageUrl, _) => Image.asset(
        AppImages.noImage,
        fit: this.boxFit ?? BoxFit.cover,
      ),
      progressIndicatorBuilder: (context, imageURL, progress) {
        return Image.asset(
          AppImages.placeholder,
          fit: BoxFit.cover,
          height: height,
          width: width,
        ).shimmer(
          primaryColor: Colors.grey.shade200,
          secondaryColor: Colors.grey.shade50,
        );
      },
    ).h(this.height).w(this.width ?? context.percentWidth);
  }
}
