import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/order_details.vm.dart';
import 'package:fuodz/widgets/cards/custom.visibility.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:localize_and_translate/localize_and_translate.dart';

class OrderPaymentInfoView extends StatelessWidget {
  const OrderPaymentInfoView(this.vm, {Key? key}) : super(key: key);
  final OrderDetailsViewModel vm;

  //
  @override
  Widget build(BuildContext context) {
    return VStack(
      [
        //
        HStack(
          [
            //Payment option
            VStack(
              [
                //
                "Payment Method".tr().text.gray500.medium.sm.make(),
                //
                "${vm.order.paymentMethod?.name.capitalized}"
                    .text
                    .medium
                    .xl
                    .make(),
                //
              ],
            ).expand(),

            //Payment status
            VStack(
              [
                //
                "Payment Status".tr().text.gray500.medium.sm.make(),
                //
                "${vm.order.paymentStatus.tr().capitalized}"
                    .text
                    .color(AppColor.getStausColor(vm.order.paymentStatus))
                    .medium
                    .xl
                    .make(),
                //
              ],
            ).expand(),
          ],
        ),
        //
        //show payer if order is parcel order
        CustomVisibilty(
          visible: vm.order.isPackageDelivery,
          child: VStack(
            [
              UiSpacer.vSpace(),
              "Order Payer".tr().text.medium.make(),
              (vm.order.payer == "1" ? "Sender" : "Receiver")
                  .tr()
                  .text
                  .xl
                  .semiBold
                  .make(),
              UiSpacer.vSpace(10),
            ],
          ),
        ),
      ],
    );
  }
}
