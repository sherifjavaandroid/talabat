import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/utils/utils.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderActions extends StatelessWidget {
  const OrderActions({
    this.canChatCustomer,
    this.busy = false,
    required this.order,
    this.onEditPressed,
    this.onCancelledPressed,
    this.onAssignPressed,
    this.onPrintPressed,
    required this.onAcceptPressed,
    Key? key,
  }) : super(key: key);

  final bool? canChatCustomer;
  final bool busy;
  final Order order;
  final Function? onEditPressed;
  final Function? onCancelledPressed;
  final Function? onAcceptPressed;
  final Function? onAssignPressed;
  final Function? onPrintPressed;

  @override
  Widget build(BuildContext context) {
    /*
    FloatingActionButton.extended(
                  onPressed: () => vm.printOrder(),
                  label: "Print".text.white.make(),
                  backgroundColor: AppColor.primaryColor,
                  icon: Icon(
                    FlutterIcons.print_faw,
                    color: Colors.white,
                  ),
                )
                */

    bool ispending = order.status == "pending";
    bool actionsNeeded =
        !["failed", "cancelled", "delivered"].contains(order.status);
    return SafeArea(
      child: busy
          ? BusyIndicator().centered().wh(Vx.dp40, Vx.dp40)
          : VStack(
              [
                //pending action
                if (ispending)
                  HStack(
                    [
                      CustomButton(
                        color: Colors.red,
                        title: "Reject".tr(),
                        onPressed: onCancelledPressed,
                      ).expand(),
                      UiSpacer.hSpace(),
                      CustomButton(
                        color: Colors.green,
                        title: "Accept".tr(),
                        onPressed: onAcceptPressed,
                      ).expand(),
                    ],
                  ),

                // note pending actions
                if (!ispending && actionsNeeded)
                  VStack(
                    [
                      HStack(
                        [
                          //edit order
                          if (order.canEditStatus)
                            Expanded(
                              child: CustomButton(
                                title: "Edit".tr(),
                                icon: FlutterIcons.edit_ant,
                                onPressed: onEditPressed,
                              ),
                            ),
                          //cancel order
                          if (order.canCancel)
                            Expanded(
                              child: CustomButton(
                                color: Colors.red,
                                title: "Cancel".tr(),
                                icon: FlutterIcons.close_ant,
                                onPressed: onCancelledPressed,
                              ),
                            ),
                        ],
                        spacing: 10,
                      ),

                      //assign driver
                      if (order.canAssignDriver)
                        CustomButton(
                          title: "Assign Order".tr(),
                          icon: FlutterIcons.truck_delivery_mco,
                          onPressed: onAssignPressed,
                        ),
                    ],
                    spacing: 20,
                  ),

                // print order always
                CustomButton(
                  child: HStack(
                    [
                      Icon(
                        FlutterIcons.print_faw,
                        color: Utils.textColorByTheme(),
                      ),
                      "Print"
                          .tr()
                          .text
                          .xl
                          .semiBold
                          .color(Utils.textColorByTheme())
                          .make(),
                    ],
                    spacing: 10,
                  ).centered(),
                  onPressed: onPrintPressed,
                ).wFull(context),
              ],
              spacing: 12,
            ),
    )
        .box
        .p20
        .outerShadow2Xl
        .shadow
        .color(context.cardColor)
        .make()
        .wFull(context);
  }
}
