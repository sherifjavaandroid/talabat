import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class CloseTag extends StatelessWidget {
  const CloseTag({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return "Closed"
        .tr()
        .text
        .sm
        .color(Utils.isDark(AppColor.closeColor) ? Colors.white : Colors.black)
        .make()
        .py2()
        .px8()
        .box
        .roundedLg
        .color(AppColor.closeColor)
        .make();
  }
}
