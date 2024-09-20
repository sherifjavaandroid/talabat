import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:velocity_x/velocity_x.dart';

class TimeTag extends StatelessWidget {
  const TimeTag(this.value, {required this.iconData, Key? key})
      : super(key: key);

  final String? value;
  final IconData iconData;
  @override
  Widget build(BuildContext context) {
    //delivery time
    return Visibility(
      visible: value != null,
      child: HStack(
        [
          //icon
          Icon(
            iconData,
            size: 12,
          ),
          UiSpacer.smHorizontalSpace(space: 2),

          //
          "${value}".text.minFontSize(6).size(10).medium.maxLines(1).make(),
        ],
      )
          .py2()
          .px8()
          .box
          .color(context.theme.colorScheme.surface)
          .rounded
          .outerShadow
          .make(),
    );
  }
}
