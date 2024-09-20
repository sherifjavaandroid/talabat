import 'package:flutter/material.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:velocity_x/velocity_x.dart';

class CurrencyHStack extends StatelessWidget {
  CurrencyHStack(
    this.children, {
    this.alignment,
    this.crossAlignment,
    this.axisSize,
    Key? key,
  }) : super(key: key);

  final List<Widget> children;
  final MainAxisAlignment? alignment;
  final CrossAxisAlignment? crossAlignment;
  final MainAxisSize? axisSize;

  @override
  Widget build(BuildContext context) {
    return HStack(
      !Utils.currencyLeftSided ? children.reversed.toList() : children,
      alignment: alignment,
      crossAlignment: crossAlignment,
      axisSize: axisSize,
    );
  }
}
