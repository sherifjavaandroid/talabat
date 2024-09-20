import 'package:flutter/material.dart';
import 'package:flutter_form_builder/flutter_form_builder.dart';
import 'package:fuodz/services/custom_form_builder_validator.service.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/payment_accounts.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';
import 'package:fuodz/extensions/context.dart';

class NewPaymentAccountBottomSheet extends StatefulWidget {
  NewPaymentAccountBottomSheet(this.vm, {Key? key}) : super(key: key);

  final PaymentAccountsViewModel vm;

  @override
  State<NewPaymentAccountBottomSheet> createState() =>
      _NewPaymentAccountBottomSheetState();
}

class _NewPaymentAccountBottomSheetState
    extends State<NewPaymentAccountBottomSheet> {
  GlobalKey<FormBuilderState> formBuilderKey = GlobalKey<FormBuilderState>();
  bool isLoading = false;

  @override
  Widget build(BuildContext context) {
    //
    return VStack(
      [
        //
        UiSpacer.formVerticalSpace(),
        "New Payment Account".tr().text.semiBold.xl2.make(),
        UiSpacer.formVerticalSpace(),
        UiSpacer.formVerticalSpace(),
        //
        FormBuilder(
          key: formBuilderKey,
          child: VStack(
            [
              //
              FormBuilderTextField(
                name: 'name',
                decoration: InputDecoration(
                  labelText: 'Account Name'.tr(),
                  border: OutlineInputBorder(),
                ),
                onChanged: (value) {},
                validator: CustomFormBuilderValidator.required,
                textInputAction: TextInputAction.next,
              ),
              UiSpacer.formVerticalSpace(),
              FormBuilderTextField(
                name: 'number',
                decoration: InputDecoration(
                  labelText: 'Account Number'.tr(),
                  border: OutlineInputBorder(),
                ),
                onChanged: (value) {},
                validator: CustomFormBuilderValidator.required,
                keyboardType: TextInputType.number,
                textInputAction: TextInputAction.next,
              ),
              UiSpacer.formVerticalSpace(),
              FormBuilderTextField(
                name: 'instructions',
                minLines: 4,
                maxLines: 5,
                decoration: InputDecoration(
                  labelText: 'Instructions'.tr(),
                  border: OutlineInputBorder(),
                ),
                onChanged: (value) {},
                textInputAction: TextInputAction.next,
              ),
              UiSpacer.formVerticalSpace(),
              FormBuilderCheckbox(
                name: 'is_active',
                title: "Active".tr().text.make(),
                onChanged: (value) {},
              ),
              UiSpacer.formVerticalSpace(),
              CustomButton(
                loading: isLoading,
                title: "Save".tr(),
                onPressed: () async {
                  setState(() {
                    isLoading = true;
                  });
                  final result =
                      await widget.vm.saveNewPaymentAccount(formBuilderKey);
                  setState(() {
                    isLoading = false;
                  });

                  //
                  if (result) {
                    context.pop();
                  }
                },
              ).wFull(context),
              UiSpacer.formVerticalSpace(),
            ],
          ),
        ),
      ],
    ).p20().scrollVertical().hThreeForth(context).pOnly(
          bottom: context.mq.viewInsets.bottom,
        );
  }
}
