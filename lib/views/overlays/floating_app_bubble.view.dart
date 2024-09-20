import 'package:app_to_foreground/app_to_foreground.dart';
import 'package:flutter/material.dart';
import 'package:flutter_overlay_window/flutter_overlay_window.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/models/new_order.dart';
import 'package:fuodz/models/new_taxi_order.dart';
import 'package:velocity_x/velocity_x.dart';

class FloatingAppBubble extends StatefulWidget {
  const FloatingAppBubble({Key? key}) : super(key: key);

  @override
  State<FloatingAppBubble> createState() => _FloatingAppBubbleState();
}

class _FloatingAppBubbleState extends State<FloatingAppBubble> {
  NewOrder? newOrder;
  NewTaxiOrder? newTaxiOrder;
  Widget currentWidget = SizedBox.shrink();

  @override
  void initState() {
    super.initState();
    FlutterOverlayWindow.overlayListener.listen(
      (event) {
        print("event: $event");
        //
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Image.asset(AppImages.appLogo)
        .box
        .roundedFull
        .clip(Clip.antiAlias)
        .make()
        .onTap(
      () {
        AppToForeground.appToForeground();
      },
    );
  }
}
