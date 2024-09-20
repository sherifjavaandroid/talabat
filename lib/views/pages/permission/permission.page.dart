import 'package:flutter/material.dart';
import 'package:fuodz/view_models/permission.vm.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';

class PermissionPage extends StatelessWidget {
  const PermissionPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<PermissionViewModel>.reactive(
      viewModelBuilder: () => PermissionViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          extendBodyBehindAppBar: true,
          body: VStack(
            [
              //page view
              PageView(
                controller: vm.pageController,
                onPageChanged: vm.onPageChanged,
                physics: NeverScrollableScrollPhysics(),
                children: vm.permissionPages(),
              ).expand(),
              AnimatedSmoothIndicator(
                activeIndex: vm.currentStep,
                count: vm.permissionPages().length,
                effect: JumpingDotEffect(
                  activeDotColor: context.theme.colorScheme.primary,
                  dotColor: Colors.grey.shade400,
                  spacing: 15,
                  dotWidth: 8,
                  dotHeight: 8,
                  paintStyle: PaintingStyle.fill,
                ),
              ).centered().p12().safeArea(top: false),
            ],
          ),
        );
      },
    );
  }
}
