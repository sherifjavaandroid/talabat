import 'package:flutter/material.dart';
import 'package:velocity_x/velocity_x.dart';

class LoadingShimmer extends StatelessWidget {
  const LoadingShimmer({this.loading = true, this.child, Key? key})
      : super(key: key);
  final Widget? child;
  final bool loading;
  @override
  Widget build(BuildContext context) {
    //
    final linerHeight = (context.percentHeight * 8) * 0.17;
    //
    if (!loading) {
      return child ?? Container();
    }
    return VxBox(
      child: VStack(
        [
          Container(
            color: Colors.grey[400],
          ).h(linerHeight),
          Container(
            color: Colors.grey[400],
          ).h(linerHeight).py4(),
          Container(
            color: Colors.grey[400],
          ).h(linerHeight),
          Container(
            color: Colors.grey[400],
          ).h(linerHeight).py4(),
          Container(
            color: Colors.grey[400],
          ).h(linerHeight),
        ],
      ),
    )
        .height(context.percentHeight * 12)
        .width(context.percentWidth * 100)
        .clip(Clip.antiAlias)
        .make()
        .shimmer(
          primaryColor: context.theme.colorScheme.surface,
          secondaryColor: Colors.grey[300],
        );
  }
}
