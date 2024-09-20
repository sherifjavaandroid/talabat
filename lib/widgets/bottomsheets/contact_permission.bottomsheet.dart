import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/services/app.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class ContactPermissionDialog extends StatelessWidget {
  const ContactPermissionDialog({Key? key}) : super(key: key);

  //
  @override
  Widget build(BuildContext context) {
    return Dialog(
      child: VStack(
        [
          //title
          "Contact Permission Request".tr().text.semiBold.xl.make().py12(),
          ("${AppStrings.appName} " +
                  "collects and utilizes your contact information to enable the 'Autofill Recipient Details' feature when you are placing orders. This process involves accessing your contact list solely for the purpose of identifying and suggesting recipient information, ensuring a streamlined and efficient order placement experience."
                      .tr())
              .text
              .make(),

          UiSpacer.verticalSpace(),
          CustomButton(
            title: "Request Permission".tr(),
            onPressed: () {
              AppService().navigatorKey.currentContext?.pop(true);
            },
          ).py12(),
          CustomButton(
            title: "Cancel".tr(),
            color: Colors.grey[400],
            onPressed: () {
              AppService().navigatorKey.currentContext?.pop(false);
            },
          ),
        ],
      ).p20().wFull(context).scrollVertical(), //.hTwoThird(context),
    );
  }
}
