import 'package:flutter/material.dart';
import 'package:fuodz/models/order.dart';
import 'package:fuodz/services/validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/wallet_transfer.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:fuodz/widgets/custom_text_form_field.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:stacked/stacked.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class WalletTransferDialog extends StatelessWidget {
  const WalletTransferDialog(this.order, {Key? key}) : super(key: key);

  final Order order;
  @override
  Widget build(BuildContext context) {
    return ViewModelBuilder<WalletTransferViewModel>.reactive(
      viewModelBuilder: () => WalletTransferViewModel(context, order: order),
      builder: (context, vm, child) {
        return Form(
          key: vm.formKey,
          child: VStack(
            [
              "Topup Customer Wallet".tr().text.semiBold.xl.make(),
              "Please enter amount to transfer from your account to customer wallet"
                  .tr()
                  .text
                  .medium
                  .sm
                  .make()
                  .py4(),
              UiSpacer.verticalSpace(),
              CustomTextFormField(
                labelText: "Amount".tr(),
                textEditingController: vm.transferAmountTEC,
                keyboardType: TextInputType.number,
                validator: (value) => FormValidator.validateCustom(
                  value,
                  name: "Amount".tr(),
                ),
              ),
              UiSpacer.verticalSpace(),
              CustomButton(
                title: "Transfer".tr(),
                loading: vm.busy(vm.transferAmountTEC),
                onPressed: vm.initiateWalletTransfer,
              ).wFull(context),
              CustomButton(
                title: "Cancel".tr(),
                color: Colors.grey,
                loading: vm.busy(vm.transferAmountTEC),
                onPressed: () {
                  context.pop();
                },
              ).wFull(context).py8(),
            ],
          ).p20().scrollVertical().pOnly(bottom: context.mq.viewInsets.bottom),
        );
      },
    );
  }
}
