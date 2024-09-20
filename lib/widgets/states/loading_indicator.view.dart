import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:velocity_x/velocity_x.dart';

class LoadingIndicator extends StatelessWidget {
  const LoadingIndicator({
    required this.child,
    this.loadingWidget,
    this.loading = false,
    this.hideChild = false,
    Key? key,
  }) : super(key: key);

  final bool loading;
  final bool hideChild;
  final Widget child;
  final Widget? loadingWidget;
  @override
  Widget build(BuildContext context) {
    if (hideChild && loading) {
      return loadingWidget ?? BusyIndicator().p12();
    }
    //
    return HStack(
      [
        child.expand(),
        loading
            ? (loadingWidget ?? BusyIndicator().p12())
            : UiSpacer.emptySpace(),
      ],
    );
  }
}
