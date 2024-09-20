import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:velocity_x/velocity_x.dart';

class TaxiCustomTextFormField extends StatelessWidget {
  const TaxiCustomTextFormField({
    required this.hintText,
    required this.focusNode,
    required this.controller,
    required this.onChanged,
    this.clear = false,
    Key? key,
  }) : super(key: key);
  final String hintText;
  final FocusNode focusNode;
  final TextEditingController controller;
  final Function(String)? onChanged;
  final bool clear;

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      decoration: InputDecoration(
        hintText: hintText,
        border: InputBorder.none,
        contentPadding: EdgeInsets.symmetric(horizontal: 12),
        suffix: clear
            ? Icon(
                FlutterIcons.close_ant,
                color: Colors.red.shade300,
              ).onInkTap(() {
                controller.clear();
              })
            : null,
      ),
      autofocus: false,
      maxLines: 1,
      controller: controller,
      focusNode: focusNode,
      onChanged: onChanged,
    )
        .box
        // .color(
        //   focusNode.hasFocus ? context.theme.colorScheme.surface : Colors.grey.shade200,
        // )
        .withRounded(value: 5)
        .clip(Clip.antiAlias)
        .border(
          color:
              focusNode.hasFocus ? AppColor.primaryColor : Colors.grey.shade200,
          width: 1.5,
        )
        .make();
  }
}
