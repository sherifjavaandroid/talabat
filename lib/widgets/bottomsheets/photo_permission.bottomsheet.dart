import 'dart:io';

import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class PhotoPermissionDialog extends StatelessWidget {
  const PhotoPermissionDialog({Key? key}) : super(key: key);

  //
  @override
  Widget build(BuildContext context) {
    return Dialog(
      child: VStack(
        [
          //title
          "Photo Permission Request".tr().text.semiBold.xl.make().py12(),
          ("${AppStrings.appName} " +
                  "needs your permission to access your photos to select a photo for your profile picture, or to upload order photos."
                      .tr() +
                  "\n\n" +
                  "We understand that your privacy is important, and we will not use your photos for any other purpose."
                      .tr() +
                  "\n" +
                  "You can change this permission in your device settings."
                      .tr())
              .text
              .make(),
          UiSpacer.verticalSpace(),
          CustomButton(
            title: Platform.isIOS ? "Next" : "Request Permission".tr(),
            onPressed: () {
              AppService().navigatorKey.currentContext?.pop(true);
            },
          ).py12(),
          Visibility(
            visible: !Platform.isIOS,
            child: CustomButton(
              title: "Cancel".tr(),
              color: Colors.grey[400],
              onPressed: () {
                AppService().navigatorKey.currentContext?.pop(false);
              },
            ),
          ),
        ],
      ).p20().wFull(context).scrollVertical(), //.hTwoThird(context),
    );
  }
}
