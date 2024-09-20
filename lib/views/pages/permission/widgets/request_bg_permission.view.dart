import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/permission.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class RequestBGPermissionView extends StatefulWidget {
  const RequestBGPermissionView(this.vm, {Key? key}) : super(key: key);

  final PermissionViewModel vm;

  @override
  State<RequestBGPermissionView> createState() =>
      _RequestBGPermissionViewState();
}

class _RequestBGPermissionViewState extends State<RequestBGPermissionView> {
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        UiSpacer.vSpace(),
        "Background Permission Request"
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
            "We need to run our app in the background even when you're not using it. This will allow us to offer personalized notifications, updates, and other features that require real-time data."
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
          onPressed: widget.vm.handleBackgroundPermission,
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
