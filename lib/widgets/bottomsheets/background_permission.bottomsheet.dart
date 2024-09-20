import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class BackgroundPermissionBottomSheet extends StatelessWidget {
  const BackgroundPermissionBottomSheet({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        Image.asset(
          AppImages.overflowPermission,
          width: context.percentWidth * 50,
          // height: context.percentWidth * 50,
        ).centered(),
        //description
        "Keep app running in background".tr().text.xl2.semiBold.make(),
        "Please allow the app to run in background to enable the app to listen for new order when the app is in background."
            .tr()
            .text
            .make(),
        20.heightBox,
        //button
        CustomButton(
          title: "Allow".tr(),
          onPressed: () => Navigator.pop(context, true),
        ).wFull(context),
        CustomTextButton(
          title: "Cancel".tr(),
          onPressed: () => Navigator.pop(context),
        ).wFull(context),

        20.heightBox,
      ],
      spacing: 10,
    ).p20().py12().box.white.topRounded().make();
  }
}
