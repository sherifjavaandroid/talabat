import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/permission.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class RequestBGLocationPermissionView extends StatefulWidget {
  const RequestBGLocationPermissionView(this.vm, {Key? key}) : super(key: key);

  final PermissionViewModel vm;

  @override
  State<RequestBGLocationPermissionView> createState() =>
      _RequestBGLocationPermissionViewState();
}

class _RequestBGLocationPermissionViewState
    extends State<RequestBGLocationPermissionView> {
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        UiSpacer.vSpace(),
        "Background Location Permission"
            .tr()
            .text
            .xl3
            .extraBlack
            .center
            .makeCentered(),
        UiSpacer.vSpace(),
        //more info
        VStack(
          [
            UiSpacer.vSpace(),
            "In order to provide you with a seamless experience, we need to access your location data even when the app is not in use. This will allow us to offer personalized recommendations, send notifications about nearby events or promotions, and help you navigate your way to your destination."
                .tr()
                .text
                .lg
                .wordSpacing(2)
                .center
                .makeCentered(),
            UiSpacer.vSpace(),
            "Rest assured that we take your privacy seriously and will only use your location data in accordance with our privacy policy. We will never sell or share your data with third parties."
                .tr()
                .text
                .lg
                .wordSpacing(2)
                .center
                .makeCentered(),
            UiSpacer.vSpace(),
          ],
        ).scrollVertical().expand(),
        CustomButton(
          shapeRadius: 25,
          title: "Next".tr(),
          onPressed: widget.vm.handleBackgroundLocationPermission,
        ),
        UiSpacer.vSpace(10),
        Visibility(
          visible: !Platform.isIOS,
          child: CustomTextButton(
            title: "Skip".tr(),
            onPressed: widget.vm.nextStep,
          ).wFull(context),
        ),
        UiSpacer.vSpace(10),
      ],
    ).p32().safeArea();
  }
}
