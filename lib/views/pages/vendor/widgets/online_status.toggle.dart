import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/vendor_details.view_model.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OnlineStatusToggle extends StatelessWidget {
  const OnlineStatusToggle(
    this.vm, {
    super.key,
  });

  final VendorDetailsViewModel vm;

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        HStack(
          [
            "Status".tr().text.medium.lg.make().expand(),
            ((vm.vendor!.isOpen) ? "Open".tr() : "Close".tr())
                .text
                .semiBold
                .xl
                .color((vm.vendor!.isOpen)
                    ? AppColor.openColor
                    : AppColor.closeColor)
                .make(),
          ],
        ),
        //
        CustomButton(
          title: (vm.vendor!.isOpen) ? "Tap to Close".tr() : "Tap to Open".tr(),
          color:
              !(vm.vendor!.isOpen) ? AppColor.openColor : AppColor.closeColor,
          loading: vm.busy(vm.vendor!.isOpen),
          elevation: 0,
          onPressed: vm.toggleVendorAvailablity,
        ).h(32).wFull(context).py12(),

        UiSpacer.divider(),
      ],
    );
  }
}
