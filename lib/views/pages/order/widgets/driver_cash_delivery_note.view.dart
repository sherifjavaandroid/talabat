import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_finance_settings.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class CheckoutDriverCashDeliveryNoticeView extends StatelessWidget {
  const CheckoutDriverCashDeliveryNoticeView({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Visibility(
      visible: AppFinanceSettings.collectDeliveryFeeInCash,
      child: VxBox(
        child: "".richText.withTextSpanChildren(
          [
            "Note:".tr().textSpan.red500.extraBlack.size(14).make(),
            " ".textSpan.make(),
            "Irrespective of order selected payment method, you are required to collect delivery fee from customer. Thank you"
                .tr()
                .textSpan
                .size(14)
                .make(),
          ],
        ).make(),
      ).p12.border(color: Colors.grey).roundedSM.make().py12(),
    );
  }
}
