import 'package:flutter/material.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:velocity_x/velocity_x.dart';

class CurrencyHStack extends StatefulWidget {
  const CurrencyHStack(
    this.mChildren, {
    this.alignment,
    this.crossAlignment,
    this.axisSize,
    Key? key,
  }) : super(key: key);

  final List<Widget> mChildren;
  final MainAxisAlignment? alignment;
  final CrossAxisAlignment? crossAlignment;
  final MainAxisSize? axisSize;

  @override
  State<CurrencyHStack> createState() => _CurrencyHStackState();
}

class _CurrencyHStackState extends State<CurrencyHStack> {
  List<Widget> children = [];

  @override
  void initState() {
    super.initState();
    //swap the view
    if (!Utils.currencyLeftSided) {
      children = widget.mChildren.reversed.toList();
    } else {
      children = widget.mChildren;
    }
  }

  @override
  Widget build(BuildContext context) {
    return HStack(
      children,
    );
  }
}
