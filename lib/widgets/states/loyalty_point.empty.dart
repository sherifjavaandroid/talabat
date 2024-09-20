import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class EmptyLoyaltyPointReport extends StatelessWidget {
  const EmptyLoyaltyPointReport({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        Image.asset(
          AppImages.emptyLoyaltyPoints,
          fit: BoxFit.cover,
        )
            .wh(context.percentWidth * 50, context.percentWidth * 50)
            .box
            .makeCentered()
            .wFull(context),
        UiSpacer.vSpace(5),

        "No Recent Earned Points".tr().text.semiBold.xl2.make(),
        10.heightBox,
        "There are currently no earned points to display. Keep participating in activities and making purchases to earn points and unlock exciting rewards."
            .tr()
            .text
            .center
            .make(),
      ],
      crossAlignment: CrossAxisAlignment.center,
      alignment: MainAxisAlignment.center,
    ).wFull(context);
  }
}
