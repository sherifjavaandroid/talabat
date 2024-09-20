import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class LocationPermissionView extends StatelessWidget {
  const LocationPermissionView({
    Key? key,
    required this.onResult,
  }) : super(key: key);

  //
  final Function(bool) onResult;

  //
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //title
        "Location Permission Request".tr().text.semiBold.xl.make().py12(),
        ("${AppStrings.appName} " +
                "requires your location permission to enable customer track your location when delivering their order"
                    .tr())
            .text
            .make(),
        UiSpacer.verticalSpace(),
        CustomButton(
          title: "Next".tr(),
          onPressed: () {
            onResult(true);
          },
        ).py12(),
      ],
    ).p20().wFull(context).scrollVertical();
  }
}
