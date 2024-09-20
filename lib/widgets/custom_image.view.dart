import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/views/pages/shared/full_image_preview.page.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class CustomImage extends StatefulWidget {
  CustomImage({
    required this.imageUrl,
    this.height = Vx.dp40,
    this.width,
    this.boxFit,
    this.canZoom = false,
    this.hideDefaultImg = false,
    Key? key,
  }) : super(key: key);

  final String imageUrl;
  final double? height;
  final double? width;
  final BoxFit? boxFit;
  final bool canZoom;
  final bool hideDefaultImg;

  @override
  State<CustomImage> createState() => _CustomImageState();
}

class _CustomImageState extends State<CustomImage>
    with AutomaticKeepAliveClientMixin {
  @override
  Widget build(BuildContext context) {
    super.build(context);

    if (this.widget.hideDefaultImg && !this.widget.imageUrl.isNotDefaultImage) {
      return 0.widthBox;
    }

    //if default image
    if (!this.widget.imageUrl.isNotDefaultImage) {
      return Image.asset(
        AppImages.appLogo,
        fit: BoxFit.cover,
        height: this.widget.height,
        width: this.widget.width,
      );
    }

    return CachedNetworkImage(
      imageUrl: this.widget.imageUrl,
      fit: this.widget.boxFit ?? BoxFit.cover,
      errorWidget: (context, imageUrl, _) => Image.asset(
        AppImages.placeholder,
        fit: BoxFit.scaleDown,
      ),
      progressIndicatorBuilder: (context, imageURL, progress) {
        // return BusyIndicator().centered();
        return Image.asset(
          AppImages.placeholder,
          fit: BoxFit.cover,
          height: this.widget.height,
          width: this.widget.width,
        ).shimmer(
          primaryColor: Colors.grey.shade200,
          secondaryColor: Colors.grey.shade50,
        );
      },
      height: this.widget.height,
      width: this.widget.width ?? context.percentWidth,
    ).onInkTap(this.widget.canZoom
        ? () {
            //if zooming is allowed
            if (this.widget.canZoom) {
              context.push(
                (context) => FullImagePreviewPage(
                  this.widget.imageUrl,
                  boxFit: this.widget.boxFit ?? BoxFit.cover,
                ),
              );
            }
          }
        : null);
  }

  @override
  bool get wantKeepAlive => true;
}
