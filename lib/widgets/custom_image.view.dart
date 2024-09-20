import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/extensions/string.dart';
import 'package:fuodz/views/shared/full_image_preview.page.dart';
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

    //
    Widget imageView = CachedNetworkImage(
      imageUrl: this.widget.imageUrl,
      errorWidget: (context, imageUrl, _) => Image.asset(
        AppImages.noImage,
        fit: this.widget.boxFit ?? BoxFit.cover,
      ),
      fit: this.widget.boxFit ?? BoxFit.cover,
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
      width: this.widget.width,
    );

    //
    //if zooming is allowed, navigate to full image preview page
    if (this.widget.canZoom) {
      imageView = GestureDetector(
        onTap: () {
          context.push(
            (context) => FullImagePreviewPage(
              this.widget.imageUrl,
              boxFit: this.widget.boxFit ?? BoxFit.cover,
            ),
          );
        },
        child: imageView,
      );
    }

    return imageView;
  }

  @override
  bool get wantKeepAlive => true;
}
