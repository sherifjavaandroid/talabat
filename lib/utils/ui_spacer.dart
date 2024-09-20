import 'package:custom_clippers/custom_clippers.dart';
import 'package:flutter/material.dart';
import 'package:velocity_x/velocity_x.dart';

class UiSpacer {
  //space between widgets vertically
  static Widget verticalSpace({double space = 20}) => SizedBox(height: space);
  static Widget hSpace([double space = 20]) => SizedBox(width: space);
  static Widget vSpace([double space = 10]) => SizedBox(height: space);

  //space between widgets horizontally
  static Widget horizontalSpace({double space = 20}) => SizedBox(width: space);

  static Widget formVerticalSpace({double space = 15}) =>
      SizedBox(height: space);

  static Widget emptySpace() => SizedBox.shrink();
  static Widget expandedSpace() => Expanded(
        child: SizedBox.shrink(),
      );

  static Widget divider({double height = 1, double thickness = 1}) => Divider(
        height: height,
        thickness: thickness,
      );

  //
  static Widget cutDivider({
    Color? color,
  }) =>
      ClipPath(
        clipper: MultiplePointsClipper(Sides.bottom,
            heightOfPoint: 5, numberOfPoints: 40),
        child: SizedBox(
          height: 8,
          width: double.infinity,
        ).box.color(color ?? Vx.gray200).make(),
      );
}
