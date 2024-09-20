import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/views/pages/order/widgets/wallet_transfer.dialog.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderActions extends StatelessWidget {
  const OrderActions({
    this.canChatCustomer = false,
    this.busy = false,
    required this.order,
    required this.processOrderCompletion,
    required this.processOrderEnroute,
    Key? key,
  }) : super(key: key);

  final bool canChatCustomer;
  final bool busy;
  final Order order;
  final Function processOrderCompletion;
  final Function processOrderEnroute;

  @override
  Widget build(BuildContext context) {
    return (!["failed", "delivered", "cancelled"].contains(order.status))
        ? SafeArea(
            child: busy
                ? BusyIndicator().centered().wh(Vx.dp40, Vx.dp40)
                : VStack(
                    [
                      //
                      order.status != "enroute"
                          ? CustomButton(
                              onLongPress: processOrderEnroute,
                              title: "Long Press To Start".tr(),
                              icon: FlutterIcons.arrow_down_thick_mco,
                            )
                          : CustomButton(
                              onLongPress: processOrderCompletion,
                              title: "Long Press To Complete".tr(),
                              icon: FlutterIcons.arrow_down_thick_mco,
                            ),

                      // topup customer wallet
                      UiSpacer.verticalSpace(),
                      CustomButton(
                        title: "Topup Customer Wallet".tr(),
                        icon: FlutterIcons.wallet_ant,
                        onPressed: () {
                          //show the wallet transfer dialog
                          showDialog(
                            context: context,
                            barrierDismissible: false,
                            builder: (context) {
                              return Dialog(
                                child: WalletTransferDialog(order),
                              );
                            },
                          );
                        },
                      ),
                    ],
                  ),
          )
            .box
            .p20
            .outerShadow2Xl
            .shadow
            .color(context.cardColor)
            .make()
            .wFull(context)
        : UiSpacer.emptySpace();
  }
}
