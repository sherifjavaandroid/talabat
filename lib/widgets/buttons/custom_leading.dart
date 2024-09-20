import 'package:eva_icons_flutter/eva_icons_flutter.dart';
import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class CustomLeading extends StatelessWidget {
  const CustomLeading({
    this.size,
    this.color,
    this.padding,
    this.bgColor,
    Key? key,
  }) : super(key: key);

  final double? size;
  final Color? color;
  final Color? bgColor;
  final double? padding;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 14),
      child: Icon(
        Utils.isArabic ? EvaIcons.arrowForward : EvaIcons.arrowBack,
        size: size ?? 20,
        color: color ?? Utils.textColorByTheme(),
      )
          .p(padding ?? 4)
          .onInkTap(() {
            context.pop();
          })
          .box
          .shadowSm
          .roundedSM
          .clip(Clip.antiAlias)
          .color(bgColor ?? AppColor.primaryColor)
          .make(),
    );
  }
}
