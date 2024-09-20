import 'package:flutter/material.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/models/payment_account.dart';
import 'package:fuodz/services/validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/earning.vm.dart';
import 'package:fuodz/widgets/busy_indicator.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/buttons/custom_text_button.dart';
import 'package:fuodz/widgets/currency_hstack.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';

class EarningBottomSheet extends StatelessWidget {
  const EarningBottomSheet({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<EarningViewModel>.reactive(
      viewModelBuilder: () => EarningViewModel(context),
      onViewModelReady: (vm) => vm.initialise(),
      builder: (context, vm, child) {
        return VStack(
          [
            "Earning".tr().text.medium.xl2.makeCentered(),
            vm.isBusy
                ? BusyIndicator().centered().p20().expand()
                : VStack(
                    [
                      //amount
                      CurrencyHStack(
                        [
                          //currency
                          "${vm.currency?.symbol}".text.medium.xl.make().px4(),
                          //earning
                          "${vm.earning?.amount}"
                              .currencyValueFormat()
                              .text
                              .semiBold
                              .xl3
                              .make(),
                        ],
                        crossAlignment: CrossAxisAlignment.center,
                        alignment: MainAxisAlignment.center,
                      ).py12(),
                      //updated at
                      "${vm.earning?.formattedUpdatedDate}"
                          .text
                          .medium
                          .lg
                          .makeCentered(),

                      //request payout
                      Visibility(
                        visible: !vm.showPayout,
                        child: CustomButton(
                          title: "Request Payout".tr(),
                          onPressed: vm.requestEarningPayout,
                        ).py20(),
                      ),

                      //payout form
                      Visibility(
                        visible: vm.showPayout,
                        child: Form(
                          key: vm.formKey,
                          child: vm.busy(vm.paymentAccounts)
                              ? BusyIndicator().centered().py20()
                              : VStack(
                                  [
                                    //
                                    Divider(thickness: 2).py12(),
                                    "Request Payout"
                                        .tr()
                                        .text
                                        .semiBold
                                        .xl
                                        .make(),
                                    UiSpacer.verticalSpace(),
                                    //
                                    "Payment Account"
                                        .tr()
                                        .text
                                        .base
                                        .light
                                        .make(),
                                    DropdownButtonFormField<PaymentAccount>(
                                      decoration: InputDecoration.collapsed(
                                          hintText: ""),
                                      value: vm.selectedPaymentAccount,
                                      onChanged: (value) {
                                        vm.selectedPaymentAccount = value;
                                        vm.notifyListeners();
                                      },
                                      items: vm.paymentAccounts.map(
                                        (e) {
                                          return DropdownMenuItem(
                                              value: e,
                                              child:
                                                  Text("${e.name}(${e.number})")
                                              // .text
                                              // .make(),
                                              );
                                        },
                                      ).toList(),
                                    )
                                        .p12()
                                        .box
                                        .border(color: AppColor.accentColor)
                                        .roundedSM
                                        .make()
                                        .py4(),
                                    //
                                    UiSpacer.verticalSpace(space: 10),
                                    CustomTextFormField(
                                      labelText: "Amount".tr(),
                                      textEditingController: vm.amountTEC,
                                      keyboardType:
                                          TextInputType.numberWithOptions(
                                        decimal: true,
                                      ),
                                      validator: (value) =>
                                          FormValidator.validateCustom(
                                        value,
                                        rules:
                                            "required||numeric||lte:${vm.earning?.amount}",
                                      ),
                                    ).py12(),
                                    CustomButton(
                                      title: "Request Payout".tr(),
                                      loading:
                                          vm.busy(vm.selectedPaymentAccount),
                                      onPressed: vm.processPayoutRequest,
                                    ).centered().py12(),
                                    //
                                    CustomTextButton(
                                      title: "Close".tr(),
                                      onPressed: () {
                                        vm.showPayout = false;
                                        vm.notifyListeners();
                                      },
                                    ).centered(),
                                  ],
                                ).scrollVertical(),
                        ),
                      ),
                    ],
                    crossAlignment: CrossAxisAlignment.center,
                    alignment: MainAxisAlignment.center,
                  ),
          ],
        )
            .p20()
            .h(context.percentHeight * (vm.showPayout ? 80 : 34))
            .box
            .color(context.theme.colorScheme.surface)
            .topRounded()
            .make();
      },
    );
  }
}
