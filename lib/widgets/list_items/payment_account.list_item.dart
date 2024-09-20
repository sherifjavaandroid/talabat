import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/payment_account.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class PaymentAccountListItem extends StatelessWidget {
  const PaymentAccountListItem(
    this.paymentAccount, {
    required this.onEditPressed,
    required this.onStatusPressed,
    Key? key,
  }) : super(key: key);
  //
  final PaymentAccount paymentAccount;
  final Function onEditPressed;
  final Function onStatusPressed;
  @override
  Widget build(BuildContext context) {
    return HStack(
      [
        VStack(
          [
            paymentAccount.name.text.bold.xl.make(),
            paymentAccount.number.text.semiBold.lg.make(),
            'Instructions'.tr().text.light.make(),
            "${paymentAccount.instructions}".text.light.make(),
          ],
        ).expand(),
        UiSpacer.horizontalSpace(),
        //actions
        VStack(
          [
            //edit
            CustomButton(
              icon: FlutterIcons.edit_ant,
              iconSize: 18,
              height: 20,
              onPressed: onEditPressed,
            ),
            UiSpacer.vSpace(),
            //delete
            CustomButton(
              icon: paymentAccount.isActive
                  ? FlutterIcons.close_ant
                  : FlutterIcons.check_ant,
              iconSize: 18,
              height: 20,
              color: paymentAccount.isActive ? Colors.red : Colors.green,
              onPressed: onStatusPressed,
            ),
          ],
        ),
      ],
    )
        .p12()
        .box
        .outerShadow
        .color(
          paymentAccount.isActive
              ? context.theme.colorScheme.surface
              : Vx.gray200,
        )
        .roundedSM
        .make()
        .px20();
  }
}
