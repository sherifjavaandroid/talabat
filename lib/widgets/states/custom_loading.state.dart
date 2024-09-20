import 'package:flutter/material.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:velocity_x/velocity_x.dart';

class CustomLoadingStateView extends StatelessWidget {
  const CustomLoadingStateView({
    required this.child,
    this.loading = false,
    Key? key,
  }) : super(key: key);

  final Widget child;
  final bool loading;
  @override
  Widget build(BuildContext context) {
    return loading ? BusyIndicator().centered() : child;
  }
}
