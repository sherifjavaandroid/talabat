import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_strings.dart';
import 'package:fuodz/models/checkout.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/checkout.vm.dart';
import 'package:fuodz/views/pages/checkout/widgets/driver_cash_delivery_note.view.dart';
import 'package:fuodz/views/pages/checkout/widgets/order_delivery_address.view.dart';
import 'package:fuodz/views/pages/checkout/widgets/payment_methods.view.dart';
import 'package:fuodz/views/pages/checkout/widgets/schedule_order.view.dart';
import 'package:fuodz/widgets/base.page.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/cards/order_summary.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:flutter/services.dart';

class CheckoutPage extends StatelessWidget {
  const CheckoutPage({
    required this.checkout,
    Key? key,
  }) : super(key: key);

  final CheckOut checkout;

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<CheckoutViewModel>.reactive(
      viewModelBuilder: () => CheckoutViewModel(context, checkout),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return BasePage(
          showAppBar: true,
          showLeadingAction: true,
          title: "Checkout".tr(),
          body: VStack(
            [
              //
              UiSpacer.verticalSpace(),
              Visibility(
                visible: !vm.isPickup,
                child: CustomTextFormField(
                  labelText:
                      "Driver Tip".tr() + " (${AppStrings.currencySymbol})",
                  textEditingController: vm.driverTipTEC,
                  keyboardType: TextInputType.number,
                  inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                  onFieldSubmitted: (value) => vm.updateTotalOrderSummary(),
                ).pOnly(bottom: Vx.dp20),
              ),
              //
              CustomTextFormField(
                labelText: "Note".tr(),
                textEditingController: vm.noteTEC,
              ),

              //note
              UiSpacer.divider().py12(),

              //pickup time slot
              ScheduleOrderView(vm),

              //its pickup
              OrderDeliveryAddressPickerView(vm),

              //payment options
              Visibility(
                visible: vm.canSelectPaymentOption,
                child: PaymentMethodsView(vm),
              ),

              //order final price preview
              OrderSummary(
                subTotal: vm.checkout!.subTotal,
                discount: (vm.checkout!.coupon?.for_delivery ?? false)
                    ? null
                    : vm.checkout!.discount,
                deliveryDiscount: (vm.checkout!.coupon?.for_delivery ?? false)
                    ? vm.checkout!.deliveryDiscount
                    : null,
                deliveryFee: vm.checkout!.deliveryFee,
                tax: vm.checkout!.tax,
                vendorTax: vm.vendor!.tax,
                driverTip: double.tryParse("${vm.driverTipTEC.text}") ?? 0.00,
                total: vm.checkout!.totalWithTip,
                fees: vm.vendor!.fees,
              ),

              //show notice it driver should be paid in cash
              if (vm.checkout!.deliveryAddress != null)
                CheckoutDriverCashDeliveryNoticeView(
                  vm.checkout!.deliveryAddress!,
                ),
              //terms and conditions checkbox
              HStack(
                [
                  Checkbox(
                    value: vm.paymentTermsAgreed,
                    onChanged: (value) {
                      vm.paymentTermsAgreed = value ?? false;
                      vm.notifyListeners();
                    },
                  ),
                  //
                  "By proceeding to place order, you agree that you are bound by our"
                      .tr()
                      .richText
                      .withTextSpanChildren(
                        [
                          "  ".textSpan.make(),
                          "Terms & Conditions"
                              .tr()
                              .textSpan
                              .color(AppColor.primaryColor)
                              .bold
                              .underline
                              .tap(() {
                            vm.openPaymentTerms();
                          }).make(),
                          "  ".textSpan.make(),
                        ],
                      )
                      .make()
                      .expand(),
                ],
                // crossAlignment: CrossAxisAlignment.start,
                alignment: MainAxisAlignment.start,
              ).py(20),
              //
              CustomButton(
                title: "PLACE ORDER".tr().padRight(14),
                icon: FlutterIcons.basket_sli,
                onPressed: vm.paymentTermsAgreed ? () => vm.placeOrder() : null,
                loading: vm.isBusy,
              ).centered().py16(),
            ],
          ).p20().scrollVertical().pOnly(bottom: context.mq.viewInsets.bottom),
        );
      },
    );
  }
}
