import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/permission.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:package_info/package_info.dart';
import 'package:velocity_x/velocity_x.dart';

class RequestOverlayPermissionView extends StatefulWidget {
  const RequestOverlayPermissionView(this.vm, {Key? key}) : super(key: key);

  final PermissionViewModel vm;

  @override
  State<RequestOverlayPermissionView> createState() =>
      _RequestOverlayPermissionViewState();
}

class _RequestOverlayPermissionViewState
    extends State<RequestOverlayPermissionView> {
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        UiSpacer.vSpace(),
        VStack(
          [
            //header
            FutureBuilder<PackageInfo>(
              future: PackageInfo.fromPlatform(),
              builder: (context, snapshot) {
                String appName = "Driver App".tr();
                if (snapshot.hasData) {
                  appName = snapshot.data!.appName;
                }
                return VStack(
                  [
                    //title
                    "Allow %s to display over other apps"
                        .tr()
                        .fill([appName])
                        .text
                        .xl3
                        .center
                        .extraBlack
                        .make()
                        .py12(),
                    UiSpacer.vSpace(10),
                    //sub-body
                    "Allow %s to display over other apps in order to receive orders when you are using other apps or app is in background."
                        .tr()
                        .fill([appName])
                        .text
                        .wordSpacing(2)
                        .lg
                        .center
                        .makeCentered(),
                    UiSpacer.vSpace(),
                  ],
                );
              },
            ),

            UiSpacer.vSpace(),
            "We need to draw over other apps on your device. This will allow us to offer features such as floating widgets, pop-up notifications, and other interactive elements."
                .tr()
                .text
                .wordSpacing(2)
                .lg
                .center
                .makeCentered(),
            UiSpacer.vSpace(),
          ],
          crossAlignment: CrossAxisAlignment.center,
        ).scrollVertical().expand(),
        CustomButton(
          shapeRadius: 25,
          title: "Go to settings".tr(),
          onPressed: widget.vm.handleOverlayPermission,
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
