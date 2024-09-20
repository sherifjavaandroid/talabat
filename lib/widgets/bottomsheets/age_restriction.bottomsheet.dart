import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_images.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class AgeRestrictionBottomSheet extends StatelessWidget {
  const AgeRestrictionBottomSheet({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        10.heightBox,
        Image.asset(
          AppImages.addressPin,
        ),
        5.heightBox,
        "Age Restriction".tr().text.xl2.bold.make(),
        5.heightBox,
        "You must be 18 years or older to purchase this product/service. By clicking continue you confirm that you are 18 years or older."
            .tr()
            .text
            .make(),
        30.heightBox,
        //actions
        HStack(
          [
            CustomTextButton(
              title: "No, Cancel".tr(),
              onPressed: () {
                Navigator.pop(context);
              },
            ).expand(flex: 2),
            20.widthBox,
            CustomButton(
              title: "Yes, I'm 18+".tr(),
              onPressed: () {
                Navigator.pop(context, true);
              },
            ).expand(flex: 3),
          ],
        ),
      ],
      crossAlignment: CrossAxisAlignment.center,
    )
        .scrollVertical(padding: EdgeInsets.all(20))
        .hTwoThird(context)
        .box
        .white
        .topRounded()
        .make();
  }
}
